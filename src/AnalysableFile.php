<?php
namespace NdB\PhpDocCheck;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AnalysableFile implements \JsonSerializable
{
    public $file;
    public $arguments;
    public $groupManager;
    protected $parser;
    protected $metrics = array(
        'cognitive'  => '\NdB\PhpDocCheck\Metrics\CognitiveComplexity',
        'cyclomatic' => '\NdB\PhpDocCheck\Metrics\CyclomaticComplexity'
    );
    
    public function __construct(
        \SplFileInfo $file,
        \PhpParser\Parser $parser,
        \GetOpt\GetOpt $arguments,
        GroupManager $groupManager
    ) {
        $this->file = $file;
        $this->parser = $parser;
        $this->arguments = $arguments;
        $this->groupManager = $groupManager;
    }

    public function analyse() : AnalysisResult
    {
        $analysisResult = new AnalysisResult($this);
        try {
            $statements = $this->parser->parse(file_get_contents($this->file->getRealPath()));
        } catch (\PhpParser\Error $e) {
            $finding = new \NdB\PhpDocCheck\Findings\Error(
                sprintf('Failed parsing: %s', $e->getRawMessage()),
                new InvalidFileNode,
                $this,
                new \NdB\PhpDocCheck\Metrics\InvalidFile(),
                0
            );
            $analysisResult->addProgress($finding);
            $this->groupManager->addFinding($finding);
            return $analysisResult;
        }
        $traverser  = new \PhpParser\NodeTraverser();
        $metricSlug = 'cognitive';
        if (array_key_exists($this->arguments->getOption('metric'), $this->metrics)) {
            $metricSlug = $this->arguments->getOption('metric');
        }
        $metric = new $this->metrics[$metricSlug];
        $traverser->addVisitor(new \PhpParser\NodeVisitor\NameResolver);
        $traverser->addVisitor(
            new \NdB\PhpDocCheck\NodeVisitors\ParentConnector()
        );
        $traverser->addVisitor(
            new \NdB\PhpDocCheck\NodeVisitors\MetricChecker($analysisResult, $this, $metric, $this->groupManager)
        );
        $traverser->traverse($statements);
        return $analysisResult;
    }

    public function jsonSerialize() : array
    {
        return array(
            'file'=>$this->file->getRealPath(),
        );
    }
}

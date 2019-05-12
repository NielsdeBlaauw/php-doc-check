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
        'cognitive'  => '\NdB\PhpDocCheck\Metrics\CognitiveComplexity', // @deprecated
        'cyclomatic' => '\NdB\PhpDocCheck\Metrics\CyclomaticComplexity', // @deprecated
        'metrics.deprecated.category'  => '\NdB\PhpDocCheck\Metrics\CategoryDeprecated',
        'metrics.deprecated.subpackage'  => '\NdB\PhpDocCheck\Metrics\SubpackageDeprecated',
        'metrics.complexity.length'  => '\NdB\PhpDocCheck\Metrics\FunctionLength',
        'metrics.complexity.cognitive'  => '\NdB\PhpDocCheck\Metrics\CognitiveComplexity',
        'metrics.complexity.cyclomatic'  => '\NdB\PhpDocCheck\Metrics\CognitiveComplexity',
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

    /**
     * Analyses the file for all requested metrics.
     */
    public function analyse() : AnalysisResult
    {
        $analysisResult = new AnalysisResult($this);
        try {
            $statements = $this->parser->parse(file_get_contents($this->file->getRealPath()));
        } catch (\PhpParser\Error $e) {
            $finding = new \NdB\PhpDocCheck\Findings\Error(
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
        
        $traverser->addVisitor(new \PhpParser\NodeVisitor\NameResolver);
        $traverser->addVisitor(
            new \NdB\PhpDocCheck\NodeVisitors\ParentConnector()
        );
        $traverser->addVisitor(
            new \NdB\PhpDocCheck\NodeVisitors\DocParser()
        );
        foreach ($this->arguments->getOption('metric') as $metricSlug) {
            if (array_key_exists($metricSlug, $this->metrics)) {
                $metric = new $this->metrics[$metricSlug];
                $traverser->addVisitor(
                    new \NdB\PhpDocCheck\NodeVisitors\MetricChecker(
                        $analysisResult,
                        $this,
                        $metric,
                        $this->groupManager
                    )
                );
            }
        }
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

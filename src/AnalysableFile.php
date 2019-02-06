<?php
namespace NdB\PhpDocCheck;

class AnalysableFile implements \JsonSerializable
{
    public $file;
    public $arguments;
    protected $parser;
    protected $metrics = array(
        'cognitive'  => '\NdB\PhpDocCheck\Metrics\CognitiveComplexity',
        'cyclomatic' => '\NdB\PhpDocCheck\Metrics\CyclomaticComplexity'
    );
    
    public function __construct(\SplFileInfo $file, \PhpParser\Parser $parser, \GetOpt\GetOpt $arguments)
    {
        $this->file = $file;
        $this->parser = $parser;
        $this->arguments = $arguments;
    }

    public function analyse() : AnalysisResult
    {
        $analysisResult = new AnalysisResult($this);
        try {
            $statements = $this->parser->parse(file_get_contents($this->file->getRealPath()));
        } catch (\PhpParser\Error $e) {
            $analysisResult->addFinding(
                new \NdB\PhpDocCheck\Findings\Error(
                    sprintf('Failed parsing: %s', $e->getRawMessage()),
                    new InvalidFileNode,
                    $this,
                    new \NdB\PhpDocCheck\Metrics\InvalidFile()
                )
            );
            return $analysisResult;
        }
        $traverser  = new \PhpParser\NodeTraverser();
        $metricSlug = 'cognitive';
        if (array_key_exists($this->arguments->getOption('metric'), $this->metrics)) {
            $metricSlug = $this->arguments->getOption('metric');
        }
        $metric = new $this->metrics[$metricSlug];
        $traverser->addVisitor(new \NdB\PhpDocCheck\NodeVisitor($analysisResult, $this, $metric));
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

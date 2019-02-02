<?php
namespace NdB\PhpDocCheck;

class AnalysableFile
{
    public $file;
    protected $parser;
    protected $arguments;
    
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
                    $e->getStartLine()
                )
            );
            return $analysisResult;
        }
        $traverser  = new \PhpParser\NodeTraverser();
        $metric = new Metrics\CyclomaticComplexity();
        $traverser->addVisitor(new \NdB\PhpDocCheck\NodeVisitor($analysisResult, $this->arguments, $metric));
        $traverser->traverse($statements);
        return $analysisResult;
    }
}

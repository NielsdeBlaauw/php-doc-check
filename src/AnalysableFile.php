<?php
namespace NdB\PhpDocCheck;

class AnalysableFile
{
    public $hasErrors = false;
    public $hasWarnings = false;
    public $findings = array();

    public function __construct(\SplFileInfo $file, \PhpParser\Parser $parser, $arguments)
    {
        $this->file = $file;
        $this->parser = $parser;
        $this->arguments = $arguments;
    }

    public function analyse()
    {
        try {
            $statements = $this->parser->parse(file_get_contents($this->file->getRealPath()));
        } catch (\PhpParser\Error $e) {
            $this->hasErrors = true;
            $this->findings[] = new \NdB\PhpDocCheck\Findings\Error(
                sprintf('Failed parsing: %s', $e->getRawMessage()),
                $e->getStartLine()
            );
            return 'I';
        }
        $traverser  = new \PhpParser\NodeTraverser();
        $traverser->addVisitor(new \NdB\PhpDocCheck\NodeVisitor($this));
        $traverser->traverse($statements);
        return $this->getProgressIndicator();
    }

    /**
     * Gives a visual progression value, based on analysis result
     */
    public function getProgressIndicator() : string
    {
        if (!$this->hasWarnings && !$this->hasErrors) {
            return '.';
        } elseif (!$this->hasErrors) {
            return 'W';
        }
        return 'E';
    }
}

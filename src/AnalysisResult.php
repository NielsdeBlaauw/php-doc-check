<?php
namespace NdB\PhpDocCheck;

class AnalysisResult
{
    public $hasErrors = false;
    public $hasWarnings = false;
    public $findings = array();
    public $sourceFile;
    public function __construct(AnalysableFile $sourceFile)
    {
        $this->sourceFile = $sourceFile;
    }

    public function addFinding(Findings\Finding $finding)
    {
        $this->findings[] = $finding;
        if (is_a($finding, 'NdB\PhpDocCheck\Findings\Error')) {
            $this->hasErrors = true;
        } elseif (is_a($finding, 'NdB\PhpDocCheck\Findings\Warning')) {
            $this->hasWarnings = true;
        }
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

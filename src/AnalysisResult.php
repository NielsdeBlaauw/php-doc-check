<?php
namespace NdB\PhpDocCheck;

class AnalysisResult implements \JsonSerializable
{
    public $hasErrors = false;
    public $hasWarnings = false;
    public $findings = array();
    public $sourceFile;

    public function __construct(AnalysableFile $sourceFile)
    {
        $this->sourceFile = $sourceFile;
    }

    public function getSourceFile():AnalysableFile
    {
        return $this->sourceFile;
    }

    public function addProgress(Findings\Finding $finding)
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

    public function jsonSerialize() : array
    {
        return array(
            'file'=>$this->sourceFile->file->getRealPath(),
            'findings'=>$this->findings,
        );
    }
}

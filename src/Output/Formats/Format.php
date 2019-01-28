<?php
namespace NdB\PhpDocCheck\Output\Formats;

abstract class Format
{
    public $analysisResults;

    /**
     * @param \NdB\PhpDocCheck\AnalysisResult[] $analysisResults
     */
    public function __construct(array $analysisResults)
    {
        $this->analysisResults = $analysisResults;
    }

    abstract public function get() : string;

    /**
     * Determines if this scan has 'failed' and should be fixed. Or if it was
     * flawless. CI will fail when a non zero exit code is returned.
     */
    public function getExitCode()
    {
        foreach ($this->analysisResults as $analysisResult) {
            if ($analysisResult->hasErrors || $analysisResult->hasWarnings) {
                return 1;
            }
        }
        return 0;
    }
}

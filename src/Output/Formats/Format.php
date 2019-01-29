<?php
namespace NdB\PhpDocCheck\Output\Formats;

abstract class Format
{
    public $channels = array();

    /**
     * @param \NdB\PhpDocCheck\Output\Channels\Channel[] $channels
     */
    public function __construct(array $channels)
    {
        $this->channels = $channels;
    }

    abstract public function get(array $results) : string;

    public function removeEmpty(\NdB\PhpDocCheck\AnalysisResult $analysisResult) : bool
    {
        return !empty($analysisResult->findings);
    }

    /**
     * Determines if this scan has 'failed' and should be fixed. Or if it was
     * flawless. CI will fail when a non zero exit code is returned.
     *
     * @param \NdB\PhpDocCheck\AnalysisResult[] $results
     */
    public function getExitCode(array $results)
    {
        foreach ($results as $analysisResult) {
            if ($analysisResult->hasErrors || $analysisResult->hasWarnings) {
                return 1;
            }
        }
        return 0;
    }

    /**
     * @param \NdB\PhpDocCheck\AnalysisResult[] $results
     */
    abstract public function result(array $results);
    abstract public function out(string $output);
    abstract public function progress(string $progress);
}

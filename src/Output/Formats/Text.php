<?php
namespace NdB\PhpDocCheck\Output\Formats;

final class Text extends Format
{
    protected function get(array $results) : string
    {
        $results = array_filter($results, array($this, 'removeEmpty'));
        $output = '';
        foreach ($results as $analysisResult) {
            $output .= $this->getFileOutput($analysisResult);
        }
        return $output;
    }

    protected function getFileOutput(\NdB\PhpDocCheck\AnalysisResult $analysisResult)
    {
        $output = '';
        $output .= "\n";
        $output .= sprintf("File: %s\n", $analysisResult->sourceFile->file->getRealPath());
        $header = array(
            'Severity',
            'Message',
            'Line'
        );
        $rows = array_map(array($this, 'formatRow'), $analysisResult->findings);
        $lines = (new \cli\Table($header, $rows))->getDisplayLines();
        foreach ($lines as $line) {
            $output .= $line. "\n";
        }
        return $output;
    }

    protected function formatRow(\NdB\PhpDocCheck\Findings\Finding $finding) : array
    {
        return array(
            $finding->getType(),
            $finding->getMessage(),
            $finding->getLine()
        );
    }

    public function result(array $results)
    {
        foreach ($this->channels as $channel) {
            $channel->out($this->get($results));
        }
    }

    public function out(string $output)
    {
        foreach ($this->channels as $channel) {
            $channel->out($output);
        }
    }

    public function progress(string $progress)
    {
        foreach ($this->channels as $channel) {
            $channel->out($progress);
        }
    }
}

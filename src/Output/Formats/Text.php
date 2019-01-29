<?php
namespace NdB\PhpDocCheck\Output\Formats;

final class Text extends Format
{
    /**
     * Outputs findings for each file analysed in a table form
     * @param \NdB\PhpDocCheck\AnalysisResult[] $results
     */
    public function get(array $results) : string
    {
        $output = '';
        foreach ($results as $analysisResult) {
            if (!empty($analysisResult->findings) && !empty($analysisResult->sourceFile)) {
                $output .= "\n";
                $output .= sprintf("File: %s\n", $analysisResult->sourceFile->file->getRealPath());
                $header = array(
                    'Severity',
                    'Message',
                    'Line'
                );
                $rows = array();
                foreach ($analysisResult->findings as $finding) {
                    $rows[] = array(
                        $finding->getType(),
                        $finding->getMessage(),
                        $finding->getLine()
                    );
                }
                $lines = (new \cli\Table($header, $rows))->getDisplayLines();
                foreach ($lines as $line) {
                    $output .= $line. "\n";
                }
            }
        }
        return $output;
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

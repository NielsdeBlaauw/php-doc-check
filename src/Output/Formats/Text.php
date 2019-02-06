<?php
namespace NdB\PhpDocCheck\Output\Formats;

final class Text extends Format
{
    protected function get(array $results) : string
    {
        $output = '';
        foreach ($results as $resultGroup) {
            if (!empty($resultGroup->getFindings())) {
                $output .= $this->getFileOutput($resultGroup);
            }
        }
        return $output;
    }

    protected function getFileOutput(\NdB\PhpDocCheck\ResultGroup $resultGroup)
    {
        $output = '';
        $output .= "\n";
        $output .= sprintf("Group: %s (score: %d)\n", $resultGroup->getName(), $resultGroup->getValue());
        $header = array(
            'Severity',
            'Message',
            'Line'
        );
        $rows = array_map(array($this, 'formatRow'), $resultGroup->getFindings());
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

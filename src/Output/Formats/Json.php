<?php
namespace NdB\PhpDocCheck\Output\Formats;

final class Json extends Format
{
    protected function get(array $results) : string
    {
        return (string) json_encode($results);
    }

    public function result(array $results)
    {
        foreach ($this->channels as $channel) {
            $channel->out($this->get($results));
        }
    }

    public function out(string $output)
    {
        return;
    }

    public function progress(string $progress)
    {
        return;
    }
}

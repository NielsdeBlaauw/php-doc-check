<?php

namespace NdB\PhpDocCheck\Output\Channels;

abstract class Channel
{
    public $stream;
    public function __construct(\SplFileObject $stream)
    {
        $this->stream = $stream;
    }
    abstract public function out(string $output);
}

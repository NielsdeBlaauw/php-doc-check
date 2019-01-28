<?php

namespace NdB\PhpDocCheck\Findings;

abstract class Finding
{
    public function __construct(string $message, int $line)
    {
        $this->message = $message;
        $this->line = $line;
    }
    
    public function getLine():int
    {
        return $this->line;
    }

    public function getMessage():string
    {
        return $this->message;
    }
}

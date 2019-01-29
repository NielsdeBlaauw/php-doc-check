<?php

namespace NdB\PhpDocCheck\Output\Channels;

final class Quiet extends Channel
{
    public function out(string $output)
    {
        return;
    }
}

<?php

namespace NdB\PhpDocCheck\Output\Channels;

final class Regular extends Channel
{
    public function out(string $output)
    {
        $this->stream->fwrite($output);
    }
}

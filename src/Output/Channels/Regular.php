<?php

namespace NdB\PhpDocCheck\Output\Channels;

final class Regular extends Channel
{
    public function progress(string $progressIndicator)
    {
        $this->stream->fwrite($progressIndicator);
    }

    public function out(string $output)
    {
        $this->stream->fwrite($output);
    }
}

<?php
namespace NdB\PhpDocCheck\Findings;

final class Error extends Finding
{
    public function getType():string
    {
        return 'Error';
    }
}

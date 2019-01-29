<?php
namespace NdB\PhpDocCheck\Findings;

final class Warning extends Finding
{
    public function getType():string
    {
        return 'Warning';
    }
}

<?php

namespace NdB\PhpDocCheck\Metrics;

interface Metric extends \JsonSerializable
{
    public function getName() : string;
    public function getValue(\PhpParser\Node $node) : int;
}

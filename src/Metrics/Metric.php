<?php

namespace NdB\PhpDocCheck\Metrics;

interface Metric
{
    public function getValue(\PhpParser\Node $node) : int;
}

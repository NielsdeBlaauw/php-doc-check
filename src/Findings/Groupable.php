<?php

namespace NdB\PhpDocCheck\Findings;

interface Groupable
{
    public function getGroupKey(string $groupingMethod) : string;
}

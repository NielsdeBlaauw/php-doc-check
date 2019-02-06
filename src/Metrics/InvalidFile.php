<?php

namespace NdB\PhpDocCheck\Metrics;

final class InvalidFile implements Metric
{
    public $value = 0;

    public function getName():string
    {
        return 'metrics.files.invalid';
    }

    public function getValue(\PhpParser\Node $node):int
    {
        return $this->value;
    }

    public function jsonSerialize() : array
    {
        return array(
            'name'=>$this->getName(),
            'value'=>$this->value
        );
    }
}

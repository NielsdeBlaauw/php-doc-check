<?php

namespace NdB\PhpDocCheck\Metrics;

final class FunctionLength implements Metric
{
    public $value = 0;

    const LINES_PER_POINT = 5;

    public function getMessage():string
    {
        return '%1$s has a length score of %2$d. It is recommended to document long functions.';
    }

    public function getName():string
    {
        return 'metrics.complexity.length';
    }

    public function getValue(\PhpParser\Node $node):int
    {
        if (!empty($node->getDocComment())) {
            return 0;
        }
        return ( $node->getEndLine() - $node->getStartLine() ) / self::LINES_PER_POINT;
    }

    public function jsonSerialize() : array
    {
        return array(
            'name'=>$this->getName(),
            'value'=>$this->value
        );
    }
}

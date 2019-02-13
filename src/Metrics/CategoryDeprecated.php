<?php

namespace NdB\PhpDocCheck\Metrics;

final class CategoryDeprecated implements Metric
{
    public $value = 0;

    public function getMessage():string
    {
        return '%1$s has a @category tag, which is deprecated. It is recommended to use the @package tag\'s ability to provide multiple levels.';
    }

    public function getName():string
    {
        return 'metrics.deprecated.category';
    }

    public function getValue(\PhpParser\Node $node):int
    {
        $docBlock = $node->getAttribute('DocBlock');
        if (!empty($docBlock) && $docBlock->hasTag('category')) {
            return 4;
        }
        return 0;
    }

    public function jsonSerialize() : array
    {
        return array(
            'name'=>$this->getName(),
            'value'=>$this->value
        );
    }
}

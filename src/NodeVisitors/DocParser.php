<?php

namespace NdB\PhpDocCheck\NodeVisitors;

class DocParser extends \PhpParser\NodeVisitorAbstract
{
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function leaveNode(\PhpParser\Node $node)
    {
        try {
            if (!empty($node->getDocComment())) {
                $factory = \phpDocumentor\Reflection\DocBlockFactory::createInstance();
                $node->setAttribute('DocBlock', $factory->create($node->getDocComment()->__toString()));
            }
        } catch (\Exception $e) {
        }
    }
}

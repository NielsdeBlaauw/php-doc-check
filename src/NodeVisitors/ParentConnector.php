<?php

namespace NdB\PhpDocCheck\NodeVisitors;

class ParentConnector extends \PhpParser\NodeVisitorAbstract
{
    private $stack;

    /**
     * @inheritdoc
     */
    public function beforeTraverse(array $nodes)
    {
        $this->stack = [];
    }
    public function enterNode(\PhpParser\Node $node)
    {
        if (!empty($this->stack)) {
            $node->setAttribute('parent', $this->stack[count($this->stack)-1]);
        }
        $this->stack[] = $node;
    }
    
    /**
     * @inheritdoc
     */
    public function leaveNode(\PhpParser\Node $node)
    {
        array_pop($this->stack);
    }
}

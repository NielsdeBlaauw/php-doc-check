<?php

namespace NdB\PhpDocCheck\Metrics;

final class CognitiveComplexity implements Metric
{
    protected $complexNodes = array(
        'Stmt_If',
        'Stmt_ElseIf',
        'Stmt_For',
        'Stmt_Foreach',
        'Stmt_While',
        'Stmt_Switch',
        'Stmt_Do',
        'Stmt_Catch',
        'Stmt_Goto',
        'Stmt_Break',
        'Stmt_Continue',
        'Expr_BinaryOp_LogicalAnd',
        'Expr_BinaryOp_LogicalOr',
        'Expr_BinaryOp_LogicalXor',
        'Expr_BinaryOp_BooleanAnd',
        'Expr_BinaryOp_BooleanOr',
        'Expr_BinaryOp_Coalesce',
        'Expr_Ternary',
    );

    public function getName():string
    {
        return 'metrics.complexity.cognitive';
    }

    public function getValue(\PhpParser\Node $node):int
    {
        return $this->calculateNodeValue($node, 0);
    }

    /**
     * Calculates cognitive complexity
     */
    protected function calculateNodeValue(\PhpParser\Node $node, $depth = 0) : int
    {
        $ccn = 0;
        if ($this->isComplexNode($node)) {
            $ccn++;
            $depth++;
        }
        foreach (get_object_vars($node) as $member) {
            foreach (is_array($member) ? $member : [$member] as $memberItem) {
                if ($memberItem instanceof \PhpParser\Node) {
                    $ccn+=$this->calculateNodeValue($memberItem, $depth);
                }
            }
        }
        // Add depth complexity when a neste item is a complex one.
        if ($ccn >= 1 && $depth >= 2) {
            $ccn += 1;
        }
        return $ccn;
    }
    
    protected function isComplexNode(\PhpParser\Node $node): bool
    {
        if (in_array($node->getType(), $this->complexNodes)) {
            return true;
        }
        return false;
    }

    public function jsonSerialize() : array
    {
        return array(
            'name'=>$this->getName()
        );
    }
}

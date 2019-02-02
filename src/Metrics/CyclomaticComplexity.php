<?php

namespace NdB\PhpDocCheck\Metrics;

final class CyclomaticComplexity implements Metric
{
    protected $complexNodes = array(
        'Stmt_If',
        'Stmt_ElseIf',
        'Stmt_For',
        'Stmt_Foreach',
        'Stmt_While',
        'Stmt_Do',
        'Stmt_Catch',
        'Expr_BinaryOp_LogicalAnd',
        'Expr_BinaryOp_LogicalOr',
        'Expr_BinaryOp_LogicalXor',
        'Expr_BinaryOp_BooleanAnd',
        'Expr_BinaryOp_BooleanOr',
        'Expr_BinaryOp_Coalesce',
        'Expr_Ternary',
    );
    
    /**
     * Recursively calculates Cyclomatic Complexity
     */
    public function getValue(\PhpParser\Node $node) : int
    {
        $ccn = 0;
        foreach (get_object_vars($node) as $member) {
            foreach (is_array($member) ? $member : [$member] as $memberItem) {
                if ($memberItem instanceof \PhpParser\Node) {
                    $ccn += $this->getValue($memberItem);
                }
            }
        }
        if (in_array($node->getType(), $this->complexNodes)) {
            $ccn++;
        }
        switch (true) {
            case $node instanceof \PhpParser\Node\Stmt\Case_:
                if ($node->cond !== null) { // exclude default
                    $ccn++;
                }
                break;
            case $node instanceof \PhpParser\Node\Expr\BinaryOp\Spaceship:
                $ccn += 2;
                break;
        }
        return $ccn;
    }
}
<?php

namespace NdB\PhpDocCheck\Metrics;

final class CyclomaticComplexity implements Metric
{
    protected $complexNodes = array(
        'PhpParser\Node\Stmt\If_',
        'PhpParser\Node\Stmt\ElseIf_',
        'PhpParser\Node\Stmt\For_',
        'PhpParser\Node\Stmt\Foreach_',
        'PhpParser\Node\Stmt\While_',
        'PhpParser\Node\Stmt\Do_',
        'PhpParser\Node\Expr\BinaryOp\LogicalAnd',
        'PhpParser\Node\Expr\BinaryOp\LogicalOr',
        'PhpParser\Node\Expr\BinaryOp\LogicalXor',
        'PhpParser\Node\Expr\BinaryOp\BooleanAnd',
        'PhpParser\Node\Expr\BinaryOp\BooleanOr',
        'PhpParser\Node\Stmt\Catch_',
        'PhpParser\Node\Expr\Ternary',
        'PhpParser\Node\Expr\BinaryOp\Coalesce',
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
        if (in_array(get_class($node), $this->complexNodes)) {
            $ccn++;
        }
        switch (true) {
            case $node instanceof \PhpParser\Node\Stmt\Case_: // include default
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

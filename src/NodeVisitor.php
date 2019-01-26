<?php

namespace NdB\PhpDocCheck;

use \PhpParser\Node;
use \PhpParser\Node\Stmt;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{
    const COMPLEX_NODES = array(
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

    public function __construct(AnalysableFile &$file)
    {
        $this->file =& $file;
    }

    /**
     * Determines if this node and it's children are of a complexity that could
     * use some clarification based on Cyclomatic Complexity.
     */
    public function leaveNode(\PhpParser\Node $node)
    {
        if (is_a($node, "\PhpParser\Node\FunctionLike")) {
            $methodCcn = $this->calculateComplexity($node) + 1; // each method by default is CCN 1 even if it's empty
            
            $name = 'Anonynous function';
            if (\property_exists($node, 'name')) {
                $name = $node->name;
            }
            if (empty($node->getDocComment())) {
                if ($methodCcn >= $this->file->arguments['complexity-error-treshold']) {
                    $this->file->hasErrors = true;
                    $this->file->findings[] = new \NdB\PhpDocCheck\Findings\Error(
                        sprintf("%s has no documentation and a complexity of %d", $name, $methodCcn),
                        $node->getStartLine()
                    );
                } elseif ($methodCcn >= $this->file->arguments['complexity-warning-treshold']) {
                    $this->file->hasWarnings = true;
                    $this->file->findings[] = new \NdB\PhpDocCheck\Findings\Warning(
                        sprintf("%s has no documentation and a complexity of %d", $name, $methodCcn),
                        $node->getStartLine()
                    );
                }
            }
        }
    }
    
    /**
     * Recursively calculates Cyclomatic Complexity
     */
    protected function calculateComplexity($node)
    {
        $ccn = 0;
        foreach (get_object_vars($node) as $member) {
            foreach (is_array($member) ? $member : [$member] as $memberItem) {
                if ($memberItem instanceof Node) {
                    $ccn += $this->calculateComplexity($memberItem);
                }
            }
        }
        if (in_array(get_class($node), self::COMPLEX_NODES)) {
            $ccn++;
        }
        switch (true) {
            case $node instanceof Stmt\Case_: // include default
                if ($node->cond !== null) { // exclude default
                    $ccn++;
                }
                break;
            case $node instanceof Node\Expr\BinaryOp\Spaceship:
                $ccn += 2;
                break;
        }
        return $ccn;
    }
}

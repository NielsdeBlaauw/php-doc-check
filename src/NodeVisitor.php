<?php

namespace NdB\PhpDocCheck;

use \PhpParser\Node;
use \PhpParser\Node\Stmt;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{
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
                    $this->file->has_errors = true;
                    $this->file->findings[] = new \NdB\PhpDocCheck\Findings\Error(
                        sprintf("%s has no documentation and a complexity of %d", $name, $methodCcn),
                        $node->getStartLine()
                    );
                } elseif ($methodCcn >= $this->file->arguments['complexity-warning-treshold']) {
                    $this->file->has_warnings = true;
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
        foreach (get_object_vars($node) as $name => $member) {
            foreach (is_array($member) ? $member : [$member] as $memberItem) {
                if ($memberItem instanceof Node) {
                    $ccn += $this->calculateComplexity($memberItem);
                }
            }
        }
        switch (true) {
            case $node instanceof Stmt\If_:
            case $node instanceof Stmt\ElseIf_:
            case $node instanceof Stmt\For_:
            case $node instanceof Stmt\Foreach_:
            case $node instanceof Stmt\While_:
            case $node instanceof Stmt\Do_:
            case $node instanceof Node\Expr\BinaryOp\LogicalAnd:
            case $node instanceof Node\Expr\BinaryOp\LogicalOr:
            case $node instanceof Node\Expr\BinaryOp\LogicalXor:
            case $node instanceof Node\Expr\BinaryOp\BooleanAnd:
            case $node instanceof Node\Expr\BinaryOp\BooleanOr:
            case $node instanceof Stmt\Catch_:
            case $node instanceof Node\Expr\Ternary:
            case $node instanceof Node\Expr\BinaryOp\Coalesce:
                $ccn++;
                break;
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

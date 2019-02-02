<?php

namespace NdB\PhpDocCheck;

use \PhpParser\Node;
use \PhpParser\Node\Stmt;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{
    public $analysisResult;
    protected $arguments;
    protected $metric;

    public function __construct(
        AnalysisResult &$analysisResult,
        \GetOpt\GetOpt $arguments,
        \NdB\PhpDocCheck\Metrics\Metric $metric
    ) {
        $this->analysisResult =& $analysisResult;
        $this->arguments      = $arguments;
        $this->metric         = $metric;
    }

    /**
     * Determines if this node and it's children are of a complexity that could
     * use some clarification based on Cyclomatic Complexity.
     */
    public function leaveNode(\PhpParser\Node $node)
    {
        if (is_a($node, "\PhpParser\Node\FunctionLike")) {
            $metricValue = $this->metric->getValue($node) + 1; // each method by default is CCN 1 even if it's empty
            
            $name = 'Anonynous function';
            if (\property_exists($node, 'name')) {
                $name = $node->name;
            }
            if (empty($node->getDocComment())) {
                if ($metricValue >= $this->arguments->getOption('complexity-error-treshold')) {
                    $this->analysisResult->addFinding(
                        new \NdB\PhpDocCheck\Findings\Error(
                            sprintf("%s has no documentation and a complexity of %d", $name, $metricValue),
                            $node->getStartLine()
                        )
                    );
                } elseif ($metricValue >= $this->arguments->getOption('complexity-warning-treshold')) {
                    $this->analysisResult->addFinding(
                        new \NdB\PhpDocCheck\Findings\Warning(
                            sprintf("%s has no documentation and a complexity of %d", $name, $metricValue),
                            $node->getStartLine()
                        )
                    );
                }
            }
        }
    }
}

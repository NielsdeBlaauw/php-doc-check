<?php

namespace NdB\PhpDocCheck;

use \PhpParser\Node;
use \PhpParser\Node\Stmt;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{
    public $analysisResult;
    protected $arguments;
    protected $sourceFile;
    protected $metric;
    protected $groupManager;

    public function __construct(
        AnalysisResult &$analysisResult,
        AnalysableFile $file,
        \NdB\PhpDocCheck\Metrics\Metric $metric,
        GroupManager $groupManager
    ) {
        $this->analysisResult =& $analysisResult;
        $this->sourceFile     = $file;
        $this->arguments      = $file->arguments;
        $this->metric         = $metric;
        $this->groupManager   = $groupManager;
    }

    /**
     * Determines if this node and it's children are of a complexity that could
     * use some clarification based on Cyclomatic Complexity.
     */
    public function leaveNode(\PhpParser\Node $node)
    {
        if (is_a($node, "\PhpParser\Node\FunctionLike")) {
            $metricValue = $this->metric->getValue($node);
            
            $name = 'Anonymous function';
            if (\property_exists($node, 'name')) {
                $name = $node->name;
            }
            if (empty($node->getDocComment())) {
                if ($metricValue >= $this->arguments->getOption('complexity-error-threshold')) {
                    $finding = new \NdB\PhpDocCheck\Findings\Error(
                        sprintf("%s has no documentation and a complexity of %d", $name, $metricValue),
                        $node,
                        $this->sourceFile,
                        $this->metric
                    );
                    $this->analysisResult->addProgress($finding);
                    $this->groupManager->addFinding($finding);
                } elseif ($metricValue >= $this->arguments->getOption('complexity-warning-threshold')) {
                    $finding = new \NdB\PhpDocCheck\Findings\Warning(
                        sprintf("%s has no documentation and a complexity of %d", $name, $metricValue),
                        $node,
                        $this->sourceFile,
                        $this->metric
                    );
                    $this->analysisResult->addProgress($finding);
                    $this->groupManager->addFinding($finding);
                }
            }
        }
    }
}

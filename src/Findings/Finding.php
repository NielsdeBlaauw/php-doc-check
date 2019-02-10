<?php

namespace NdB\PhpDocCheck\Findings;

abstract class Finding implements \JsonSerializable, Groupable, \NdB\PhpDocCheck\Sortable
{
    public $message;
    public $node;
    public $sourceFile;
    public $metric;
    public $value;

    public function __construct(
        string $message,
        \PhpParser\Node $node,
        \NdB\PhpDocCheck\AnalysableFile $sourceFile,
        \NdB\PhpDocCheck\Metrics\Metric $metric,
        int $value
    ) {
        $this->message    = $message;
        $this->node       = $node;
        $this->sourceFile = $sourceFile;
        $this->metric     = $metric;
        $this->value      = $value;
    }
    
    /**
     * Based on the group key, new groups are made by the groupManager.
     */
    public function getGroupKey(string $groupingMethod) : string
    {
        switch ($groupingMethod) {
            case 'none':
                $groupKey = 'none';
                break;
            case 'metric':
                $groupKey = $this->metric->getName();
                break;
            case 'severity':
                $groupKey = $this->getType();
                break;
            case 'fileline':
                $groupKey = $this->sourceFile->file->getRealPath().":".$this->getLine();
                break;
            case 'file':
            default:
                $groupKey = $this->sourceFile->file->getRealPath();
        }
        return $groupKey;
    }

    public function getLine():int
    {
        return $this->node->getStartLine();
    }

    public function getMessage():string
    {
        return $this->message;
    }

    abstract public function getType():string;

    public function getSortValue($sortMethod): string
    {
        if ($sortMethod === 'value') {
            return (string) $this->value;
        }
        return (string) $this->getLine();
    }

    public function jsonSerialize() : array
    {
        return array(
            'message'=> $this->getMessage(),
            'type'=> $this->getType(),
            'value'=> $this->value,
            'metric'=> $this->metric,
            'sourceFile'=> $this->sourceFile,
            'node'=> array(
                'name'         => $this->node->getAttribute('FQSEN'),
                'getStartLine' => $this->getLine()
            )
        );
    }
}

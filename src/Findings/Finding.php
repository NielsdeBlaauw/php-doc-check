<?php

namespace NdB\PhpDocCheck\Findings;

abstract class Finding implements \JsonSerializable
{
    public $message;
    public $node;
    public $sourceFile;
    public $metric;

    public function __construct(
        string $message,
        \PhpParser\Node $node,
        \NdB\PhpDocCheck\AnalysableFile $sourceFile,
        \NdB\PhpDocCheck\Metrics\Metric $metric
    ) {
        $this->message    = $message;
        $this->node       = $node;
        $this->sourceFile = $sourceFile;
        $this->metric     = $metric;
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

    public function jsonSerialize() : array
    {
        return array(
            'message'=> $this->getMessage(),
            'type'=> $this->getType(),
            'metric'=> $this->metric,
            'sourceFile'=> $this->sourceFile,
            'node'=> array(
                'name'         => isset($this->node->name)?$this->node->name:false,
                'getStartLine' => $this->getLine()
            )
        );
    }
}

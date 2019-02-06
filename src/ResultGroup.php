<?php

namespace NdB\PhpDocCheck;

class ResultGroup implements \JsonSerializable
{
    public $name;
    protected $findings = array();

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName():string
    {
        return $this->name;
    }

    public function addFinding(Findings\Finding $finding)
    {
        $this->findings[] = $finding;
    }

    public function getFindings(): array
    {
        return $this->findings;
    }

    public function jsonSerialize() : array
    {
        return array(
            'groupName'=>$this->getName(),
            'findings'=>$this->getFindings(),
        );
    }
}

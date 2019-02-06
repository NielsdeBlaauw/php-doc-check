<?php

namespace NdB\PhpDocCheck;

class ResultGroup implements \JsonSerializable, Sortable
{
    public $name;
    public $sortingMethod;
    protected $findings = array();

    public function __construct(string $name, string $sortingMethod)
    {
        $this->name = $name;
        $this->sortingMethod = $sortingMethod;
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
        uasort($this->findings, function ($prev, $next) {
            if ($prev->getSortValue($this->sortingMethod) == $next->getSortValue($this->sortingMethod)) {
                return 0;
            }
            return ($prev->getSortValue($this->sortingMethod) < $next->getSortValue($this->sortingMethod)) ? -1 : 1;
        });
        if ($this->sortingMethod === 'value') {
            $this->findings = array_reverse($this->findings, true);
        }
        return $this->findings;
    }

    public function jsonSerialize() : array
    {
        return array(
            'groupName'=>$this->getName(),
            'findings'=>$this->getFindings(),
            'value'=>$this->getValue()
        );
    }

    public function getValue() : int
    {
        return array_reduce($this->getFindings(), function (int $carry, Findings\Finding $finding) {
            return $carry + $finding->value;
        }, 0);
    }

    public function getSortValue($sortMethod): string
    {
        if ($sortMethod === 'value') {
            return (string) $this->getValue();
        }
        return $this->getName();
    }
}

<?php

namespace NdB\PhpDocCheck;

class GroupManager implements GroupContainer
{
    protected $groupingMethod = 'file';
    protected $groups = array();
    public function __construct($groupingMethod)
    {
        $this->groupingMethod = $groupingMethod;
    }

    public function addFinding(Findings\Groupable $finding)
    {
        if (!array_key_exists($finding->getGroupKey($this->groupingMethod), $this->groups)) {
            $this->groups[$finding->getGroupKey($this->groupingMethod)] = new ResultGroup(
                $finding->getGroupKey($this->groupingMethod)
            );
        }
        $this->groups[$finding->getGroupKey($this->groupingMethod)]->addFinding($finding);
    }

    public function getGroups(): array
    {
        return $this->groups;
    }
}

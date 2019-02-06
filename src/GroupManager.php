<?php

namespace NdB\PhpDocCheck;

class GroupManager implements GroupContainer
{
    protected $groupingMethod = 'file';
    protected $sortingMethod = 'natural';
    protected $groups = array();
    public function __construct(string $groupingMethod, string $sortingMethod)
    {
        $this->groupingMethod = $groupingMethod;
        $this->sortingMethod  = $sortingMethod;
    }

    public function addFinding(Findings\Groupable $finding)
    {
        if (!array_key_exists($finding->getGroupKey($this->groupingMethod), $this->groups)) {
            $this->groups[$finding->getGroupKey($this->groupingMethod)] = new ResultGroup(
                $finding->getGroupKey($this->groupingMethod),
                $this->sortingMethod
            );
        }
        $this->groups[$finding->getGroupKey($this->groupingMethod)]->addFinding($finding);
    }

    public function getGroups(): array
    {
        uasort($this->groups, function ($prev, $next) {
            if ($prev->getSortValue($this->sortingMethod) == $next->getSortValue($this->sortingMethod)) {
                return 0;
            }
            return ($prev->getSortValue($this->sortingMethod) < $next->getSortValue($this->sortingMethod)) ? -1 : 1;
        });
        if ($this->sortingMethod === 'value') {
            $this->groups = array_reverse($this->groups, true);
        }
        return $this->groups;
    }
}

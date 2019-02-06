<?php

namespace NdB\PhpDocCheck;

interface GroupContainer
{
    public function addFinding(Findings\Groupable $finding);
    public function getGroups(): array;
}

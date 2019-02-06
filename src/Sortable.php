<?php

namespace NdB\PhpDocCheck;

interface Sortable
{
    public function getSortValue($sortMethod): string;
}

<?php

namespace NdB\PhpDocCheck;

/**
 * @SuppressWarnings(PHPMD)
 */
class InvalidFileNode implements \PhpParser\Node
{
    public function getType() : string
    {
        return 'InvalidFile';
    }

    public function getSubNodeNames() : array
    {
        return array();
    }

    public function getLine() : int
    {
        return $this->getStartLine();
    }

    public function getStartLine() : int
    {
        return -1;
    }

    public function getEndLine() : int
    {
        return -1;
    }

    public function getStartTokenPos() : int
    {
        return -1;
    }

    public function getEndTokenPos() : int
    {
        return -1;
    }

    public function getStartFilePos() : int
    {
        return -1;
    }

    public function getEndFilePos() : int
    {
        return -1;
    }

    public function getComments() : array
    {
        return array();
    }

    public function getDocComment()
    {
        return null;
    }

    public function setDocComment(\PhpParser\Comment\Doc $docComment)
    {
    }

    public function setAttribute(string $key, $value)
    {
    }

    public function hasAttribute(string $key) : bool
    {
        return false;
    }

    public function getAttribute(string $key, $default = null)
    {
        return $default;
    }

    public function getAttributes() : array
    {
        return array();
    }

    public function setAttributes(array $attributes)
    {
    }
}

<?php

namespace NdB\PhpDocCheck;

final class FileFinderTest extends \PHPUnit\Framework\TestCase
{
    public function testCanCreateFileListing()
    {
        $arguments = $this->createMock(\GetOpt\GetOpt::class);
        $arguments->method('getOperand')->willReturn(array('./'));
        $arguments->method('getOption')->will($this->onConsecutiveCalls(
            array('php'),
            array('vendor')
        ));
        $fileFinder = new FileFinder();
        $this->assertInstanceOf(\Iterator::class, $fileFinder->getFiles($arguments));
    }
}

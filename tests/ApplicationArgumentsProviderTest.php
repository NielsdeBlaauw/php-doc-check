<?php

namespace NdB\PhpDocCheck;

final class ApplicationArgumentsProviderTest extends \PHPUnit\Framework\TestCase
{
    public function testCanGetArbuments()
    {
        $arguments = new ApplicationArgumentsProvider();
        $this->assertInstanceOf(\GetOpt\GetOpt::class, $arguments->getArguments(array(
            './'
        )));
    }
}

<?php

namespace NdB\PhpDocCheck\Output\Formats;

final class JsonTest extends \PHPUnit\Framework\TestCase
{
    public function testCanSkipEmptyResults()
    {
        $channel = $this->createMock(\NdB\PhpDocCheck\Output\Channels\Channel::class);
        $channel->expects($this->once())
            ->method('out')
            ->with('[[]]');
        $formatter = new Json(array($channel));
        $results = $this->createMock(\NdB\PhpDocCheck\ResultGroup::class);
        $formatter->result(array($results));
    }
}

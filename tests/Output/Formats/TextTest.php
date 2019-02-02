<?php

namespace NdB\PhpDocCheck\Output\Formats;

final class TextTest extends \PHPUnit\Framework\TestCase
{
    public function testCanSkipEmptyResults()
    {
        $channel = $this->createMock(\NdB\PhpDocCheck\Output\Channels\Channel::class);
        $channel->expects($this->once())
            ->method('out')
            ->with('');
        $formatter = new Text(array($channel));
        $results = $this->createMock(\NdB\PhpDocCheck\AnalysisResult::class);
        $formatter->result(array($results));
    }

    public function testCanOutputSimpleResults()
    {
        $channel = $this->createMock(\NdB\PhpDocCheck\Output\Channels\Channel::class);
        $channel->expects($this->once())
            ->method('out')
            ->with($this->stringContains('Basic warning'));
        $formatter = new Text(array($channel));
        $results = $this->createMock(\NdB\PhpDocCheck\AnalysisResult::class);
        $results->findings = array(
            new \NdB\PhpDocCheck\Findings\Warning("Basic warning", 1)
        );
        $results->sourceFile = $this->createMock(\NdB\PhpDocCheck\AnalysableFile::class);
        $results->sourceFile->file = $this->createMock(\SplFileInfo::class);
        $results->sourceFile->file->method('getRealPath')->willReturn('/tmp/test');
        $formatter->result(array($results));
    }
}

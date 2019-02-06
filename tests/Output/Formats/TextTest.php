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
        $results = $this->createMock(\NdB\PhpDocCheck\ResultGroup::class);
        $formatter->result(array($results));
    }

    public function testCanOutputSimpleResults()
    {
        $channel = $this->createMock(\NdB\PhpDocCheck\Output\Channels\Channel::class);
        $channel->expects($this->once())
            ->method('out')
            ->with($this->stringContains('Basic warning'));
        $formatter = new Text(array($channel));
        $results = $this->createMock(\NdB\PhpDocCheck\ResultGroup::class);
        $metric = $this->createMock('\NdB\PhpDocCheck\Metrics\Metric');
        $analysableFile = $this->createMock('\NdB\PhpDocCheck\AnalysableFile');
        $node = $this->createMock('\PhpParser\Node');
        $results->method('getFindings')->willReturn(array(
            new \NdB\PhpDocCheck\Findings\Warning("Basic warning", $node, $analysableFile, $metric, 0)
        ));
        $results->sourceFile = $this->createMock(\NdB\PhpDocCheck\AnalysableFile::class);
        $results->sourceFile->file = $this->createMock(\SplFileInfo::class);
        $results->sourceFile->file->method('getRealPath')->willReturn('/tmp/test');
        $formatter->result(array($results));
    }

    public function testErrorCodeIndicatesFailures()
    {
        $channel = $this->createMock(\NdB\PhpDocCheck\Output\Channels\Channel::class);
        $formatter = new Text(array($channel));
        $results = $this->createMock(\NdB\PhpDocCheck\AnalysisResult::class);
        $results->hasErrors = true;
        $this->assertEquals(1, $formatter->getExitCode(array($results)));
    }
    
    public function testErrorCodeIndicatesNoProblem()
    {
        $channel = $this->createMock(\NdB\PhpDocCheck\Output\Channels\Channel::class);
        $formatter = new Text(array($channel));
        $results = $this->createMock(\NdB\PhpDocCheck\AnalysisResult::class);
        $results->hasErrors = false;
        $results->hasWarnings = false;
        $this->assertEquals(0, $formatter->getExitCode(array($results)));
    }
}

<?php

namespace NdB\PhpDocCheck;

final class NodeVisitorTest extends \PHPUnit\Framework\TestCase
{
    public function testCanAnalyseNodesForWarningFindings()
    {
        $analysisResult = $this->createMock(AnalysisResult::class);
        $analysisResult->expects($this->once())
            ->method('addProgress')
            ->with($this->isInstanceOf(\NdB\PhpDocCheck\Findings\Warning::class));
        $arguments = $this->createMock(\GetOpt\GetOpt::class);
        $arguments->method('getOption')->will($this->onConsecutiveCalls(6, 4));
        $analysableFile = $this->createMock('\NdB\PhpDocCheck\AnalysableFile');
        $analysableFile->arguments = $arguments;
        $metric = $this->createMock(\NdB\PhpDocCheck\Metrics\Metric::class);
        $metric->method('getValue')->willReturn(4);
        $groupManager = new \NdB\PhpDocCheck\GroupManager('none', 'natural');
        $nodeVisitor = new NodeVisitor($analysisResult, $analysableFile, $metric, $groupManager);
        $node = $this->createMock(\PhpParser\Node\Stmt\Function_::class);
        $nodeVisitor->leaveNode($node);
    }

    public function testCanAnalyseNodesForErrorFindings()
    {
        $analysisResult = $this->createMock(AnalysisResult::class);
        $analysisResult->expects($this->once())
            ->method('addProgress')
            ->with($this->isInstanceOf(\NdB\PhpDocCheck\Findings\Error::class));
        $arguments = $this->createMock(\GetOpt\GetOpt::class);
        $arguments->method('getOption')->will($this->onConsecutiveCalls(6, 4));
        $analysableFile = $this->createMock('\NdB\PhpDocCheck\AnalysableFile');
        $analysableFile->arguments = $arguments;
        $metric = $this->createMock(\NdB\PhpDocCheck\Metrics\Metric::class);
        $metric->method('getValue')->willReturn(9);
        $groupManager = $this->createMock(\NdB\PhpDocCheck\GroupManager::class);
        $nodeVisitor = new NodeVisitor($analysisResult, $analysableFile, $metric, $groupManager);
        $node = $this->createMock(\PhpParser\Node\Stmt\Function_::class);
        $nodeVisitor->leaveNode($node);
    }
}

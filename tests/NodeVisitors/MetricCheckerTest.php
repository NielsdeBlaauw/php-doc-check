<?php

namespace NdB\PhpDocCheck\NodeVisitors;

final class MetricCheckerTest extends \PHPUnit\Framework\TestCase
{
    public function testCanAnalyseNodesForWarningFindings()
    {
        $analysisResult = $this->createMock(\NdB\PhpDocCheck\AnalysisResult::class);
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
        $metricChecker = new MetricChecker($analysisResult, $analysableFile, $metric, $groupManager);
        $node = $this->createMock(\PhpParser\Node\Stmt\Function_::class);
        $metricChecker->leaveNode($node);
    }

    public function testCanAnalyseNodesForErrorFindings()
    {
        $analysisResult = $this->createMock(\NdB\PhpDocCheck\AnalysisResult::class);
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
        $metricChecker = new MetricChecker($analysisResult, $analysableFile, $metric, $groupManager);
        $node = $this->createMock(\PhpParser\Node\Stmt\Function_::class);
        $metricChecker->leaveNode($node);
    }
}

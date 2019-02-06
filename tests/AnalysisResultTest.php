<?php

namespace NdB\PhpDocCheck;

final class AnalysisResultTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        $this->metric = $this->createMock('\NdB\PhpDocCheck\Metrics\Metric');
        $this->analysableFile = $this->createMock('\NdB\PhpDocCheck\AnalysableFile');
        $this->node = $this->createMock('\PhpParser\Node');
    }

    public function testAlwaysStartsClean()
    {
        $file = $this->createMock(AnalysableFile::class);
        $analysisResult = new AnalysisResult($file);
        $this->assertEquals('.', $analysisResult->getProgressIndicator());
        return $analysisResult;
    }
    
    /**
     * @depends testAlwaysStartsClean
     */
    public function testChangesStateWhenAnWarningIsAdded($analysisResult)
    {
        $finding = new \NdB\PhpDocCheck\Findings\Warning(
            "Basic warning",
            $this->node,
            $this->analysableFile,
            $this->metric,
            0
        );
        $analysisResult->addProgress($finding);
        $this->assertEquals('W', $analysisResult->getProgressIndicator());
        return $analysisResult;
    }

    /**
     * @depends testChangesStateWhenAnWarningIsAdded
     */
    public function testChangesStateWhenAnErrorIsAdded($analysisResult)
    {
        $finding = new \NdB\PhpDocCheck\Findings\Error(
            "Basic error",
            $this->node,
            $this->analysableFile,
            $this->metric,
            1
        );
        $analysisResult->addProgress($finding);
        $this->assertEquals('E', $analysisResult->getProgressIndicator());
        return $analysisResult;
    }
}

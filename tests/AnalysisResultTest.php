<?php

namespace NdB\PhpDocCheck;

final class AnalysisResultTest extends \PHPUnit\Framework\TestCase
{
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
        $finding = new \NdB\PhpDocCheck\Findings\Warning("Basic warning", 1);
        $analysisResult->addFinding($finding);
        $this->assertEquals('W', $analysisResult->getProgressIndicator());
        return $analysisResult;
    }

    /**
     * @depends testChangesStateWhenAnWarningIsAdded
     */
    public function testChangesStateWhenAnErrorIsAdded($analysisResult)
    {
        $finding = new \NdB\PhpDocCheck\Findings\Error("Basic error", 1);
        $analysisResult->addFinding($finding);
        $this->assertEquals('E', $analysisResult->getProgressIndicator());
        return $analysisResult;
    }
}

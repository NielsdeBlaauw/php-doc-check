<?php

namespace NdB\PhpDocCheck\Metrics;

final class CyclomaticComplexityTest extends \PHPUnit\Framework\TestCase
{
    public function testMetricWithoutComplexityIsZero()
    {
        $node = $this->createMock(\PhpParser\Node\Expr\Assign::class);
        $node->method('getType')->willReturn('Expr_Assign');
        $metric = new CyclomaticComplexity();
        $this->assertEquals(0, $metric->getValue($node));
    }

    public function testCanCalculateSimpleMetric()
    {
        $node = $this->createMock(\PhpParser\Node\Stmt\If_::class);
        $node->method('getType')->willReturn('Stmt_If');
        $metric = new CyclomaticComplexity();
        $this->assertEquals(1, $metric->getValue($node));
    }

    public function testCanCalculateFunctionWithChildren()
    {
        $nodeIf1 = $this->createMock(\PhpParser\Node\Stmt\If_::class);
        $nodeIf1->method('getType')->willReturn('Stmt_If');
        $nodeIf2 = $this->createMock(\PhpParser\Node\Stmt\If_::class);
        $nodeIf2->method('getType')->willReturn('Stmt_If');

        $node = $this->createMock(\PhpParser\Node\Stmt\Function_::class);
        $node->stmts = array(
            $nodeIf1,
            $nodeIf2,
        );
        $metric = new CyclomaticComplexity();
        $this->assertEquals(2, $metric->getValue($node));
    }

    public function testCanCalculateCaseComplexity()
    {
        $node = $this->createMock(\PhpParser\Node\Stmt\Case_::class);
        $node->cond = true;
        $metric = new CyclomaticComplexity();
        $this->assertEquals(1, $metric->getValue($node));
    }

    public function testCanCalculateSpaceshipComplexity()
    {
        $node = $this->createMock(\PhpParser\Node\Expr\BinaryOp\Spaceship::class);
        $metric = new CyclomaticComplexity();
        $this->assertEquals(2, $metric->getValue($node));
    }
}

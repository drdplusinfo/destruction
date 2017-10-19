<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Destruction;

use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use Granam\Tests\Tools\TestWithMockery;
use Mockery\MockInterface;

class RealTimeOfDestructionTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $realTimeOfDestruction = new RealTimeOfDestruction(
            $this->createBaseTimeOfDestruction(123),
            $this->createRollOnDestruction(456),
            new TimeTable()
        );
        self::assertSame(123 - 456, $realTimeOfDestruction->getValue());
        self::assertInstanceOf(
            TimeBonus::class,
            $realTimeOfDestruction,
            RealTimeOfDestruction::class . ' should be usable as a ' . TimeBonus::class
        );
    }

    /**
     * @param int $value
     * @return BaseTimeOfDestruction|MockInterface
     */
    private function createBaseTimeOfDestruction(int $value): BaseTimeOfDestruction
    {
        $baseTimeOfDestruction = $this->mockery(BaseTimeOfDestruction::class);
        $baseTimeOfDestruction->shouldReceive('getValue')
            ->andReturn($value);

        return $baseTimeOfDestruction;
    }

    /**
     * @param int $value
     * @return RollOnDestruction|MockInterface
     */
    private function createRollOnDestruction(int $value): RollOnDestruction
    {
        $rollOnDestruction = $this->mockery(RollOnDestruction::class);
        $rollOnDestruction->shouldReceive('getValue')
            ->andReturn($value);

        return $rollOnDestruction;
    }
}
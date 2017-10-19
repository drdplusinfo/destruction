<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Destruction;

use DrdPlus\Destruction\MaterialResistance;
use DrdPlus\Destruction\PowerOfDestruction;
use DrdPlus\Destruction\RollOnDestruction;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;
use DrdPlus\Tests\RollsOn\QualityAndSuccess\SimpleRollOnSuccessTest;
use Mockery\MockInterface;

class RollOnDestructionTest extends SimpleRollOnSuccessTest
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $successfulRollOn = new RollOnDestruction(
            $this->createPowerOfDestruction($bonus = 123),
            new MaterialResistance($baseDifficulty = 456),
            $rollOnQuality = new RollOnQuality($preconditions = 456, $this->createRoll($rollValue = 789))
        );
        $difficulty = $baseDifficulty - $bonus;
        self::assertSame($difficulty, $successfulRollOn->getDifficulty());
        self::assertSame($rollOnQuality, $successfulRollOn->getRollOnQuality());
        self::assertGreaterThan($difficulty, $preconditions + $rollValue);
        self::assertSame('not_damaged', $successfulRollOn->getResult());

        $failedRollOn = new RollOnDestruction(
            $this->createPowerOfDestruction($bonus = 1),
            new MaterialResistance($baseDifficulty = 581),
            $rollOnQuality = new RollOnQuality($preconditions = 456, $this->createRoll($rollValue = 123))
        );
        self::assertSame($baseDifficulty - $bonus, $failedRollOn->getDifficulty());
        self::assertSame($rollOnQuality, $failedRollOn->getRollOnQuality());
        self::assertLessThan($baseDifficulty - $bonus, $preconditions + $rollValue);
        self::assertSame('damaged', $failedRollOn->getResult());
    }

    /**
     * @param int $value
     * @return PowerOfDestruction|MockInterface
     */
    private function createPowerOfDestruction(int $value): PowerOfDestruction
    {
        $powerOfDestruction = $this->mockery(PowerOfDestruction::class);
        $powerOfDestruction->shouldReceive('getValue')
            ->andReturn($value);

        return $powerOfDestruction;
    }

}
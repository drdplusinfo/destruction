<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Destruction;

use DrdPlus\Destruction\BaseTimeOfDestruction;
use DrdPlus\Properties\Body\Size;
use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tables\Measurements\Volume\VolumeBonus;
use DrdPlus\Tables\Tables;
use Granam\Integer\IntegerObject;
use Granam\Tests\Tools\TestWithMockery;

class BaseTimeOfDestructionTest extends TestWithMockery
{
    /**
     * @test
     */
    public function It_is_time_bonus()
    {
        $baseTimeOfDestruction = new BaseTimeOfDestruction(new IntegerObject(123), new TimeTable());
        self::assertInstanceOf(TimeBonus::class, $baseTimeOfDestruction);
    }

    /**
     * @test
     * @dataProvider provideSizeAndExpectedTimeBOnus
     * @param int $size
     * @param int $expectedTimeBonus
     */
    public function I_can_create_it_for_a_size_body_size_and_volume(int $size, int $expectedTimeBonus)
    {
        $directly = new BaseTimeOfDestruction(new IntegerObject($size), Tables::getIt()->getTimeTable());
        self::assertSame($expectedTimeBonus, $directly->getValue());

        $forItemSize = BaseTimeOfDestruction::createForItemSize(new IntegerObject($size), Tables::getIt()->getTimeTable());
        self::assertSame($expectedTimeBonus, $forItemSize->getValue());
        self::assertEquals($directly, $forItemSize);

        $forBodySize = BaseTimeOfDestruction::createForBodySize(Size::getIt($size), Tables::getIt()->getTimeTable());
        self::assertSame($expectedTimeBonus, $forBodySize->getValue());
        self::assertEquals($directly, $forBodySize);

        $forVolume = BaseTimeOfDestruction::createForItemOfVolume(
            new VolumeBonus($size, Tables::getIt()->getVolumeTable()),
            Tables::getIt()->getTimeTable()
        );
        self::assertSame($expectedTimeBonus, $forVolume->getValue());
        self::assertEquals($directly, $forVolume);
    }

    public function provideSizeAndExpectedTimeBOnus()
    {
        return [
            [123, 174],
            [-51, 0],
            [999, 1050],
        ];
    }
}
<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Destruction;

use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Environment\MaterialCode;
use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Destruction\Destruction;
use DrdPlus\Destruction\PowerOfDestruction;
use DrdPlus\Destruction\MaterialResistance;
use DrdPlus\Destruction\RollOnDestruction;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;
use DrdPlus\Tables\Armaments\Armourer;
use DrdPlus\Tables\Environments\MaterialResistancesTable;
use DrdPlus\Tables\Tables;
use Granam\Tests\Tools\TestWithMockery;
use Mockery\MockInterface;

class DestructionTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_destruction_power()
    {
        $hand = MeleeWeaponCode::getIt(MeleeWeaponCode::HAND);
        $strength = Strength::getIt(123);
        $itemHoldingCode = ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND);
        $armourer = $this->mockery(Armourer::class);
        $armourer->shouldReceive('getPowerOfDestruction')
            ->once()
            ->with($hand, $strength, $itemHoldingCode)
            ->andReturn(456);
        /** @var Armourer $armourer */
        $destruction = new Destruction($this->createTablesWithArmourer($armourer));
        $powerOfDestruction = $destruction->getPowerOfDestruction($hand, $strength, $itemHoldingCode);
        self::assertInstanceOf(PowerOfDestruction::class, $powerOfDestruction);
        self::assertSame(456, $powerOfDestruction->getValue());
    }

    /**
     * @param Armourer $armourer
     * @return Tables|MockInterface
     */
    private function createTablesWithArmourer(Armourer $armourer): Tables
    {
        $tables = $this->mockery(Tables::class);
        $tables->shouldReceive('getArmourer')
            ->atLeast()->once()
            ->andReturn($armourer);

        return $tables;
    }

    /**
     * @test
     */
    public function I_can_get_material_resistance()
    {
        $bronze = MaterialCode::getIt(MaterialCode::BRONZE);
        $materialResistancesTable = $this->mockery(MaterialResistancesTable::class);
        $materialResistancesTable->shouldReceive('getResistanceOfMaterial')
            ->once()
            ->with($bronze)
            ->andReturn(777);
        /** @var MaterialResistancesTable $materialResistancesTable */
        $destruction = new Destruction($this->createTablesWithMaterialResistancesTable($materialResistancesTable));
        $materialResistance = $destruction->getMaterialResistance($bronze);
        self::assertInstanceOf(MaterialResistance::class, $materialResistance);
        self::assertSame(777, $materialResistance->getValue());
    }

    /**
     * @param MaterialResistancesTable $materialResistancesTable
     * @return Tables|MockInterface
     */
    private function createTablesWithMaterialResistancesTable(MaterialResistancesTable $materialResistancesTable): Tables
    {
        $tables = $this->mockery(Tables::class);
        $tables->shouldReceive('getMaterialResistancesTable')
            ->atLeast()->once()
            ->andReturn($materialResistancesTable);

        return $tables;
    }

    /**
     * @test
     */
    public function I_can_get_roll_on_destruction()
    {
        $destruction = new Destruction(Tables::getIt());
        $rollOnDestruction = $destruction->getRollOnDestruction(
            $powerOfDestruction = $this->createPowerOfDestruction(123),
            $materialResistance = $this->createMaterialResistance(234),
            $rollOnQuality = $this->createRollOnQuality()
        );

        self::assertEquals(
            new RollOnDestruction($powerOfDestruction, $materialResistance, $rollOnQuality),
            $rollOnDestruction
        );
    }

    /**
     * @param int $value
     * @return MockInterface|PowerOfDestruction
     */
    private function createPowerOfDestruction(int $value): PowerOfDestruction
    {
        $powerOfDestruction = $this->mockery(PowerOfDestruction::class);
        $powerOfDestruction->shouldReceive('getValue')
            ->atLeast()->once()
            ->andReturn($value);

        return $powerOfDestruction;
    }

    /**
     * @param int $value
     * @return MockInterface|MaterialResistance
     */
    private function createMaterialResistance(int $value): MaterialResistance
    {
        $materialResistance = $this->mockery(MaterialResistance::class);
        $materialResistance->shouldReceive('getValue')
            ->atLeast()->once()
            ->andReturn($value);

        return $materialResistance;
    }

    /**
     * @return MockInterface|RollOnQuality
     */
    private function createRollOnQuality(): RollOnQuality
    {
        return $this->mockery(RollOnQuality::class);
    }
}
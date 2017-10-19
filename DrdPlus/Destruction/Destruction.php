<?php
namespace DrdPlus\Destruction;

use DrdPlus\Codes\Armaments\MeleeWeaponlikeCode;
use DrdPlus\Codes\Environment\MaterialCode;
use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;
use DrdPlus\Tables\Tables;
use Granam\Strict\Object\StrictObject;

/**
 * @link https://pph.drdplus.info/#niceni
 */
class Destruction extends StrictObject
{

    /** @var Tables */
    private $tables;

    public function __construct(Tables $tables)
    {
        $this->tables = $tables;
    }

    /**
     * There is NO malus for missing strength (we are not fighting, just smashing)
     *
     * @link https://pph.drdplus.info/#vypocet_sily_niceni
     * @param MeleeWeaponlikeCode $meleeWeaponlikeCode
     * @param Strength $strength
     * @param ItemHoldingCode $itemHoldingCode
     * @return PowerOfDestruction it is a bonus of destruction strength, not the final value
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotUseMeleeWeaponlikeBecauseOfMissingStrength
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmament
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownMeleeWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     */
    public function getPowerOfDestruction(
        MeleeWeaponlikeCode $meleeWeaponlikeCode,
        Strength $strength,
        ItemHoldingCode $itemHoldingCode
    ): PowerOfDestruction
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new PowerOfDestruction($meleeWeaponlikeCode, $strength, $itemHoldingCode, $this->tables);
    }

    /**
     * @param MaterialCode $materialCode
     * @return MaterialResistance
     * @throws \DrdPlus\Tables\Environments\Exceptions\UnknownMaterialToGetResistanceFor
     */
    public function getMaterialResistance(MaterialCode $materialCode): MaterialResistance
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new MaterialResistance(
            $this->tables->getMaterialResistancesTable()->getResistanceOfMaterial($materialCode)
        );
    }

    /**
     * @param PowerOfDestruction $powerOfDestruction
     * @param MaterialResistance $materialResistance
     * @param RollOnQuality $rollOnDestruction
     * @return RollOnDestruction
     */
    public function getRollOnDestruction(
        PowerOfDestruction $powerOfDestruction,
        MaterialResistance $materialResistance,
        RollOnQuality $rollOnDestruction
    ): RollOnDestruction
    {
        return new RollOnDestruction($powerOfDestruction, $materialResistance, $rollOnDestruction);
    }
}
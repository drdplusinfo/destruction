<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Destruction;

use DrdPlus\Properties\Body\Size;
use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tables\Measurements\Volume\VolumeBonus;
use Granam\Integer\IntegerInterface;

/**
 * This is an expression of a time consumed on the worst, but YET successful destruction of an item.
 */
class BaseTimeOfDestruction extends TimeBonus
{
    /**
     * @param IntegerInterface $itemSize
     * @param TimeTable $timeTable
     */
    public function __construct(IntegerInterface $itemSize, TimeTable $timeTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::__construct($itemSize->getValue() + 51, $timeTable);
    }

    public static function createForItemSize(IntegerInterface $itemSize, TimeTable $timeTable)
    {
        return new static($itemSize, $timeTable);
    }

    /**
     * @param Size $size
     * @param TimeTable $timeTable
     * @return BaseTimeOfDestruction
     */
    public static function createForBodySize(Size $size, TimeTable $timeTable): BaseTimeOfDestruction
    {
        return new static($size, $timeTable);
    }

    /**
     * @param VolumeBonus $volumeBonus
     * @param TimeTable $timeTable
     * @return BaseTimeOfDestruction
     */
    public static function createForItemOfVolume(VolumeBonus $volumeBonus, TimeTable $timeTable): BaseTimeOfDestruction
    {
        return new static($volumeBonus, $timeTable);
    }
}
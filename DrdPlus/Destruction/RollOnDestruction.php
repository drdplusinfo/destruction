<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Destruction;

use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;
use DrdPlus\RollsOn\QualityAndSuccess\SimpleRollOnSuccess;
use Granam\Integer\IntegerInterface;

class RollOnDestruction extends SimpleRollOnSuccess implements IntegerInterface
{
    const NOT_DAMAGED = 'not_damaged';
    const DAMAGED = 'damaged';

    /**
     * @param PowerOfDestruction $powerOfDestruction
     * @param MaterialResistance $materialResistance
     * @param RollOnQuality $rollOnDestructing
     */
    public function __construct(
        PowerOfDestruction $powerOfDestruction,
        MaterialResistance $materialResistance,
        RollOnQuality $rollOnDestructing
    )
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::__construct(
            $materialResistance->getValue() - $powerOfDestruction->getValue(), // as a difficulty
            $rollOnDestructing,
            self::NOT_DAMAGED,
            self::DAMAGED
        );
    }

    /**
     * Final roll on destruction, including material resistance and power of destruction (weapon + strength)
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->getRollOnQuality()->getValue() - $this->getDifficulty();
    }

}
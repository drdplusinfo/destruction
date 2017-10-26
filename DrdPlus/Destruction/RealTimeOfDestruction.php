<?php
declare(strict_types=1); // on PHP 7+ can be methods strict to scalar types of given parameters

namespace DrdPlus\Destruction;

use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Measurements\Time\TimeTable;

/**
 * @link https://pph.plus.info/#vypocet_skutecne_doby_niceni
 */
class RealTimeOfDestruction extends TimeBonus
{
    public function __construct(
        BaseTimeOfDestruction $baseTimeOfDestruction,
        RollOnDestruction $rollOnDestruction,
        TimeTable $timeTable
    )
    {
        parent::__construct($baseTimeOfDestruction->getValue() - $rollOnDestruction->getValue(), $timeTable);
    }
}
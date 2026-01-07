<?php

namespace App\Service\Data;

use App\Entity\Nick;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NickServiceInterface
{
    public function save(Nick $nick): void;

    public function incrementUsageCount(Nick $nick): void;

    public function getOrCreate(
        Subject $subject,
        Qualifier $qualifier,
        WordGender $targetGender,
        OffenseLevel $offenseLevel,
        string $label): Nick;
}

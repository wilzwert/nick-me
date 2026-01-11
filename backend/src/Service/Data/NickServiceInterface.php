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
    /**
     * @param Nick $nick
     * @return void
     */
    public function save(Nick $nick): void;

    /**
     * @param int $id
     * @return Nick|null
     */
    public function getNick(int $id): ?Nick;

    /**
     * @param Nick $nick
     * @return void
     */
    public function incrementUsageCount(Nick $nick): void;

    /**
     * @param Subject $subject
     * @param Qualifier $qualifier
     * @param WordGender $targetGender
     * @param OffenseLevel $offenseLevel
     * @param string $label
     * @return Nick
     */
    public function getOrCreate(
        Subject $subject,
        Qualifier $qualifier,
        WordGender $targetGender,
        OffenseLevel $offenseLevel,
        string $label
    ): Nick;
}

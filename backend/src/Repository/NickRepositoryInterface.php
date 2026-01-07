<?php

namespace App\Repository;

use App\Entity\Nick;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NickRepositoryInterface
{
    public function getById(int $id): ?Nick;

    public function getByProperties(Subject $subject, Qualifier $qualifier, WordGender $targetGender): ?Nick;
}

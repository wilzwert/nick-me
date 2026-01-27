<?php

namespace App\Repository;

use App\Entity\Contact;

/**
 * @author Wilhelm Zwertvaegher
 */
interface ContactRepositoryInterface
{
    public function getById(int $id): ?Contact;
}

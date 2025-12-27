<?php

namespace App\Repository;

use App\Entity\Qualifier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
* @author Wilhelm Zwertvaegher
* @extends ServiceEntityRepository<Qualifier>
 */
class QualifierRepository extends ServiceEntityRepository implements QualifierRepositoryInterface
{

}

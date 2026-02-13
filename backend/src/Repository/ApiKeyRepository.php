<?php

namespace App\Repository;
use App\Entity\ApiKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiKey>
 *
 * @author Wilhelm Zwertvaegher
 */
class ApiKeyRepository extends ServiceEntityRepository implements ApiKeyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiKey::class);
    }

    public function findById(int $id): ?ApiKey
    {
        return parent::find($id);
    }

    public function findByHash(string $hash): ?ApiKey
    {
        return parent::findOneBy(['hash' => $hash]);
    }
}

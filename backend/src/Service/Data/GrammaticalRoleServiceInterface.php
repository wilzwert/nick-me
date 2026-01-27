<?php

namespace App\Service\Data;

use App\Entity\GrammaticalRole;
use App\Enum\GrammaticalRoleType;
use App\Specification\WordCriteria;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @template T of GrammaticalRole
 *
 * @author Wilhelm Zwertvaegher
 */
#[AutoconfigureTag('app.word_type_data_service')]
interface GrammaticalRoleServiceInterface
{
    public function getGrammaticalRole(): GrammaticalRoleType;

    /**
     * @return ?T
     */
    public function findByWordId(int $wordId): ?GrammaticalRole;

    /**
     * Find a similar GrammaticalRole.
     *
     * @param T $other
     *
     * @return ?T
     */
    public function findSimilar(GrammaticalRole $other, WordCriteria $criteria): ?GrammaticalRole;

    /**
     * Increments a grammatical role usages count.
     */
    public function incrementUsageCount(GrammaticalRole $grammaticalRole): void;
}

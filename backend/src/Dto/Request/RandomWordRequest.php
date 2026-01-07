<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Enum\GrammaticalRoleType;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @author Wilhelm Zwertvaegher
 * Parameters for word retrieval (i.e. replace a word in a generated nick)
 *  - Lang
 *  - WordType
 *  - OffenseLevel (maybe null)
 *  - WordGender (maybe null)
 *  - exclusions : a list a word ids to exclude
 *
 */
class RandomWordRequest implements Request
{
    /**
     * @param int $previousId
     * @param GrammaticalRoleType $role
     * @param WordGender $gender
     * @param OffenseLevel $offenseLevel
     * @param list<int> $exclusions
     */
    public function __construct(
        #[Assert\Type('integer')]
        private int                 $previousId,
        private GrammaticalRoleType $role,
        private WordGender         $gender,
        private OffenseLevel       $offenseLevel = OffenseLevel::HIGH,
        private array                      $exclusions = [],
    ) {
    }

    public function getPreviousId(): int
    {
        return $this->previousId;
    }

    public function getGrammaticalRoleType(): GrammaticalRoleType
    {
        return $this->role;
    }

    public function getGender(): ?WordGender
    {
        return $this->gender;
    }

    public function getOffenseLevel(): ?OffenseLevel
    {
        return $this->offenseLevel;
    }

    /**
     * @return list<int>
     */
    public function getExclusions(): array
    {
        return $this->exclusions;
    }





}

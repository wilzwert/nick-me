<?php

namespace App\Dto\Request;

use App\Enum\Lang;
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
readonly class RandomWordRequest
{
    /**
     * @var list<int>
     */
    private array $exclusions;

    public function __construct(
        private int                 $previousId,
        private GrammaticalRoleType $role,
        private WordGender         $gender,
        private OffenseLevel       $offenseLevel = OffenseLevel::HIGH,
        string                      $exclusions = '',
    ) {
        $exclusionsStrArray = explode(',', $exclusions);
        $exclusionsIntArray = [];
        foreach ($exclusionsStrArray as $exclusion) {
            if( !filter_var($exclusion, FILTER_VALIDATE_INT) ) {
                throw new ValidatorException('Exclusions must be integers');
            }
            $exclusionsIntArray[] = (int)$exclusion;
        }
        $this->exclusions = $exclusionsIntArray;
    }

    public function getPreviousId(): int
    {
        return $this->previousId;
    }

    public function getGrammaticalRole(): GrammaticalRoleType
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

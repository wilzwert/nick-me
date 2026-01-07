<?php

namespace App\Dto\Request;

use App\Entity\GrammaticalRole;
use App\Enum\EnumConverter;
use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class RequestFactory
{
    /**
     * @var array<class-string, \Closure>
     */
    private array $strategies;

    public function __construct(private EnumConverter $enumConverter)
    {
        $this->strategies = array(
            RandomNickRequest::class => fn(array $parameters) => new RandomNickRequest(
                lang: isset($parameters['lang']) ? $this->enumConverter->convert(Lang::class, $parameters['lang']) : Lang::FR,
                gender: isset($parameters['gender']) ? $this->enumConverter->convert(WordGender::class, $parameters['gender']) : WordGender::AUTO,
                offenseLevel: isset($parameters['offenseLevel']) ? $this->enumConverter->convert(OffenseLevel::class, $parameters['offenseLevel']) : null,
                exclusions: $parameters['exclusions'] ?? ''
            ),
            RandomWordRequest::class => fn(array $parameters) => new RandomWordRequest(
                previousId: $parameters['previousId'] ?? null,
                role: isset($parameters['role']) ? $this->enumConverter->convert(GrammaticalRoleType::class, $parameters['role']) : null,
                gender: isset($parameters['gender']) ? $this->enumConverter->convert(WordGender::class, $parameters['gender']) : null,
                offenseLevel: isset($parameters['offenseLevel']) ? $this->enumConverter->convert(OffenseLevel::class, $parameters['offenseLevel']) : null,
                exclusions: $parameters['exclusions'] ?? ''
            ),
        );
    }

    /**
     * @param class-string $class
     * @param array<string, string> $parameters
     * @return Request
     */
    public function fromParameters(string $class, array $parameters): Request
    {
        if(!array_key_exists($class, $this->strategies)) {
            throw new ValidatorException('Strategy not found');
        }

        return $this->strategies[$class]($parameters);
    }
}

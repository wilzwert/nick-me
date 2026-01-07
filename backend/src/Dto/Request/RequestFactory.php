<?php

namespace App\Dto\Request;

use App\Enum\Enum;
use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Exception\EnumConversionException;
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
                lang: $this->handleConversion(Lang::class, 'lang', $parameters, Lang::FR),
                gender: $this->handleConversion(WordGender::class, 'gender', $parameters, WordGender::AUTO),
                offenseLevel: $this->handleConversion(OffenseLevel::class, 'offenseLevel', $parameters),
                exclusions: $parameters['exclusions'] ?? ''
            ),
            RandomWordRequest::class => fn(array $parameters) => new RandomWordRequest(
                previousId: $parameters['previousId'] ?? null,
                role: $this->handleConversion(GrammaticalRoleType::class, 'role', $parameters),
                gender: $this->handleConversion(WordGender::class, 'gender', $parameters),
                offenseLevel: $this->handleConversion(OffenseLevel::class, 'offenseLevel', $parameters),
                exclusions: $parameters['exclusions'] ?? ''
            ),
        );
    }

    /**
     * @template  T of Enum
     * @param class-string<T> $enumClass
     * @param string $field
     * @param array $parameters
     * @return ?T
     * @throws EnumConversionException
     */
    private function handleConversion(string $enumClass, string $field, array $parameters, ?Enum $defaultValue = null): ?Enum
    {
        if (!isset($parameters[$field])) {
            return $defaultValue;
        }

        try {
            return $this->enumConverter->convert($enumClass, $parameters[$field]);
        }
        catch (\Throwable $t) {
            throw new EnumConversionException($enumClass, $field, $parameters[$field], 0, $t);
        }
    }


    /**
     * @param class-string $class
     * @param array<string, string> $parameters
     * @return Request
     * @throws EnumConversionException|\TypeError
     */
    public function fromParameters(string $class, array $parameters): Request
    {
        if(!array_key_exists($class, $this->strategies)) {
            throw new ValidatorException('Strategy not found');
        }

        return $this->strategies[$class]($parameters);
    }
}

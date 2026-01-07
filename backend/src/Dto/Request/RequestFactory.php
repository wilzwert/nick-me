<?php

namespace App\Dto\Request;

use App\Enum\Enum;
use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Exception\ConversionException;

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
        $this->strategies = [
            RandomNickRequest::class => fn (array $parameters) => new RandomNickRequest(
                lang: $this->convertEnum(Lang::class, 'lang', $parameters, Lang::FR),
                gender: $this->convertEnum(WordGender::class, 'gender', $parameters, WordGender::AUTO),
                offenseLevel: $this->convertEnum(OffenseLevel::class, 'offenseLevel', $parameters),
                exclusions: $this->convertIntArray('exclusions', $parameters)
            ),
            RandomWordRequest::class => fn (array $parameters) => new RandomWordRequest(
                previousId: $parameters['previousId'] ?? null,
                role: $this->convertEnum(GrammaticalRoleType::class, 'role', $parameters),
                gender: $this->convertEnum(WordGender::class, 'gender', $parameters),
                offenseLevel: $this->convertEnum(OffenseLevel::class, 'offenseLevel', $parameters),
                exclusions: $this->convertIntArray('exclusions', $parameters)
            ),
        ];
    }

    /**
     * @param array<string, string> $parameters
     *
     * @return array<int>
     *
     * @throws ConversionException
     */
    private function convertIntArray(string $field, array $parameters): array
    {
        $str = $parameters[$field] ?? '';
        $intArray = [];
        if ('' != $str) {
            $strArray = explode(',', $str);
            foreach ($strArray as $value) {
                if (!filter_var($value, FILTER_VALIDATE_INT)) {
                    throw new ConversionException($field, $str);
                }
                $intArray[] = (int) $value;
            }
        }

        return $intArray;
    }

    /**
     * @template  T of Enum
     *
     * @param class-string<T>       $enumClass
     * @param array<string, string> $parameters
     *
     * @return ?T
     *
     * @throws ConversionException
     */
    private function convertEnum(string $enumClass, string $field, array $parameters, ?Enum $defaultValue = null): ?Enum
    {
        if (!isset($parameters[$field])) {
            return $defaultValue;
        }

        try {
            return $this->enumConverter->convert($enumClass, $parameters[$field]);
        } catch (\Throwable $t) {
            throw new ConversionException($field, $parameters[$field], 0, $t);
        }
    }

    /**
     * @param class-string          $class
     * @param array<string, string> $parameters
     *
     * @throws ConversionException|\TypeError
     */
    public function fromParameters(string $class, array $parameters): Request
    {
        if (!array_key_exists($class, $this->strategies)) {
            throw new \InvalidArgumentException('Strategy not found');
        }

        return $this->strategies[$class]($parameters);
    }
}

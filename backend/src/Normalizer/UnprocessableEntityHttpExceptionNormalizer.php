<?php

namespace App\Normalizer;

use App\Exception\ErrorCode;
use App\Exception\ErrorMessage;
use Symfony\Component\Clock\ClockAwareTrait;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AutoconfigureTag('app.exception_normalizer')]
class UnprocessableEntityHttpExceptionNormalizer extends ExceptionNormalizer
{
    use ClockAwareTrait;

    /**
     * @param array<string, mixed> $context
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof UnprocessableEntityHttpException;
    }

    /**
     * @return array<string, array<string, string[]|int[]>>
     */
    protected function normalizeErrors(\Throwable $throwable): array
    {
        if (!$throwable instanceof UnprocessableEntityHttpException) {
            throw new \InvalidArgumentException();
        }

        /**
         * @var ValidationFailedException $validationFailedException
         */
        $validationFailedException = $throwable->getPrevious();

        $errorsAsArray = [];
        foreach ($validationFailedException->getViolations() as $violation) {
            $propertyPath = $violation->getPropertyPath();
            /** @var class-string<Constraint> $constraintClass */
            $constraintClass = $violation->getConstraint() ? $violation->getConstraint()::class : null;
            $detailCode = $constraintClass ? $constraintClass::getErrorName($violation->getCode()) : 'UNKNOWN_ERROR';
            if (!isset($errorsAsArray[$propertyPath])) {
                $errorsAsArray[$propertyPath] = [];
            }
            if (!isset($errorsAsArray[$propertyPath][$detailCode])) {
                $errorsAsArray[$propertyPath][$detailCode] = [];
            }
            $errorsAsArray[$propertyPath][$detailCode][strtolower($detailCode)] = $this->translator->translate($violation->getMessage());
        }

        return $errorsAsArray;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [UnprocessableEntityHttpException::class => true];
    }

    #[\Override]
    protected function getErrorCode(\Throwable $throwable): ErrorCode
    {
        return ErrorCode::VALIDATION_ERROR;
    }

    protected function getMessage(\Throwable $throwable): string
    {
        return ErrorMessage::VALIDATION_FAILED;
    }

    #[\Override]
    protected function getStatus(\Throwable $throwable): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }
}

<?php

namespace App\Dto\Request;

use App\Exception\ConversionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Wilhelm Zwertvaegher
 */

class CustomRequestQueryValueResolver implements ValueResolverInterface
{
    /**
     * @param RequestFactory $requestFactory
     */
    public function __construct(
        private readonly RequestFactory $requestFactory,
        private readonly ValidatorInterface $validator,
    )
    {
    }

    private function enumConversionExceptionToViolation(ConversionException $e): ConstraintViolation
    {
        $message = $e->getMessage();

        return new ConstraintViolation(
            message: "Invalid enum value. {$message}",
            messageTemplate: '{{ message }}',
            parameters: [],
            root: null,
            propertyPath: $e->getPath(),
            invalidValue: $e->getValue(),
        );
    }

    private function typeErrorToViolation(\TypeError $e): ConstraintViolation
    {
        $message = $e->getMessage();

        if (preg_match('/\$\w+/', $message, $matches)) {
            $property = ltrim($matches[0], '$');
        } else {
            $property = 'unknown';
        }

        if (preg_match('/,\s*(.+)\s+given/', $message, $matches)) {
            $invalidValue = $matches[1];
        } else {
            $invalidValue = null;
        }

        return new ConstraintViolation(
            message: "Invalid type.",
            messageTemplate: null,
            parameters: [],
            root: null,
            propertyPath: $property,
            invalidValue: $invalidValue,
        );
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return iterable<\App\Dto\Request\Request>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$argument->getAttributes(RequestFromQuery::class)) {
            return [];
        }

        if (!$argument->getType() || !is_a($argument->getType(), \App\Dto\Request\Request::class, allow_string: true )) {
            return [];
        }

        $dto = null;
        $violations = new ConstraintViolationList();
        try {
            $dto = $this->requestFactory->fromParameters($argument->getType(), $request->query->all());
            $violations = $this->validator->validate($dto);
        }
        catch (ConversionException $e) {
            $violations->add($this->enumConversionExceptionToViolation($e));
        }
        catch (\TypeError $e) {
            $violations->add($this->typeErrorToViolation($e));
        }

        if($violations->count() > 0) {
            throw HttpException::fromStatusCode(
                422,
                implode("\n",
                    array_map(
                        static fn ($e) => $e->getMessage(),
                        iterator_to_array($violations)
                    )
                ),
                new ValidationFailedException(null, $violations));
        }

        return $dto ? [$dto] : [];
    }
}

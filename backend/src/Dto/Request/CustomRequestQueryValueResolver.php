<?php

namespace App\Dto\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * @author Wilhelm Zwertvaegher
 */

class CustomRequestQueryValueResolver implements ValueResolverInterface
{
    /**
     * @param RequestFactory $requestFactory
     */
    public function __construct(
        private readonly RequestFactory $requestFactory
    )
    {
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

        return [$this->requestFactory->fromParameters($argument->getType(), $request->query->all())];
    }
}

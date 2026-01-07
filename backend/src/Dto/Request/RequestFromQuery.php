<?php

namespace App\Dto\Request;

/**
 * Attribute to explicitly target our CustomRequestQueryValueResolver.
 *
 * @author Wilhelm Zwertvaegher
 *
 * @see CustomRequestQueryValueResolver
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class RequestFromQuery
{
}

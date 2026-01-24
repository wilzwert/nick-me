<?php

namespace App\Exception;

/**
 * @author Wilhelm Zwertvaegher
 */
final readonly class ErrorMessage
{
    public const string VALIDATION_FAILED = 'error.validation_failed';
    public const string WORD_ALREADY_EXISTS = 'error.word_already_exists';
    public const string WORD_NOT_FOUND = 'error.word_not_found';
    public const string NICK_NOT_FOUND = 'error.nick_not_found';
    public const string NO_WORD_FOUND = 'error.no_word_found';
}

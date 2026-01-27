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
    public const string NOTIFICATION_NOT_FOUND = 'error.notification_not_found';
    public const string NO_SUBJECT_FOUND = 'error.no_subject_found';
    public const string NO_QUALIFIER_FOUND = 'error.no_qualifier_found';
}

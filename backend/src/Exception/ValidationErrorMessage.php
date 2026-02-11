<?php

namespace App\Exception;

/**
 * @author Wilhelm Zwertvaegher
 */
class ValidationErrorMessage
{
    public const string UNKNOWN_ERROR = 'error.validation.unknown_error';
    public const string INVALID_EMAIL = 'error.validation.invalid_email';
    public const string INVALID_FIELD_VALUE = 'error.validation.invalid_field_value';
    public const string FIELD_CANNOT_BE_NULL = 'error.validation.field_cannot_be_null';
    public const string FIELD_CANNOT_BE_EMPTY = 'error.validation.field_cannot_be_empty';
    public const string MALFORMED_FIELD_VALUE = 'error.validation.malformed_value';
    public const string FIELD_CANNOT_CONTAIN_HTML = 'error.validation.field_cannot_contain_html';
    public const string ENTITY_ALREADY_EXISTS = 'error.validation.entity_already_exists';
    public const string FIELD_VALUE_TOO_SHORT = 'error.validation.field_value_too_short';
    public const string QUALIFIER_POSITION_CANNOT_BE_EMPTY = 'error.validation.qualifier_position_cannot_be_empty';
}

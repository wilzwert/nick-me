<?php

namespace App\Tests\Support;

/**
 * @author Wilhelm Zwertvaegher
 */
class ApiUrl
{
    public const string NICK_ENDPOINT = '/api/nick';
    public const string WORD_ENDPOINT = '/api/word';
    public const string CONTACT_ENDPOINT = '/api/contact';

    public static function build($endpoint, $query = ''): string
    {
        return sprintf('%s?%s', $endpoint, $query);
    }
}

<?php

namespace App\Tests\Support;

/**
 * @author Wilhelm Zwertvaegher
 */
class ApiUrl
{
    const string NICK_ENDPOINT = '/api/nick';

    const string WORD_ENDPOINT = '/api/word';

    public static function build($endpoint, $query = ''): string
    {
        return sprintf('%s?%s', $endpoint, $query);
    }
}

<?php

namespace App\Tests\Support;

/**
 * @author Wilhelm Zwertvaegher
 */
class AppTestData
{
    public const int EXISTING_NICK_ID = 1;
    public const int EXISTING_PENDING_CONTACT_NOTIFICATION_ID = 1;
    public const int EXISTING_HANDLED_SUGGESTION_NOTIFICATION_ID = 2;

    public const string CLIENT_API_KEY_HEADER = 'Client-Api-Key';
    public const string CLIENT_API_KEY = 'CLIENT-API-KEY';
    public const string CLIENT_INVALID_API_KEY = 'invalid';

    public const string INTERNAL_APP_KEY_HEADER = 'Internal-App-Key';
    public const string INTERNAL_APP_KEY = 'INTERNAL-APP-KEY';
    public const string INTERNAL_APP_INVALID_KEY = 'invalid';
}

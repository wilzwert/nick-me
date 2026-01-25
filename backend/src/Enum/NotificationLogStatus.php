<?php

namespace App\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
enum NotificationLogStatus: string
{
    case SENT = 'sent';
    case ERROR = 'error';
    case DISCARDED = 'discarded';
}

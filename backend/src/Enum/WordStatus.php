<?php

namespace App\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
enum WordStatus: string
{
    case PENDING = "PENDING";
    case REJECTED = "REJECTED";
    case APPROVED = "APPROVED";

}

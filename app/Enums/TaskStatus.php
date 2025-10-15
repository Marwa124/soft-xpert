<?php

namespace App\Enums;

abstract class TaskStatus
{
    public const PENDING = 1;
    public const IN_PROGRESS = 2;
    public const COMPLETED = 3;
    public const CANCELED = 4;

    public const STATUS = [
        'PENDING' => self::PENDING,
        'IN_PROGRESS' => self::IN_PROGRESS,
        'COMPLETED' => self::COMPLETED,
        'CANCELED' => self::CANCELED,
    ];

    public static function getName(int $value): string
    {
        // We are using array_flip cause the PAYMENT_METHODS array has the keys as the string, so flipping the array solves the issue
        return __('product.'.array_flip(self::STATUS)[$value]);
    }
}

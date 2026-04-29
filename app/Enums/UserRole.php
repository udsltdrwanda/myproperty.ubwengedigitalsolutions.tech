<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'ADMIN';
    case LANDLORD = 'LANDLORD';
    case EMPLOYEE = 'EMPLOYEE';
    case TENANT = 'TENANT';
    case USER = 'USER';
    public static function getValues(): array
    {
        return [self::ADMIN->value, self::LANDLORD->value, self::EMPLOYEE->value, self::TENANT->value, self::USER->value];
    }
}

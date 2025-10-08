<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case USER = 'user';

    /**
     * Get all role values
     */
    public static function getAllRoles(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get role display name
     */
    public function getDisplayName(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Administrator',
            self::ADMIN => 'Administrator',
            self::USER => 'User',
        };
    }
}

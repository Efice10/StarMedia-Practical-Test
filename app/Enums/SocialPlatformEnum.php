<?php

namespace App\Enums;

enum SocialPlatformEnum: string
{
    case FACEBOOK = 'facebook';
    case X = 'x'; // Formerly Twitter
    case WHATSAPP = 'whatsapp';
    case TELEGRAM = 'telegram';
    case EMAIL = 'email';

    /**
     * Get all platform values
     */
    public static function getAllPlatforms(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get platform display name
     */
    public function getDisplayName(): string
    {
        return match ($this) {
            self::FACEBOOK => 'Facebook',
            self::X => 'X (Twitter)',
            self::WHATSAPP => 'WhatsApp',
            self::TELEGRAM => 'Telegram',
            self::EMAIL => 'Email',
        };
    }

    /**
     * Get platform icon class (Font Awesome)
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::FACEBOOK => 'fab fa-facebook',
            self::X => 'fab fa-x-twitter',
            self::WHATSAPP => 'fab fa-whatsapp',
            self::TELEGRAM => 'fab fa-telegram',
            self::EMAIL => 'fas fa-envelope',
        };
    }

    /**
     * Get platform brand color
     */
    public function getColor(): string
    {
        return match ($this) {
            self::FACEBOOK => '#1877F2',
            self::X => '#000000',
            self::WHATSAPP => '#25D366',
            self::TELEGRAM => '#0088CC',
            self::EMAIL => '#EA4335',
        };
    }

    /**
     * Get platform data for seeding
     */
    public static function getPlatformData(): array
    {
        $platforms = [];
        $sortOrder = 1;

        foreach (self::cases() as $platform) {
            $platforms[] = [
                'name' => $platform->value,
                'display_name' => $platform->getDisplayName(),
                'icon' => $platform->getIcon(),
                'color' => $platform->getColor(),
                'is_active' => true,
                'sort_order' => $sortOrder++,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $platforms;
    }
}

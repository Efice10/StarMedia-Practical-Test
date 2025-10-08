<?php

namespace App\Enums;

enum PermissionEnum: string
{
    // Analytics Permissions
    case VIEW_ANALYTICS = 'view_analytics';
    case EXPORT_ANALYTICS = 'export_analytics';
    
    // Social Platform Management
    case VIEW_PLATFORMS = 'view_platforms';
    case CREATE_PLATFORMS = 'create_platforms';
    case UPDATE_PLATFORMS = 'update_platforms';
    case DELETE_PLATFORMS = 'delete_platforms';
    
    // User Management
    case VIEW_USERS = 'view_users';
    case CREATE_USERS = 'create_users';
    case UPDATE_USERS = 'update_users';
    case DELETE_USERS = 'delete_users';
    
    // Role Management
    case VIEW_ROLES = 'view_roles';
    case CREATE_ROLES = 'create_roles';
    case UPDATE_ROLES = 'update_roles';
    case DELETE_ROLES = 'delete_roles';
    
    // Activity Logs
    case VIEW_ACTIVITY_LOGS = 'view_activity_logs';

    /**
     * Get all permission values
     */
    public static function getAllPermissions(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get permission display name
     */
    public function getDisplayName(): string
    {
        return ucwords(str_replace('_', ' ', $this->value));
    }

    /**
     * Get permissions by category
     */
    public static function getPermissionsByCategory(): array
    {
        return [
            'Analytics' => [
                self::VIEW_ANALYTICS->value,
                self::EXPORT_ANALYTICS->value,
            ],
            'Social Platforms' => [
                self::VIEW_PLATFORMS->value,
                self::CREATE_PLATFORMS->value,
                self::UPDATE_PLATFORMS->value,
                self::DELETE_PLATFORMS->value,
            ],
            'Users' => [
                self::VIEW_USERS->value,
                self::CREATE_USERS->value,
                self::UPDATE_USERS->value,
                self::DELETE_USERS->value,
            ],
            'Roles' => [
                self::VIEW_ROLES->value,
                self::CREATE_ROLES->value,
                self::UPDATE_ROLES->value,
                self::DELETE_ROLES->value,
            ],
            'Activity Logs' => [
                self::VIEW_ACTIVITY_LOGS->value,
            ],
        ];
    }
}

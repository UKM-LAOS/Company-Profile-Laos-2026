<?php

namespace App\Enums;

enum Permission: string
{
    // User & Role Management
    case MANAGE_USERS = 'manage_users';
    case MANAGE_ROLES = 'manage_roles';

    // Content Management
    case VIEW_CONTENT = 'view_content';
    case CREATE_CONTENT = 'create_content';
    case EDIT_CONTENT = 'edit_content';
    case DELETE_CONTENT = 'delete_content';
    case PUBLISH_CONTENT = 'publish_content';

    // Settings
    case MANAGE_SETTINGS = 'manage_settings';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::MANAGE_USERS => 'Mengelola Pengguna',
            self::MANAGE_ROLES => 'Mengelola Role & Hak Akses',
            self::VIEW_CONTENT => 'Melihat Konten',
            self::CREATE_CONTENT => 'Membuat Konten',
            self::EDIT_CONTENT => 'Mengubah Konten',
            self::DELETE_CONTENT => 'Menghapus Konten',
            self::PUBLISH_CONTENT => 'Mempublikasikan Konten',
            self::MANAGE_SETTINGS => 'Mengelola Pengaturan Sistem',
        };
    }
}

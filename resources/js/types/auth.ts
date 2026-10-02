export type RoleName = 'super_admin' | 'admin' | 'member' | (string & {});

export type PermissionName =
    | 'manage_users'
    | 'manage_roles'
    | 'view_content'
    | 'create_content'
    | 'edit_content'
    | 'delete_content'
    | 'publish_content'
    | 'manage_settings'
    | (string & {});

export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    roles?: RoleName[];
    permissions?: PermissionName[];
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

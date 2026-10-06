export type RoleName = string;

export type PermissionName =
    | 'view_dashboard'
    | 'view_divisions'
    | 'create_divisions'
    | 'edit_divisions'
    | 'delete_divisions'
    | 'view_work_programs'
    | 'create_work_programs'
    | 'edit_work_programs'
    | 'delete_work_programs'
    | 'view_news'
    | 'create_news'
    | 'edit_news'
    | 'delete_news'
    | 'view_committee'
    | 'create_committee'
    | 'edit_committee'
    | 'delete_committee'
    | 'view_users'
    | 'create_users'
    | 'edit_users'
    | 'delete_users'
    | 'view_roles'
    | 'create_roles'
    | 'edit_roles'
    | 'delete_roles'
    | 'view_shortlinks'
    | 'create_shortlinks'
    | 'edit_shortlinks'
    | 'delete_shortlinks'
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

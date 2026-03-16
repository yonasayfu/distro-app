export type ManagedRole = {
    id: number;
    name: string;
    permissions: string[];
    usersCount: number;
    editable: boolean;
};

export type ManagedUser = {
    id: number;
    name: string;
    email: string;
    roles: string[];
    isCurrentUser: boolean;
    emailVerifiedAt: string | null;
    createdAt: string | null;
};

export type RoleOption = {
    name: string;
    label: string;
    usersCount: number;
};

export type PermissionDefinition = {
    name: string;
    label: string;
    description: string;
};

export type PermissionGroup = {
    key: string;
    title: string;
    permissions: PermissionDefinition[];
};

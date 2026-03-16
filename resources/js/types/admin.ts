export type ManagedRole = {
    id: number;
    name: string;
    description: string | null;
    permissions: string[];
    usersCount: number;
    permissionsCount: number;
    isSystem: boolean;
    canDelete: boolean;
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
    description: string | null;
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

export type ResourceFilters = {
    search: string;
};

export type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

export type PaginatedResource<T> = {
    data: T[];
    current_page: number;
    from: number | null;
    last_page: number;
    links: PaginationLink[];
    per_page: number;
    to: number | null;
    total: number;
};

export type FlashMessages = {
    success?: string | null;
    error?: string | null;
};

export type ManagedNotification = {
    id: string;
    title: string;
    message: string;
    actionUrl: string | null;
    actionLabel: string | null;
    level: string;
    readAt: string | null;
    createdAt: string | null;
};

export type NotificationStats = {
    unreadCount: number;
    totalCount: number;
};

export type NotificationFilters = {
    read: string;
};

export type ManagedActivityLog = {
    id: number;
    event: string;
    description: string;
    actor: string | null;
    ipAddress: string | null;
    createdAt: string | null;
    properties: Record<string, unknown>;
};

export type ActivityLogFilters = {
    search: string;
    event: string;
};

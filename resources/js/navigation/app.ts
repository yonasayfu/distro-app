import {
    Bell,
    FileOutput,
    LayoutGrid,
    Search,
    Shield,
    ScrollText,
    Users,
} from 'lucide-vue-next';
import { index as activityLogsIndex } from '@/routes/activity-logs';
import { dashboard } from '@/routes';
import { index as exportsIndex } from '@/routes/exports';
import { index as notificationsIndex } from '@/routes/notifications';
import { index as searchIndex } from '@/routes/search';
import { index as rolesIndex } from '@/routes/roles';
import { index as usersIndex } from '@/routes/users';
import type { NavGroup } from '@/types';

export const appNavigation: NavGroup[] = [
    {
        title: 'Workspace',
        description: 'Shared for all signed-in users',
        items: [
            {
                title: 'Dashboard',
                href: dashboard(),
                icon: LayoutGrid,
                permission: 'dashboard.view',
            },
            {
                title: 'Search',
                href: searchIndex(),
                icon: Search,
                permission: 'search.view',
            },
        ],
    },
    {
        title: 'Updates',
        description: 'Permission-aware cross-project modules',
        items: [
            {
                title: 'Notifications',
                href: notificationsIndex(),
                icon: Bell,
                permission: 'notifications.view',
            },
            {
                title: 'Activity logs',
                href: activityLogsIndex(),
                icon: ScrollText,
                permission: 'activity-logs.view',
            },
        ],
    },
    {
        title: 'Operations',
        description: 'Administrative tools, exports, and governance surfaces',
        items: [
            {
                title: 'Export center',
                href: exportsIndex(),
                icon: FileOutput,
                permission: 'exports.view',
            },
            {
                title: 'Users',
                href: usersIndex(),
                icon: Users,
                permission: 'users.view',
            },
            {
                title: 'Roles',
                href: rolesIndex(),
                icon: Shield,
                permission: 'roles.view',
            },
        ],
    },
];

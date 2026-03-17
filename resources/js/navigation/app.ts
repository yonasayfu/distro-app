import {
    Bell,
    BookOpenText,
    FileOutput,
    FileText,
    LayoutGrid,
    Shield,
    ScrollText,
    Users,
} from 'lucide-vue-next';
import { index as activityLogsIndex } from '@/routes/activity-logs';
import { dashboard } from '@/routes';
import { index as exportsIndex } from '@/routes/exports';
import { index as handbookIndex } from '@/routes/handbook';
import { index as notificationsIndex } from '@/routes/notifications';
import { index as pagesIndex } from '@/routes/pages';
import { index as rolesIndex } from '@/routes/roles';
import { index as usersIndex } from '@/routes/users';
import type { NavGroup } from '@/types';

export const appNavigation: NavGroup[] = [
    {
        title: 'Core',
        items: [
            {
                title: 'Dashboard',
                href: dashboard(),
                icon: LayoutGrid,
                permission: 'dashboard.view',
            },
            {
                title: 'Handbook',
                href: handbookIndex(),
                icon: BookOpenText,
                permission: 'handbook.view',
            },
        ],
    },
    {
        title: 'Insight',
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
        title: 'Management',
        items: [
            {
                title: 'Export center',
                href: exportsIndex(),
                icon: FileOutput,
                permission: 'exports.view',
            },
            {
                title: 'Pages',
                href: pagesIndex(),
                icon: FileText,
                permission: 'pages.view',
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

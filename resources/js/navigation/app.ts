import {
    Bell,
    BookOpenText,
    LayoutGrid,
    Palette,
    Shield,
    ShieldCheck,
    ScrollText,
    UserCog,
    Users,
} from 'lucide-vue-next';
import { index as activityLogsIndex } from '@/routes/activity-logs';
import { dashboard } from '@/routes';
import { edit as editAppearance } from '@/routes/appearance';
import { index as notificationsIndex } from '@/routes/notifications';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
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
        title: 'Account',
        description: 'Common account management pages',
        items: [
            {
                title: 'Profile',
                href: editProfile(),
                icon: UserCog,
            },
            {
                title: 'Security',
                href: editSecurity(),
                icon: ShieldCheck,
            },
            {
                title: 'Appearance',
                href: editAppearance(),
                icon: Palette,
            },
        ],
    },
    {
        title: 'Administration',
        description: 'Visible only when the signed-in user can manage access',
        items: [
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

export const appResourceLinks: NavGroup[] = [
    {
        title: 'Resources',
        items: [
            {
                title: 'Laravel Docs',
                href: 'https://laravel.com/docs',
                icon: BookOpenText,
            },
            {
                title: 'Inertia Docs',
                href: 'https://inertiajs.com/',
                icon: BookOpenText,
            },
        ],
    },
];

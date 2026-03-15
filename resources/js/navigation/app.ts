import {
    BookOpenText,
    LayoutGrid,
    Palette,
    ShieldCheck,
    UserCog,
} from 'lucide-vue-next';
import { dashboard } from '@/routes';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { NavGroup } from '@/types';

export const appNavigation: NavGroup[] = [
    {
        title: 'Workspace',
        items: [
            {
                title: 'Dashboard',
                href: dashboard(),
                icon: LayoutGrid,
            },
        ],
    },
    {
        title: 'Account',
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

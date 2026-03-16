<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Shield, SlidersHorizontal } from 'lucide-vue-next';
import RolePermissionCard from '@/components/admin/RolePermissionCard.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as rolesIndex } from '@/routes/roles';
import type { BreadcrumbItem, ManagedRole, PermissionGroup } from '@/types';

type Props = {
    roles: ManagedRole[];
    permissionGroups: PermissionGroup[];
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles',
        href: rolesIndex(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Roles" />

        <PageContainer>
            <PageHeader
                title="Roles and permission management"
                description="Assign capabilities at the role level so the sidebar, route protection, and future CRUD actions all stay aligned from one source."
            >
                <template #eyebrow>
                    <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-medium tracking-[0.2em] text-sky-900 uppercase dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-100">
                        <Shield class="size-3.5" />
                        RBAC control
                    </div>
                </template>
                <template #actions>
                    <div class="inline-flex items-center gap-2 rounded-full border border-border/70 bg-background/80 px-3 py-2 text-xs text-muted-foreground">
                        <SlidersHorizontal class="size-3.5" />
                        Permission changes apply after save
                    </div>
                </template>
            </PageHeader>

            <div class="grid gap-6">
                <RolePermissionCard
                    v-for="role in roles"
                    :key="role.id"
                    :role="role"
                    :permission-groups="permissionGroups"
                />
            </div>
        </PageContainer>
    </AppLayout>
</template>

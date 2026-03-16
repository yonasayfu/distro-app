<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ShieldCheck, Users } from 'lucide-vue-next';
import UserRoleCard from '@/components/admin/UserRoleCard.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as usersIndex } from '@/routes/users';
import type { BreadcrumbItem, ManagedUser, RoleOption } from '@/types';

type Props = {
    users: ManagedUser[];
    roles: RoleOption[];
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: usersIndex(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Users" />

        <PageContainer>
            <PageHeader
                title="Users management"
                description="Assign roles to users here. Those role changes immediately drive what each signed-in user can see and do across the application."
            >
                <template #eyebrow>
                    <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-medium tracking-[0.2em] text-emerald-900 uppercase dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-100">
                        <Users class="size-3.5" />
                        Admin only
                    </div>
                </template>
                <template #actions>
                    <div class="inline-flex items-center gap-2 rounded-full border border-border/70 bg-background/80 px-3 py-2 text-xs text-muted-foreground">
                        <ShieldCheck class="size-3.5" />
                        Role changes refresh access on the next request
                    </div>
                </template>
            </PageHeader>

            <div class="grid gap-6">
                <UserRoleCard
                    v-for="user in users"
                    :key="user.id"
                    :user="user"
                    :roles="roles"
                />
            </div>
        </PageContainer>
    </AppLayout>
</template>

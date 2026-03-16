<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ArrowRight, Blocks, SearchX, ShieldCheck, Sparkles } from 'lucide-vue-next';
import EmptyState from '@/components/EmptyState.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import LoadingState from '@/components/LoadingState.vue';
import PageContainer from '@/components/PageContainer.vue';
import PageErrorState from '@/components/PageErrorState.vue';
import PageHeader from '@/components/PageHeader.vue';
import { edit as editAppearance } from '@/routes/appearance';
import { dashboard } from '@/routes';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
];

const starterTracks = [
    {
        title: 'App shell',
        description:
            'Replace starter defaults with a reusable workspace layout, navigation system, and page patterns.',
    },
    {
        title: 'Identity and security',
        description:
            'Build on Fortify with polished account settings, two-factor auth, and a stronger admin foundation.',
    },
    {
        title: 'Reusable modules',
        description:
            'Add RBAC, users, roles, notifications, activity logs, and API foundations for future projects.',
    },
];

const quickLinks = [
    {
        title: 'Profile settings',
        description: 'Update account details and baseline user preferences.',
        href: editProfile(),
    },
    {
        title: 'Security settings',
        description: 'Review password and two-factor authentication.',
        href: editSecurity(),
    },
    {
        title: 'Appearance settings',
        description: 'Set theme preferences for the starter shell.',
        href: editAppearance(),
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <PageContainer class="bg-[radial-gradient(circle_at_top_left,#fef3c7,transparent_28%),radial-gradient(circle_at_top_right,#dbeafe,transparent_24%)] dark:bg-[radial-gradient(circle_at_top_left,rgba(245,158,11,0.12),transparent_28%),radial-gradient(circle_at_top_right,rgba(59,130,246,0.16),transparent_24%)]">
            <PageHeader
                title="Build the reusable admin shell before adding modules."
                description="This starter now acts like a real project workspace instead of a demo page. The next phases will add RBAC, user administration, notifications, activity logs, and API foundations on top of this shell."
            >
                <template #eyebrow>
                    <div class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-medium tracking-[0.2em] text-amber-900 uppercase dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                        <Sparkles class="size-3.5" />
                        Phase 1 workspace
                    </div>
                </template>
            </PageHeader>

            <section
                class="overflow-hidden rounded-[1.75rem] border border-border/70 bg-card/90 p-6 shadow-sm backdrop-blur xl:p-8"
            >
                <div class="grid gap-10 xl:grid-cols-[1.7fr_1fr]">
                    <div class="space-y-6">
                        <div class="grid gap-3 md:grid-cols-3">
                            <article
                                v-for="track in starterTracks"
                                :key="track.title"
                                class="rounded-2xl border border-border/70 bg-background/80 p-4"
                            >
                                <h2 class="text-sm font-semibold">
                                    {{ track.title }}
                                </h2>
                                <p class="mt-2 text-sm leading-6 text-muted-foreground">
                                    {{ track.description }}
                                </p>
                            </article>
                        </div>
                    </div>
                    <aside
                        class="rounded-[1.5rem] border border-border/70 bg-[linear-gradient(180deg,rgba(17,24,39,0.96),rgba(31,41,55,0.92))] p-5 text-slate-100 shadow-inner"
                    >
                        <div class="flex items-center gap-2 text-sm font-medium text-amber-200">
                            <Blocks class="size-4" />
                            Starter checklist
                        </div>
                        <div class="mt-5 space-y-4">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-300">
                                    Current focus
                                </div>
                                <p class="mt-2 text-sm leading-6 text-slate-100">
                                    Centralized navigation and a neutral dashboard shell are in place. Next comes the shared page header structure and role-aware navigation.
                                </p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <div class="flex items-center gap-2 text-sm font-medium">
                                    <ShieldCheck class="size-4 text-emerald-300" />
                                    Immediate next phase
                                </div>
                                <p class="mt-2 text-sm leading-6 text-slate-300">
                                    Add RBAC and turn the shell into a real admin foundation rather than a single-user starter.
                                </p>
                            </div>
                        </div>
                    </aside>
                </div>
            </section>

            <section class="mt-4 grid gap-4 xl:grid-cols-[1.3fr_1fr]">
                <div
                    class="rounded-[1.5rem] border border-border/70 bg-card/90 p-6 shadow-sm backdrop-blur"
                >
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold">Quick access</h2>
                            <p class="mt-1 text-sm text-muted-foreground">
                                Use these pages to verify the current shell, settings flow, and account foundation before deeper module work begins.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 grid gap-3">
                        <a
                            v-for="link in quickLinks"
                            :key="link.title"
                            :href="link.href.url"
                            class="group rounded-2xl border border-border/70 bg-background/80 p-4 transition hover:border-foreground/20 hover:bg-background"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-sm font-semibold">
                                        {{ link.title }}
                                    </h3>
                                    <p class="mt-2 text-sm leading-6 text-muted-foreground">
                                        {{ link.description }}
                                    </p>
                                </div>
                                <ArrowRight class="mt-0.5 size-4 shrink-0 text-muted-foreground transition group-hover:translate-x-0.5 group-hover:text-foreground" />
                            </div>
                        </a>
                    </div>
                </div>
                <div
                    class="rounded-[1.5rem] border border-border/70 bg-card/90 p-6 shadow-sm backdrop-blur"
                >
                    <h2 class="text-lg font-semibold">How to judge progress</h2>
                    <div class="mt-4 space-y-3 text-sm leading-6 text-muted-foreground">
                        <p>
                            The shell is correct when new pages no longer invent their own layout, starter links are gone, and navigation lives in one reusable place.
                        </p>
                        <p>
                            The next checkpoints should always update the roadmap task list, add a learning note to <span class="font-medium text-foreground">laravelbasics.md</span>, and include verification steps.
                        </p>
                    </div>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-3">
                <LoadingState />
                <EmptyState
                    title="Empty states are now reusable"
                    description="Future list and detail pages can use one consistent zero-state pattern instead of inventing a different placeholder every time."
                    :icon="SearchX"
                />
                <PageErrorState
                    title="Error presentation pattern"
                    :errors="[
                        'Show actionable, human-readable errors near the page content.',
                        'Reuse this shell-level pattern for future failed states.',
                    ]"
                />
            </section>
        </PageContainer>
    </AppLayout>
</template>

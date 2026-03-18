<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    ArrowRight,
    Blocks,
    BookOpenText,
    Fingerprint,
    Globe2,
    GraduationCap,
    Rocket,
    ShieldCheck,
    Workflow,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public/PublicLayout.vue';
import { dashboard, login, register } from '@/routes';
import { index as handbookIndex } from '@/routes/handbook';
import type { Auth, SharedSettings } from '@/types';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const page = usePage();
const auth = computed(() => page.props.auth as Auth);
const settings = computed(() => page.props.settings as SharedSettings);
const appName = computed(() => page.props.name as string ?? 'Laravel Boilerplate');

const capabilityCards = [
    {
        title: 'Private operations shell',
        description: 'RBAC, CRUD, notifications, audit logs, exports, and API contracts are already in place.',
        icon: ShieldCheck,
    },
    {
        title: 'Public website layer',
        description: 'A marketing-ready surface can live beside the admin app without inheriting the sidebar shell.',
        icon: Globe2,
    },
    {
        title: 'Future module workflow',
        description: 'New entities, pages, permissions, API endpoints, and tests follow one repeatable pattern.',
        icon: Workflow,
    },
];

const workflowSteps = [
    'Model the domain entity and its permissions.',
    'Add admin CRUD using the shared resource patterns.',
    'Expose public pages or API endpoints only where the project needs them.',
    'Verify with Pest, build checks, and documentation updates.',
];

const architecturePanels = [
    {
        eyebrow: 'Public',
        title: 'Landing pages that explain the product',
        description: 'Guest pages should communicate value, trust, and direction without looking like the internal app.',
    },
    {
        eyebrow: 'Admin',
        title: 'A controlled workspace for operators',
        description: 'Users, roles, notifications, activity logs, exports, and future modules stay behind the authenticated shell.',
    },
    {
        eyebrow: 'Platform',
        title: 'Shared foundation under both surfaces',
        description: 'Laravel routes, Form Requests, RBAC, API versioning, and tests hold the whole system together.',
    },
];

const learningCards = [
    {
        title: 'Boilerplate roadmap',
        description: 'Read the full phase plan and understand what this starter already covers and what comes next.',
        href: handbookIndex.url({
            query: {
                document: 'roadmap',
            },
        }),
        icon: BookOpenText,
        label: 'Roadmap',
    },
    {
        title: 'Task checklist',
        description: 'Open the implementation tracker and see what is complete, pending, and still planned.',
        href: handbookIndex.url({
            query: {
                document: 'task-list',
            },
        }),
        icon: Workflow,
        label: 'Tasks',
    },
    {
        title: 'Laravel lessons',
        description: 'Read the code-level learning archive entry by entry, grouped like lessons instead of raw notes.',
        href: handbookIndex.url({
            query: {
                document: 'laravelbasics',
                lesson: 'entry-20-public-layout-and-landing-page-foundation',
            },
        }),
        icon: GraduationCap,
        label: 'Lessons',
    },
];
</script>

<template>
    <PublicLayout title="Public Website Foundation">
        <Head title="Public Website Foundation" />

        <section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-24">
            <div class="grid gap-12 lg:grid-cols-[1.15fr_0.85fr] lg:items-end">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-stone-900/10 bg-white/65 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-600 shadow-sm">
                        <Rocket class="size-3.5" />
                        Public-ready Laravel boilerplate
                    </div>

                    <h1 class="mt-6 max-w-4xl text-5xl leading-[0.96] font-semibold tracking-[-0.05em] text-stone-950 md:text-6xl lg:text-7xl">
                        Build one platform that can present like a brand and operate like a product.
                    </h1>

                    <p class="mt-6 max-w-2xl text-base leading-8 text-stone-700 md:text-lg">
                        {{ appName }} is no longer just an internal admin starter. It is designed to support a
                        polished public website, a controlled admin workspace, and reusable Laravel patterns for the
                        modules you add next.
                    </p>
                    <p v-if="settings.publicTagline" class="mt-3 max-w-2xl text-sm leading-7 text-stone-600 md:text-base">
                        {{ settings.publicTagline }}
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <Button
                            as-child
                            class="rounded-full bg-stone-900 px-6 text-stone-50 hover:bg-stone-800"
                        >
                            <Link :href="auth.user ? dashboard() : login()">
                                <span>{{ auth.user ? 'Open dashboard' : 'Open the app' }}</span>
                                <ArrowRight class="size-4" />
                            </Link>
                        </Button>
                        <Button
                            v-if="canRegister && !auth.user"
                            variant="outline"
                            as-child
                            class="rounded-full border-stone-900/15 bg-white/70 px-6"
                        >
                            <Link :href="register()">Create starter access</Link>
                        </Button>
                        <Button
                            variant="outline"
                            as-child
                            class="rounded-full border-stone-900/15 bg-white/70 px-6"
                        >
                            <Link :href="settings.publicCtaUrl ?? handbookIndex()">
                                <BookOpenText class="size-4" />
                                {{ settings.publicCtaLabel ?? 'Read the handbook' }}
                            </Link>
                        </Button>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 rounded-[2rem] bg-[linear-gradient(140deg,rgba(17,24,39,0.92),rgba(120,53,15,0.94))] shadow-[0_40px_120px_rgba(68,37,10,0.22)]" />
                    <div class="relative overflow-hidden rounded-[2rem] border border-stone-950/10 p-6 text-stone-50">
                        <div class="flex items-center justify-between text-[0.72rem] uppercase tracking-[0.22em] text-stone-300">
                            <span>Product surfaces</span>
                            <span>Guest + Admin</span>
                        </div>
                        <div class="mt-8 grid gap-4">
                            <div class="rounded-[1.5rem] bg-white/10 p-5 backdrop-blur">
                                <div class="flex items-center gap-3">
                                    <div class="rounded-2xl bg-white/12 p-3">
                                        <Globe2 class="size-5" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold">Public experience</div>
                                        <div class="mt-1 text-sm text-stone-300">Landing page, product narrative, and future public content.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-[1.5rem] bg-white/10 p-5 backdrop-blur">
                                <div class="flex items-center gap-3">
                                    <div class="rounded-2xl bg-white/12 p-3">
                                        <Fingerprint class="size-5" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold">Controlled operations</div>
                                        <div class="mt-1 text-sm text-stone-300">Auth, RBAC, notifications, audit logs, and admin CRUD live here.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-[1.5rem] bg-[#f0dfc4] p-5 text-stone-900">
                                <div class="text-[0.72rem] font-semibold uppercase tracking-[0.22em] text-stone-600">Current foundation</div>
                                <div class="mt-3 grid gap-2 text-sm">
                                    <div class="flex items-center justify-between rounded-xl bg-white/70 px-3 py-2">
                                        <span>RBAC + Admin CRUD</span>
                                        <span class="font-semibold">Ready</span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-xl bg-white/70 px-3 py-2">
                                        <span>Notifications + Audit</span>
                                        <span class="font-semibold">Ready</span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-xl bg-white/70 px-3 py-2">
                                        <span>Public website layer</span>
                                        <span class="font-semibold">Starting now</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-5 py-4 lg:px-8 lg:py-8">
            <div class="rounded-[2rem] border border-stone-900/10 bg-white/75 p-6 shadow-sm backdrop-blur lg:p-8">
                <div class="max-w-3xl">
                    <div class="text-[0.72rem] font-semibold uppercase tracking-[0.22em] text-stone-500">Learn inside the product</div>
                    <h2 class="mt-4 text-3xl font-semibold tracking-[-0.03em] text-stone-950">Use the front page as your learning entrance, not just as a marketing screen.</h2>
                    <p class="mt-4 text-sm leading-7 text-stone-700">
                        The handbook is now readable from the public side too. Open the roadmap, task list, or Laravel lesson archive directly from here while you learn the structure of your boilerplate.
                    </p>
                </div>

                <div class="mt-8 grid gap-5 lg:grid-cols-3">
                    <Link
                        v-for="card in learningCards"
                        :key="card.title"
                        :href="card.href"
                        class="group rounded-[1.6rem] border border-stone-900/10 bg-[#f6efe3] p-5 shadow-sm transition hover:-translate-y-1 hover:bg-stone-900 hover:text-stone-50"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <div class="inline-flex rounded-2xl bg-stone-900 p-3 text-stone-50 transition group-hover:bg-stone-50 group-hover:text-stone-900">
                                <component :is="card.icon" class="size-5" />
                            </div>
                            <span class="text-[0.72rem] font-semibold uppercase tracking-[0.2em] text-stone-500 transition group-hover:text-stone-300">
                                {{ card.label }}
                            </span>
                        </div>
                        <h3 class="mt-5 text-lg font-semibold tracking-[-0.02em]">{{ card.title }}</h3>
                        <p class="mt-3 text-sm leading-7 text-stone-700 transition group-hover:text-stone-300">
                            {{ card.description }}
                        </p>
                        <div class="mt-5 inline-flex items-center gap-2 text-sm font-medium">
                            Open guide
                            <ArrowRight class="size-4 transition group-hover:translate-x-1" />
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <section id="capabilities" class="mx-auto max-w-7xl px-5 py-8 lg:px-8 lg:py-12">
            <div class="grid gap-5 lg:grid-cols-3">
                <article
                    v-for="card in capabilityCards"
                    :key="card.title"
                    class="rounded-[1.75rem] border border-stone-900/10 bg-white/70 p-6 shadow-sm backdrop-blur"
                >
                    <div class="inline-flex rounded-2xl bg-stone-900 p-3 text-stone-50">
                        <component :is="card.icon" class="size-5" />
                    </div>
                    <h2 class="mt-5 text-xl font-semibold tracking-[-0.02em] text-stone-950">{{ card.title }}</h2>
                    <p class="mt-3 text-sm leading-7 text-stone-700">{{ card.description }}</p>
                </article>
            </div>
        </section>

        <section id="workflow" class="mx-auto max-w-7xl px-5 py-10 lg:px-8 lg:py-14">
            <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
                <div class="rounded-[2rem] bg-stone-900 p-8 text-stone-50 shadow-[0_30px_80px_rgba(28,25,23,0.28)]">
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/15 px-3 py-1 text-[0.72rem] uppercase tracking-[0.2em] text-stone-300">
                        <Blocks class="size-3.5" />
                        Project method
                    </div>
                    <h2 class="mt-5 text-3xl font-semibold tracking-[-0.03em]">Add new projects without rebuilding the platform every time.</h2>
                    <p class="mt-4 text-sm leading-7 text-stone-300">
                        This boilerplate is meant to absorb the repetitive work: shell, security, RBAC, admin UX, and public
                        foundations. Your next app should spend time on domain logic, not on rewriting infrastructure.
                    </p>
                </div>

                <div class="rounded-[2rem] border border-stone-900/10 bg-white/75 p-6 shadow-sm backdrop-blur">
                    <ol class="space-y-4">
                        <li
                            v-for="(step, index) in workflowSteps"
                            :key="step"
                            class="flex gap-4 rounded-[1.4rem] border border-stone-900/8 bg-[#f6efe3] px-4 py-4"
                        >
                            <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-stone-900 text-sm font-semibold text-stone-50">
                                {{ index + 1 }}
                            </div>
                            <p class="pt-1 text-sm leading-7 text-stone-700">{{ step }}</p>
                        </li>
                    </ol>
                </div>
            </div>
        </section>

        <section id="architecture" class="mx-auto max-w-7xl px-5 py-10 lg:px-8 lg:py-14">
            <div class="rounded-[2rem] border border-stone-900/10 bg-[#f3eadb] p-6 shadow-sm lg:p-8">
                <div class="max-w-2xl">
                    <div class="text-[0.72rem] font-semibold uppercase tracking-[0.22em] text-stone-500">Architecture split</div>
                    <h2 class="mt-4 text-3xl font-semibold tracking-[-0.03em] text-stone-950">One backend foundation, two intentional experiences.</h2>
                </div>
                <div class="mt-8 grid gap-5 lg:grid-cols-3">
                    <article
                        v-for="panel in architecturePanels"
                        :key="panel.title"
                        class="rounded-[1.6rem] bg-white/70 p-5 shadow-sm"
                    >
                        <div class="text-[0.72rem] font-semibold uppercase tracking-[0.2em] text-stone-500">{{ panel.eyebrow }}</div>
                        <h3 class="mt-3 text-lg font-semibold text-stone-950">{{ panel.title }}</h3>
                        <p class="mt-3 text-sm leading-7 text-stone-700">{{ panel.description }}</p>
                    </article>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ArrowRight, LayoutDashboard, LogIn, Menu, UserPlus } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { dashboard, home, login, register } from '@/routes';
import type { Auth } from '@/types';

withDefaults(defineProps<{
    title?: string;
}>(), {
    title: 'Home',
});

const page = usePage();
const auth = computed(() => page.props.auth as Auth);
const appName = computed(() => page.props.name as string ?? 'Laravel Boilerplate');
const canRegister = computed(() => page.props.canRegister as boolean ?? false);
const mobileMenuOpen = ref(false);

const primaryAction = computed(() => {
    if (auth.value.user) {
        return {
            href: dashboard(),
            label: 'Go to dashboard',
            icon: LayoutDashboard,
        };
    }

    return {
        href: login(),
        label: 'Enter the app',
        icon: ArrowRight,
    };
});
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,#fff0c2_0%,#f7f2e8_34%,#efe6d6_68%,#e7dcc9_100%)] text-stone-900">
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute top-[-8rem] left-[-5rem] h-72 w-72 rounded-full bg-amber-300/35 blur-3xl" />
            <div class="absolute top-20 right-[-7rem] h-80 w-80 rounded-full bg-emerald-300/20 blur-3xl" />
            <div class="absolute bottom-[-8rem] left-1/3 h-72 w-72 rounded-full bg-rose-300/20 blur-3xl" />
        </div>

        <header class="sticky top-0 z-30 border-b border-stone-900/10 bg-[#f6efe3]/85 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-5 py-4 lg:px-8">
                <Link :href="home()" class="flex min-w-0 items-center gap-3">
                    <div class="flex size-11 items-center justify-center rounded-2xl bg-stone-900 text-stone-50 shadow-sm">
                        <AppLogoIcon class="size-6 fill-current" />
                    </div>
                    <div class="min-w-0">
                        <div class="truncate text-[0.7rem] font-semibold uppercase tracking-[0.22em] text-stone-500">Public Layer</div>
                        <div class="truncate text-base font-semibold">{{ appName }}</div>
                    </div>
                </Link>

                <nav class="hidden items-center gap-7 text-sm font-medium text-stone-700 lg:flex">
                    <a href="#capabilities" class="transition hover:text-stone-950">Capabilities</a>
                    <a href="#workflow" class="transition hover:text-stone-950">Workflow</a>
                    <a href="#architecture" class="transition hover:text-stone-950">Architecture</a>
                </nav>

                <div class="hidden items-center gap-3 lg:flex">
                    <Button
                        v-if="!auth.user"
                        variant="ghost"
                        as-child
                        class="rounded-full text-stone-700 hover:bg-stone-900/5 hover:text-stone-950"
                    >
                        <Link :href="login()">
                            <LogIn class="size-4" />
                            Log in
                        </Link>
                    </Button>

                    <Button
                        v-if="!auth.user && canRegister"
                        as-child
                        class="rounded-full bg-stone-900 px-5 text-stone-50 hover:bg-stone-800"
                    >
                        <Link :href="register()">
                            <UserPlus class="size-4" />
                            Register
                        </Link>
                    </Button>

                    <Button
                        v-else
                        as-child
                        class="rounded-full bg-stone-900 px-5 text-stone-50 hover:bg-stone-800"
                    >
                        <Link :href="primaryAction.href">
                            <component :is="primaryAction.icon" class="size-4" />
                            {{ primaryAction.label }}
                        </Link>
                    </Button>
                </div>

                <Sheet v-model:open="mobileMenuOpen">
                    <SheetTrigger as-child>
                        <Button
                            variant="outline"
                            size="icon"
                            class="rounded-full border-stone-900/15 bg-white/70 lg:hidden"
                        >
                            <Menu class="size-5" />
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="right" class="w-80 border-l border-stone-900/10 bg-[#f6efe3]">
                        <SheetHeader>
                            <SheetTitle>{{ appName }}</SheetTitle>
                            <SheetDescription>Public navigation and entry points.</SheetDescription>
                        </SheetHeader>
                        <div class="mt-8 flex flex-col gap-3">
                            <a href="#capabilities" class="rounded-2xl px-4 py-3 text-sm font-medium text-stone-700 hover:bg-stone-900/5">Capabilities</a>
                            <a href="#workflow" class="rounded-2xl px-4 py-3 text-sm font-medium text-stone-700 hover:bg-stone-900/5">Workflow</a>
                            <a href="#architecture" class="rounded-2xl px-4 py-3 text-sm font-medium text-stone-700 hover:bg-stone-900/5">Architecture</a>
                        </div>
                        <div class="mt-8 flex flex-col gap-3">
                            <Button
                                v-if="!auth.user"
                                variant="outline"
                                as-child
                                class="justify-start rounded-2xl border-stone-900/10 bg-white/70"
                            >
                                <Link :href="login()" @click="mobileMenuOpen = false">
                                    <LogIn class="size-4" />
                                    Log in
                                </Link>
                            </Button>
                            <Button
                                v-if="!auth.user && canRegister"
                                as-child
                                class="justify-start rounded-2xl bg-stone-900 text-stone-50 hover:bg-stone-800"
                            >
                                <Link :href="register()" @click="mobileMenuOpen = false">
                                    <UserPlus class="size-4" />
                                    Register
                                </Link>
                            </Button>
                            <Button
                                v-else
                                as-child
                                class="justify-start rounded-2xl bg-stone-900 text-stone-50 hover:bg-stone-800"
                            >
                                <Link :href="primaryAction.href" @click="mobileMenuOpen = false">
                                    <component :is="primaryAction.icon" class="size-4" />
                                    {{ primaryAction.label }}
                                </Link>
                            </Button>
                        </div>
                    </SheetContent>
                </Sheet>
            </div>
        </header>

        <main>
            <slot />
        </main>

        <footer class="border-t border-stone-900/10 bg-[#f3eadb]">
            <div class="mx-auto grid max-w-7xl gap-8 px-5 py-10 lg:grid-cols-[1.5fr_1fr_1fr] lg:px-8">
                <div>
                    <div class="text-[0.72rem] font-semibold uppercase tracking-[0.24em] text-stone-500">Foundation</div>
                    <p class="mt-3 max-w-xl text-sm leading-7 text-stone-700">
                        A reusable Laravel platform with a public website layer, private admin shell, RBAC,
                        notifications, audit logs, and API baseline.
                    </p>
                </div>
                <div>
                    <div class="text-sm font-semibold text-stone-900">Public</div>
                    <div class="mt-3 space-y-2 text-sm text-stone-700">
                        <a href="#capabilities" class="block hover:text-stone-950">Capabilities</a>
                        <a href="#workflow" class="block hover:text-stone-950">Workflow</a>
                        <a href="#architecture" class="block hover:text-stone-950">Architecture</a>
                    </div>
                </div>
                <div>
                    <div class="text-sm font-semibold text-stone-900">Access</div>
                    <div class="mt-3 space-y-2 text-sm text-stone-700">
                        <Link :href="login()" class="block hover:text-stone-950">Log in</Link>
                        <Link v-if="!auth.user && canRegister" :href="register()" class="block hover:text-stone-950">Register</Link>
                        <Link v-else :href="dashboard()" class="block hover:text-stone-950">Dashboard</Link>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

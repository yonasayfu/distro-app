<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { update } from '@/routes/users/roles';
import type { ManagedUser, RoleOption } from '@/types';

type Props = {
    user: ManagedUser;
    roles: RoleOption[];
};

const props = defineProps<Props>();

const form = useForm<{
    roles: string[];
}>({
    roles: [...props.user.roles],
});

watch(
    () => props.user.roles,
    (roles) => {
        form.defaults({
            roles: [...roles],
        });
        form.roles = [...roles];
    },
);

const assignedRoles = computed(() => new Set(form.roles));

const toggleRole = (role: string, checked: boolean | 'indeterminate'): void => {
    const nextRoles = new Set(form.roles);

    if (checked === true) {
        nextRoles.add(role);
    } else {
        nextRoles.delete(role);
    }

    form.roles = [...nextRoles].sort((left, right) => left.localeCompare(right));
};

const submit = (): void => {
    form.put(update(props.user.id).url, {
        preserveScroll: true,
    });
};
</script>

<template>
    <section class="rounded-[1.5rem] border border-border/70 bg-card/85 p-5 shadow-sm backdrop-blur">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-lg font-semibold text-foreground">
                        {{ user.name }}
                    </h2>
                    <span
                        v-if="user.isCurrentUser"
                        class="rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-900 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100"
                    >
                        Current session
                    </span>
                    <span
                        class="rounded-full border border-border/70 bg-background/80 px-2.5 py-1 text-xs font-medium text-muted-foreground"
                    >
                        {{ user.emailVerifiedAt ? 'Verified' : 'Unverified' }}
                    </span>
                </div>

                <div class="space-y-1 text-sm leading-6 text-muted-foreground">
                    <p>{{ user.email }}</p>
                    <p v-if="user.roles.length > 0">
                        Current roles: {{ user.roles.join(', ') }}
                    </p>
                    <p v-else>No roles assigned yet.</p>
                    <p v-if="user.isCurrentUser">
                        Your own Admin role is protected here to avoid locking your current session out of the control panel.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <p
                    v-if="form.recentlySuccessful"
                    class="text-sm font-medium text-emerald-600 dark:text-emerald-400"
                >
                    Saved.
                </p>
                <Button
                    type="button"
                    :disabled="form.processing || !form.isDirty"
                    @click="submit"
                >
                    Save roles
                </Button>
            </div>
        </div>

        <InputError class="mt-4" :message="form.errors.roles" />

        <div class="mt-6 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            <label
                v-for="role in roles"
                :key="`${user.id}-${role.name}`"
                class="flex gap-3 rounded-2xl border border-border/70 bg-background/70 px-4 py-3 transition-colors hover:border-border hover:bg-accent/35"
            >
                <Checkbox
                    :checked="assignedRoles.has(role.name)"
                    :disabled="form.processing"
                    @update:checked="toggleRole(role.name, $event)"
                />

                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <Label class="text-sm font-medium text-foreground">
                            {{ role.label }}
                        </Label>
                        <code class="rounded bg-muted px-1.5 py-0.5 text-[11px] text-muted-foreground">
                            {{ role.usersCount }} users
                        </code>
                    </div>
                    <p class="mt-1 text-sm leading-5 text-muted-foreground">
                        Users with this role inherit the permissions currently attached to it.
                    </p>
                </div>
            </label>
        </div>
    </section>
</template>

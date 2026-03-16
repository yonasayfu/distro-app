<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { update as updateRole } from '@/routes/roles';
import type { ManagedRole, PermissionGroup } from '@/types';

type Props = {
    role: ManagedRole;
    permissionGroups: PermissionGroup[];
};

const props = defineProps<Props>();

const form = useForm<{
    permissions: string[];
}>({
    permissions: [...props.role.permissions],
});

watch(
    () => props.role.permissions,
    (permissions) => {
        form.defaults({
            permissions: [...permissions],
        });
        form.permissions = [...permissions];
    },
);

const assignedPermissions = computed(() => new Set(form.permissions));

const togglePermission = (
    permission: string,
    checked: boolean | 'indeterminate',
): void => {
    const nextPermissions = new Set(form.permissions);

    if (checked === true) {
        nextPermissions.add(permission);
    } else {
        nextPermissions.delete(permission);
    }

    form.permissions = [...nextPermissions].sort((left, right) =>
        left.localeCompare(right),
    );
};

const submit = (): void => {
    if (!props.role.editable) {
        return;
    }

    form.put(updateRole(props.role.id).url, {
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
                        {{ role.name }}
                    </h2>
                    <span
                        class="rounded-full border border-border/70 bg-background/80 px-2.5 py-1 text-xs font-medium text-muted-foreground"
                    >
                        {{ role.usersCount }} users
                    </span>
                    <span
                        v-if="!role.editable"
                        class="rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-900 dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-100"
                    >
                        System role
                    </span>
                </div>
                <p class="max-w-3xl text-sm leading-6 text-muted-foreground">
                    {{ role.editable
                        ? 'Changes here immediately affect route access, sidebar visibility, and future action-level checks for users assigned to this role.'
                        : 'The Admin role stays fully open so the boilerplate always keeps one stable recovery role with full access.' }}
                </p>
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
                    :disabled="form.processing || !form.isDirty || !role.editable"
                    @click="submit"
                >
                    Save permissions
                </Button>
            </div>
        </div>

        <InputError class="mt-4" :message="form.errors.permissions" />

        <div class="mt-6 grid gap-4 xl:grid-cols-2">
            <section
                v-for="group in permissionGroups"
                :key="`${role.id}-${group.key}`"
                class="rounded-2xl border border-border/70 bg-background/70 p-4"
            >
                <div class="mb-4">
                    <h3 class="text-sm font-semibold tracking-wide text-foreground uppercase">
                        {{ group.title }}
                    </h3>
                </div>

                <div class="space-y-3">
                    <label
                        v-for="permission in group.permissions"
                        :key="permission.name"
                        class="flex gap-3 rounded-xl border border-transparent px-3 py-3 transition-colors hover:border-border/80 hover:bg-accent/40"
                    >
                        <Checkbox
                            :checked="assignedPermissions.has(permission.name)"
                            :disabled="form.processing || !role.editable"
                            @update:checked="togglePermission(permission.name, $event)"
                        />
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <Label class="text-sm font-medium text-foreground">
                                    {{ permission.label }}
                                </Label>
                                <code class="rounded bg-muted px-1.5 py-0.5 text-[11px] text-muted-foreground">
                                    {{ permission.name }}
                                </code>
                            </div>
                            <p class="mt-1 text-sm leading-5 text-muted-foreground">
                                {{ permission.description }}
                            </p>
                        </div>
                    </label>
                </div>
            </section>
        </div>
    </section>
</template>

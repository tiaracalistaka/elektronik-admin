<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

defineOptions({
    layout: null,
});

const page = usePage<any>();
const errors = computed(() => page.props.errors as Record<string, string>);
const status = computed(() => page.props.status as string | undefined);
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 px-4 dark:from-slate-900 dark:to-slate-800">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="mb-8 text-center">
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-xl bg-primary text-primary-foreground shadow-lg">
                    <svg class="h-7 w-7" viewBox="0 0 40 40" fill="currentColor">
                        <rect x="8" y="8" width="24" height="24" rx="4" opacity="0.9"/>
                        <rect x="12" y="12" width="16" height="16" rx="2" fill="none" stroke="currentColor" stroke-width="2" opacity="0.6"/>
                        <rect x="14" y="4" width="3" height="6" rx="1" opacity="0.7"/>
                        <rect x="18.5" y="4" width="3" height="6" rx="1" opacity="0.7"/>
                        <rect x="23" y="4" width="3" height="6" rx="1" opacity="0.7"/>
                        <rect x="14" y="30" width="3" height="6" rx="1" opacity="0.7"/>
                        <rect x="18.5" y="30" width="3" height="6" rx="1" opacity="0.7"/>
                        <rect x="23" y="30" width="3" height="6" rx="1" opacity="0.7"/>
                        <rect x="4" y="14" width="6" height="3" rx="1" opacity="0.7"/>
                        <rect x="4" y="18.5" width="6" height="3" rx="1" opacity="0.7"/>
                        <rect x="4" y="23" width="6" height="3" rx="1" opacity="0.7"/>
                        <rect x="30" y="14" width="6" height="3" rx="1" opacity="0.7"/>
                        <rect x="30" y="18.5" width="6" height="3" rx="1" opacity="0.7"/>
                        <rect x="30" y="23" width="6" height="3" rx="1" opacity="0.7"/>
                        <circle cx="20" cy="20" r="3" opacity="0.4"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-foreground">Elektronik Store</h1>
                <p class="mt-1 text-sm text-muted-foreground">Admin Panel</p>
            </div>

            <!-- Card -->
            <div class="rounded-2xl border border-border bg-card p-8 shadow-xl">
                <h2 class="mb-6 text-center text-xl font-semibold text-foreground">Masuk ke Akun Anda</h2>

                <div v-if="status" class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                    {{ status }}
                </div>

                <Form
                    method="post"
                    :action="'/login'"
                    :reset-on-success="false"
                    v-slot="{ processing }"
                    class="flex flex-col gap-5"
                >
                    <div class="grid gap-2">
                        <Label for="email">Alamat Email</Label>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="admin@elektronik.com"
                        />
                        <InputError v-if="errors?.email" :message="errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">Kata Sandi</Label>
                        <PasswordInput
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi"
                        />
                        <InputError v-if="errors?.password" :message="errors.password" />
                    </div>

                    <div class="flex items-center">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-input text-primary focus:ring-primary"
                        />
                        <label for="remember" class="ml-2 text-sm text-muted-foreground">Ingat saya</label>
                    </div>

                    <Button type="submit" class="w-full" :disabled="processing">
                        <Spinner v-if="processing" />
                        Masuk
                    </Button>
                </Form>
            </div>

            <p class="mt-6 text-center text-xs text-muted-foreground">
                &copy; {{ new Date().getFullYear() }} Elektronik Store. Hak cipta dilindungi.
            </p>
        </div>
    </div>
</template>

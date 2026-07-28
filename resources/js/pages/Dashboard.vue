<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

router.replace('/dashboard');
</script>

<template>
    <Head title="Redirecting..." />
    <div class="flex h-screen items-center justify-center">
        <p class="text-muted-foreground">Redirecting...</p>
    </div>
</template>

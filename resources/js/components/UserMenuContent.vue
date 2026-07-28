<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { LogOut } from '@lucide/vue';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import UserInfo from '@/components/UserInfo.vue';
import { Button } from '@/components/ui/button';
import type { User } from '@/types';

type Props = {
    user: User;
};

defineProps<Props>();

const handleLogout = () => {
    router.post('/logout', {}, {
        onFinish: () => router.flushAll(),
    });
};
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuSeparator />
    <DropdownMenuItem class="gap-2 cursor-pointer" @click="handleLogout">
        <LogOut class="h-4 w-4" />
        Keluar
    </DropdownMenuItem>
</template>

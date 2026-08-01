<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 40 40"
        :class="className"
        v-bind="$attrs"
    >
        <!-- Circuit board / chip icon for Elektronik -->
        <rect x="8" y="8" width="24" height="24" rx="4" fill="currentColor" opacity="0.9"/>
        <rect x="12" y="12" width="16" height="16" rx="2" fill="none" stroke="currentColor" stroke-width="2" opacity="0.6"/>
        <!-- Pins -->
        <rect x="14" y="4" width="3" height="6" rx="1" fill="currentColor" opacity="0.7"/>
        <rect x="18.5" y="4" width="3" height="6" rx="1" fill="currentColor" opacity="0.7"/>
        <rect x="23" y="4" width="3" height="6" rx="1" fill="currentColor" opacity="0.7"/>
        <rect x="14" y="30" width="3" height="6" rx="1" fill="currentColor" opacity="0.7"/>
        <rect x="18.5" y="30" width="3" height="6" rx="1" fill="currentColor" opacity="0.7"/>
        <rect x="23" y="30" width="3" height="6" rx="1" fill="currentColor" opacity="0.7"/>
        <rect x="4" y="14" width="6" height="3" rx="1" fill="currentColor" opacity="0.7"/>
        <rect x="4" y="18.5" width="6" height="3" rx="1" fill="currentColor" opacity="0.7"/>
        <rect x="4" y="23" width="6" height="3" rx="1" fill="currentColor" opacity="0.7"/>
        <rect x="30" y="14" width="6" height="3" rx="1" fill="currentColor" opacity="0.7"/>
        <rect x="30" y="18.5" width="6" height="3" rx="1" fill="currentColor" opacity="0.7"/>
        <rect x="30" y="23" width="6" height="3" rx="1" fill="currentColor" opacity="0.7"/>
        <!-- Inner dot -->
        <circle cx="20" cy="20" r="3" fill="currentColor" opacity="0.4"/>
    </svg>
</template>

<script setup lang="ts">

import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        required: true
    },
    filled: {
        default: false
    }
});
</script>

<template>
    <Link
        :href="href"
        class="whitespace-nowrap rounded-full transition-colors py-0.5 px-2 border border-pink-500 mb-2 text-sm hover:bg-pink-500"
        :class="{'bg-pink-500': filled}">
        <slot></slot>
    </Link>
</template>

<style scoped>

</style>

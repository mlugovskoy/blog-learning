<script setup lang="ts">
import { onMounted, ref, watchEffect } from 'vue';
import { usePage } from '@inertiajs/vue3';

const flashShow = ref(false);
const $page = usePage();
const timeout = ref(null);

watchEffect(async () => {
    if ($page.props.flash?.message) {
        flashShow.value = true;

        clearTimeout(timeout.value);

        timeout.value = setTimeout(() => flashShow.value = false, 5000);
    }
});

onMounted(() => clearTimeout(timeout.value));
</script>

<template>
    <div v-if="flashShow"
         class="sticky top-4 transition-all max-w-48 text-center m-2 py-2 px-4 rounded-xl bg-indigo-600 z-50">
        {{ $page.props.flash.message }}
    </div>
</template>

<script setup lang="ts">
import { relativeDate } from '@/util/date';
import { router } from '@inertiajs/vue3';

const props = defineProps(['comment']);
const emit = defineEmits(['edit', 'delete']);
</script>

<template>
    <div class="sm:flex px-2 py-4">
        <div class="sm:flex-auto">
            <div class="markdown mt-1 prose prose-sm max-w-none" v-html="comment.html"></div>
            <p class="text-sm break-all">{{ comment.body }}</p>
            <span class="first-letter:uppercase block text-sm pt-1 text-neutral-400">
                By {{ comment.user.name }} {{ relativeDate(comment.created_at) }} before
            </span>
            <div class="flex space-x-3 empty:hidden">
                <form v-if="comment.can?.update" @submit.prevent="$emit('edit', comment.id)">
                    <button class="font-mono cursor-pointer text-xs hover:font-semibold">Edit</button>
                </form>
                <form v-if="comment.can?.delete" @submit.prevent="$emit('delete', comment.id)">
                    <button class="font-mono text-red-700 cursor-pointer text-xs hover:font-semibold">Delete</button>
                </form>
            </div>
        </div>
    </div>
</template>

<style>
.markdown strong,
.markdown blockquote,
.markdown h1,
.markdown h2,
.markdown h3,
.markdown a {
    color: white;
}
</style>

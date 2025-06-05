<script setup lang="ts">
import { relativeDate } from '@/util/date';
import { Link, router } from '@inertiajs/vue3';
import { HandThumbDownIcon, HandThumbUpIcon } from '@heroicons/vue/20/solid';

const props = defineProps(['comment']);
const emit = defineEmits(['edit', 'delete']);
</script>

<template>
    <div class="sm:flex px-2 py-4">
        <div class="sm:flex-auto">
            <div class="markdown mt-1 prose prose-sm max-w-none" v-html="comment.html"></div>
            <p class="text-sm break-all">{{ comment.body }}</p>
            <span class="first-letter:uppercase block text-sm pt-1 text-neutral-400">
                By {{ comment.user.name }} {{ relativeDate(comment.created_at) }} | <span class="text-pink-500">{{comment.likes_count}} likes</span>
            </span>
            <div class="flex space-x-3 empty:hidden">
                <div v-if="$page.props.auth.user">
                    <Link
                        v-if="comment.can.like"
                        preserve-scroll
                        class="cursor-pointer inline-block text-gray-700 hover:text-pink-500 transition-colors text-white rounded-full"
                        :href="route('likes.store', ['comment', comment.id])" method="post">
                        <HandThumbUpIcon class="size-4 inline-block mr-1" />
                        <span class="sr-only">Like Comment</span>
                    </Link>
                    <Link
                        v-else
                        preserve-scroll
                        class="cursor-pointer inline-block text-gray-700 hover:text-pink-500 transition-colors text-white rounded-full"
                        :href="route('likes.destroy', ['comment', comment.id])" method="delete">
                        <HandThumbDownIcon class="size-4 inline-block mr-1" />
                        <span class="sr-only">Unlike Comment</span>
                    </Link>
                </div>

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

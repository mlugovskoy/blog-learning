<script setup lang="ts">

import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import Container from '@/components/Container.vue';
import { computed, ref } from 'vue';
import Pagination from '@/components/Pagination.vue';
import Comment from '@/components/Comment.vue';
import { relativeDate } from '@/util/date';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { router, useForm } from '@inertiajs/vue3';
import { TextArea } from '@/components/ui/textarea';
import InputError from '@/components/InputError.vue';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import PageHeading from '@/components/PageHeading.vue';
import Pill from '@/components/Pill.vue';
import { Link } from '@inertiajs/vue3';
import { HandThumbUpIcon, HandThumbDownIcon } from '@heroicons/vue/20/solid';

const props = defineProps(['post', 'comments']);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: route('posts.index')
    },
    {
        title: props.post.title,
        href: ''
    }
];

const formattedDate = computed(() => relativeDate((props.post.created_at)));

const commentForm = useForm({
    body: ''
});

const addComment = () => commentForm.post(route('posts.comments.store', props.post.id), {
    preserveScroll: true,
    onSuccess: () => commentForm.reset()
});

const deleteComment = (commentId) => {
    if (!confirm('Are you sure you want to delete this comment?')) return;

    router.delete(route('comments.destroy', {
        comment: commentId,
        page: props.comments.data.length > 1 ?
            props.comments.meta.current_page :
            Math.max(props.comments.meta.current_page - 1, 1)
    }), {
        preserveScroll: true
    });
};

const commentTextAreaRef = ref(null);
const commentIdBeingEdited = ref(null);
const commentBeingEdit = computed(() => props.comments.data.find(comment => comment.id === commentIdBeingEdited.value));
const editComment = (commentId) => {
    commentIdBeingEdited.value = commentId;
    commentForm.body = commentBeingEdit.value?.body;
    commentTextAreaRef.value?.focus();
};

const cancelEditComment = () => {
    commentIdBeingEdited.value = null;
    commentForm.reset();
};

const updateComment = () => commentForm.put(route('comments.update', {
    comment: commentIdBeingEdited.value,
    page: props.comments.meta.current_page
}), {
    onSuccess: cancelEditComment,
    preserveScroll: true
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <div>
                <Pill :href="route('posts.index', {topic: post.topic.slug})">{{ post.topic.name }}</Pill>
            </div>
            <PageHeading class="mt-2">{{ post.title }}</PageHeading>
            <span class="text-sm text-neutral-400 block">{{ formattedDate }} ago by {{ post.user.name }}</span>

            <div class="mt-4">
                <span class="text-pink-500 font-bold">{{ post.likes_count }} likes</span>

                <div class="mt-2" v-if="$page.props.auth.user">
                    <Link
                        v-if="post.can.like"
                        class="cursor-pointer inline-block bg-indigo-600 hover:bg-pink-500 transition-colors text-white py-1.5 px-3 rounded-full"
                        :href="route('likes.store', ['post', post.id])" method="post">
                        <HandThumbUpIcon class="size-4 inline-block mr-1" />
                        Like Post
                    </Link>
                    <Link
                        v-else
                        class="cursor-pointer inline-block bg-indigo-600 hover:bg-pink-500 transition-colors text-white py-1.5 px-3 rounded-full"
                        :href="route('likes.destroy', ['post', post.id])" method="delete">
                        <HandThumbDownIcon class="size-4 inline-block mr-1" />
                        Unlike Post
                    </Link>
                </div>
            </div>

            <article class="markdown mt-6 prose prose-sm max-w-none text-white" v-html="post.html"></article>

            <div class="mt-12">
                <h2 class="text-xl font-semibold">Comments</h2>

                <form v-if="$page.props.auth.user"
                      @submit.prevent="() => commentIdBeingEdited ? updateComment() : addComment()">
                    <div class="mt-3">
                        <Label class="sr-only" for="body">Comment</Label>
                        <MarkdownEditor ref="commentTextAreaRef" editorClass="min-h-[160px]" id="body"
                                        v-model="commentForm.body"
                                        placeholder="Speak your mind Spock..."></MarkdownEditor>
                        <InputError class="mt-1" :message="commentForm.errors.body" />
                    </div>

                    <Button type="submit" class="mt-3" :disabled="commentForm.processing"
                            v-text="commentIdBeingEdited ? 'Update comment' : 'Add Comment'"></Button>
                    <Button v-if="commentIdBeingEdited" @click="cancelEditComment" class="ml-2">Cancel</Button>
                </form>

                <ul class="divide-y mt-4">
                    <Comment v-for="comment in comments.data" :key="comment.id" :comment="comment" @edit="editComment"
                             @delete="deleteComment" />
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']" />
            </div>
        </Container>
    </AppLayout>
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

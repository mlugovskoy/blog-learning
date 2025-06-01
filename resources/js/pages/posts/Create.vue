<script setup lang="ts">

import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import Container from '@/components/Container.vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { TextArea } from '@/components/ui/textarea';
import { Input } from '@/components/ui/input';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import axios from 'axios';
import PageHeading from '@/components/PageHeading.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: route('posts.index')
    }
];

const props = defineProps(['topics']);

const form = useForm({
    title: '',
    body: '',
    topic_id: props.topics[0].id
});

const createPost = () => form.post(route('posts.store'));

const isInProduction = () => import.meta.env.PROD;

const autofill = async () => {
    if (isInProduction()) {
        return;
    }

    const response = await axios.get('/local/post-content');

    form.title = response.data.title;
    form.body = response.data.body;
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <PageHeading>Create a Post</PageHeading>

            <form @submit.prevent="createPost" class="mt-6">
                <div>
                    <Label for="title" class="sr-only">Title</Label>
                    <Input id="title" class="w-full" v-model="form.title" placeholder="Give it a great title..." />
                    <InputError :message="form.errors.title" class="mt-1" />
                </div>

                <div class="mt-3">
                    <Label for="topic_id" class="mb-1">Select a Topic</Label>
                    <select v-model="form.topic_id" id="topic_id" class="border-input flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none md:text-sm">
                        <option v-for="topic in topics" :key="topic.id" :value="topic.id" class="text-primary-foreground">
                            {{ topic.name }}
                        </option>
                    </select>
                    <InputError :message="form.errors.topic_id" class="mt-1" />
                </div>

                <div class="mt-3">
                    <Label for="body" class="sr-only">Body</Label>
                    <MarkdownEditor v-model="form.body">
                        <template #toolbar="{editor}">
                            <li v-if="!isInProduction()">
                                <button @click="autofill"
                                        type="button"
                                        title="Autofill"
                                        class="px-3 py-2 shadow-xs transition-all">
                                    <i class="ri-article-line"></i>
                                </button>
                            </li>
                        </template>
                    </MarkdownEditor>
                    <InputError :message="form.errors.title" class="mt-1" />
                </div>

                <div class="mt-3">
                    <Button type="submit" class="cursor-pointer">Create Post</Button>
                </div>
            </form>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>

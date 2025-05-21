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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: route('posts.index')
    }
];

const form = useForm({
    title: '',
    body: ''
});

const createPost = () => form.post(route('posts.store'));
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <h1 class="text-2xl font-bold">Create a Post</h1>

            <form @submit.prevent="createPost" class="mt-6">
                <div>
                    <Label for="title" class="sr-only">Title</Label>
                    <Input id="title" class="w-full" v-model="form.title" placeholder="Give it a great title..." />
                    <InputError :message="form.errors.title" class="mt-1" />
                </div>

                <div class="mt-3">
                    <Label for="body" class="sr-only">Body</Label>
                    <TextArea id="body" v-model="form.body" rows="25" />
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

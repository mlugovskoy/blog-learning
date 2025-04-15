<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import Container from '@/components/Container.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: route('posts.index')
    }
];

defineProps(['posts']);
</script>

<template>
    <Head title="Posts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <ul class="divide-y">
                <li class="px-2 py-4" v-for="post in posts" :key="post.id">
                    <span class="font-bold text-sm">{{ post.title }}</span>
                </li>
            </ul>
        </Container>
    </AppLayout>
</template>

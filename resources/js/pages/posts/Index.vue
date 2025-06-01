<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import Container from '@/components/Container.vue';
import Pagination from '@/components/Pagination.vue';
import { computed } from 'vue';
import { formatDistance, parseISO } from 'date-fns';
import { relativeDate } from '@/util/date';
import PageHeading from '@/components/PageHeading.vue';
import Pill from '@/components/Pill.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: route('posts.index')
    }
];

defineProps(['posts', 'topics', 'selectedTopic']);

const formattedDate = (post) => {
    return relativeDate(post.created_at);
};
</script>

<template>
    <Head title="Posts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <div>
                <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All posts'" />
                <p v-if="selectedTopic" class="mt-1 text-neutral-400 text-sm">{{ selectedTopic.description }}</p>
                <menu class="flex space-x-1 mt-4 overflow-x-auto pb-2 pt-1">
                    <li><Pill :href="route('posts.index')">All Posts</Pill></li>
                    <li v-for="topic in topics" :key="topic.id">
                        <Pill :filled="topic.id === selectedTopic?.id" :href="route('posts.index', {topic: topic.slug})">{{ topic.name }}</Pill>
                    </li>
                </menu>
            </div>
            <ul class="divide-y mt-4">
                <li v-for="post in posts.data" :key="post.id"
                    class="flex justify-between items-baseline flex-col md:flex-row">
                    <Link :href="post.routes.show" class="group block px-2 py-4">
                        <span class="font-bold text-sm transition-all group-hover:text-indigo-400">
                            {{ post.title }}
                        </span>
                        <span class="text-sm pt-1 text-neutral-400 block">
                            {{ formattedDate(post) }} ago by {{ post.user.name }}
                        </span>
                    </Link>
                    <Pill :href="route('posts.index', {topic: post.topic.slug})">{{ post.topic.name }}</Pill>
                </li>
            </ul>
            <Pagination :meta="posts.meta" />
        </Container>
    </AppLayout>
</template>

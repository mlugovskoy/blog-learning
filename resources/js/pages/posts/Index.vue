<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import Container from '@/components/Container.vue';
import Pagination from '@/components/Pagination.vue';
import { computed } from 'vue';
import { formatDistance, parseISO } from 'date-fns';
import { relativeDate } from '@/util/date';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: route('posts.index')
    }
];

defineProps(['posts']);

const formattedDate = (post) => {
    return relativeDate(post.created_at);
};
</script>

<template>
    <Head title="Posts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id">
                    <Link :href="post.routes.show" class="group block px-2 py-4">
                        <span class="font-bold text-sm transition-all group-hover:text-indigo-400">
                            {{ post.title }}
                        </span>
                        <span class="text-sm pt-1 text-neutral-400 block">
                            {{ formattedDate(post) }} ago by {{ post.user.name }}
                        </span>
                    </Link>
                </li>
            </ul>
            <Pagination :meta="posts.meta" />
        </Container>
    </AppLayout>
</template>

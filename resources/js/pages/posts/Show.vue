<script setup lang="ts">

import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import Container from '@/components/Container.vue';
import { computed } from 'vue';
import { formatDistance, parseISO } from 'date-fns';

const props = defineProps(['post']);

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

const formattedDate = computed(() => formatDistance(parseISO(props.post.created_at), new Date()));
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <h1 class="text-2xl font-bold">{{ post.title }}</h1>
            <span class="text-sm text-neutral-400 block">{{ formattedDate }} ago by {{ post.user.name }}</span>
            <article class="mt-6">
                <pre class="whitespace-pre-wrap font-sans">{{ post.body }}</pre>
            </article>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>

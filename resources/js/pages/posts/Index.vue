<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import Container from '@/components/Container.vue';
import Pagination from '@/components/Pagination.vue';
import { computed } from 'vue';
import { formatDistance, parseISO } from 'date-fns';
import { relativeDate } from '@/util/date';
import PageHeading from '@/components/PageHeading.vue';
import Pill from '@/components/Pill.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: route('posts.index')
    }
];

const props = defineProps(['posts', 'topics', 'selectedTopic', 'query']);

const formattedDate = (post) => {
    return relativeDate(post.created_at);
};

const searchForm = useForm({
    query: props.query,
    page: 1
});

const page = usePage();

const search = () => searchForm.get(page.url);

const clearSearch = () => {
    searchForm.query = '';
    search();
};
</script>

<template>
    <Head title="Posts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <div>
                <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All posts'" />
                <p v-if="selectedTopic" class="mt-1 text-neutral-400 text-sm">{{ selectedTopic.description }}</p>
                <menu class="flex space-x-1 max-w-[1600px] mt-4 overflow-x-auto pb-2 pt-1">
                    <li>
                        <Pill :href="route('posts.index', {query: searchForm.query})">All Posts</Pill>
                    </li>
                    <li v-for="topic in topics" :key="topic.id">
                        <Pill :filled="topic.id === selectedTopic?.id"
                              :href="route('posts.index', {topic: topic.slug, query: searchForm.query})">
                            {{ topic.name }}
                        </Pill>
                    </li>
                </menu>
                <form @submit.prevent="search" class="mt-4">
                    <div>
                        <Label for="query">Search</Label>
                        <div class="space-x-2 flex mt-1">
                            <Input v-model="searchForm.query" class="w-full" id="query" />
                            <Button class="cursor-pointer" type="submit">Search</Button>
                            <Button class="cursor-pointer bg-red-500 hover:bg-red-600 transition-colors rounded px-3" v-if="searchForm.query" @click="clearSearch">Clear</Button>
                        </div>
                    </div>
                </form>
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

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Paginator from "@/Components/paginator.vue";
import {Link} from '@inertiajs/vue3'
import {formatDistance, parseISO} from "date-fns";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";

defineProps(['posts', 'selectedTopic', 'topics'])

const formatDate = (post) => {
    return formatDistance(parseISO(post.created_at), new Date(), {addSuffix: true});
}
</script>

<template>
    <AppLayout>
        <Container>
            <Link :href="route('posts.index')" v-if="selectedTopic" class="underline text-blue-600">
                Back to all posts
            </Link>
            <menu class="flex justify-between flex-grow-0 my-4">
                <li v-for="topic in topics" :key="topic.id">
                    <Pill :href="route('posts.index', {topicSlug: topic.slug})" :is-active="selectedTopic ? topic.id === selectedTopic.id : false">
                        {{topic.name}}
                    </Pill>
                </li>
            </menu>
            <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All posts' "></PageHeading>
            <p v-if="selectedTopic" class="mt-1 text-gray-500 text-sm">{{ selectedTopic.description }}</p>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id"
                    class="hover:bg-amber-200 p-4 transition-all duration-200 flex flex-col sm:flex-row sm:justify-between sm:items-center ">
                    <Link :href="post.show_post_url">
                        <span class="block">{{ post.title }}</span>
                        <span class="text-gray-500 text-sm py-2 block">{{ formatDate(post) }} by {{
                                post.user.name
                            }}</span>
                    </Link>
                    <Pill :href="route('posts.index', {topicSlug: post.topic.slug})">
                        {{ post.topic.name }}
                    </Pill>
                </li>
            </ul>
            <Paginator :meta="posts.meta" only="posts"></Paginator>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>

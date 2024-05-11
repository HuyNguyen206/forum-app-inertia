<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Paginator from "@/Components/paginator.vue";
import {Link} from '@inertiajs/vue3'
import {formatDistance, parseISO} from "date-fns";
import PageHeading from "@/Components/PageHeading.vue";

defineProps(['posts', 'selectedTopic'])

const formatDate = (post) => {
    return formatDistance(parseISO(post.created_at), new Date());
}
</script>

<template>
    <AppLayout>
        <Container>
            <Link :href="route('posts.index')" v-if="selectedTopic" class="underline text-blue-600">
                Back to all posts
            </Link>

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
                    <Link :href="route('posts.index', {topicSlug: post.topic.slug})"
                          class="font-bold rounded-full px-4 py-2 hover:bg-pink-500 hover:text-white transition border-pink-500 border-2 mt-2 inline-block self-start">
                        {{ post.topic.name }}
                    </Link>
                </li>
            </ul>
            <Paginator :meta="posts.meta" only="posts"></Paginator>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>

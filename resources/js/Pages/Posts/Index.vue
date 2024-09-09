<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Paginator from "@/Components/paginator.vue";
import {Link, useForm} from '@inertiajs/vue3'
import {formatDistance, parseISO} from "date-fns";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";
import TextInput from "@/Components/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps(['posts', 'selectedTopic', 'topics', 'search'])

const formatDate = (post) => {
    return formatDistance(parseISO(post.created_at), new Date(), {addSuffix: true});
}

const searchForm = useForm({
    search: props.search
})

const search = () => searchForm.get(route('posts.index', props.selectedTopic ? props.selectedTopic.slug : ''))

const reset = function () {
    searchForm.search = '';
    search()
}
</script>

<template>
    <AppLayout>
        <Container>
            <Link :href="route('posts.index', {search: props.search})" v-if="selectedTopic" class="underline text-blue-600">
                Back to all posts
            </Link>
            <menu class="flex justify-between flex-grow-0 my-4">
                <li v-for="topic in topics" :key="topic.id">
                    <Pill :href="route('posts.index', {topicSlug: topic.slug, search: props.search})" :is-active="selectedTopic ? topic.id === selectedTopic.id : false">
                        {{topic.name}}
                    </Pill>
                </li>
            </menu>

                <form @submit.prevent="search">
                    <div>
                        <InputLabel for="query">Search</InputLabel>
                        <div class="flex space-x-2 mt-1">
                            <TextInput v-model="searchForm.search" class="mt-1 w-full" id="query"/>
                            <div class="flex">
                            <SecondaryButton type="submit">Search</SecondaryButton>
                            <SecondaryButton v-if="searchForm.search" type="button" @click.prevent="reset">Clear</SecondaryButton>
                            </div>
                        </div>
                    </div>
                </form>

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
                        <span class="text-pink-500 font-bold">  {{  post.likes_count_label }}</span>
                        <Link v-if="$page.props.auth.user" hover:text-blue-500 transition :href="route('likes.toggle', ['post', post.id])" method="patch">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"  :class="{'text-blue-500 font-weight': post.is_like }">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                            </svg>
                        </Link>
                    </Link>
                    <Pill :href="route('posts.index', {topicSlug: post.topic.slug})">
                        {{ post.topic.name }}
                    </Pill>
                </li>
            </ul>
            <Paginator :meta="posts.meta" :only="['posts']"></Paginator>
        </Container>
    </AppLayout>
</template>



<style scoped>

</style>

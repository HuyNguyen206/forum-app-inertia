<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Paginator from "@/Components/paginator.vue";
import {Link} from '@inertiajs/vue3'
import {formatDistance, parseISO} from "date-fns";

defineProps(['posts'])

const formatDate = (post) => {
    return formatDistance(parseISO(post.created_at), new Date());
}
</script>

<template>
    <AppLayout>
        <Container>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id" class="hover:bg-amber-200 p-4 transition-all duration-200">
                    <Link :href="post.show_post_url" >
                        <span class="block">{{ post.title }}</span>
                        <span class="text-gray-500 text-sm py-2 block">{{ formatDate(post) }} by {{ post.user.name }}</span>
                    </Link>
                </li>
            </ul>
            <Paginator :meta="posts.meta" only="posts"></Paginator>
        </Container>

    </AppLayout>
</template>

<style scoped>

</style>

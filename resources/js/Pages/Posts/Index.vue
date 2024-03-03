<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Paginator from "@/Components/paginator.vue";
import {Link} from '@inertiajs/vue3'
defineProps(['posts'])

</script>

<template>
    <AppLayout>
        <Container>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id" class="p-4">
                    <Link :href="route('posts.show', post.id)">{{ post.title }}</Link>
                </li>
            </ul>
            <Paginator :meta="posts.meta"></Paginator>
        </Container>

    </AppLayout>
</template>

<style scoped>

</style>

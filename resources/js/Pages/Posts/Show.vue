<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Paginator from "@/Components/paginator.vue";

defineProps(['post'])

</script>

<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="font-bold text-2xl pb-2">{{post.title}}</h1>
            <span class="text-gray-500 text-sm py-2 inline-block">{{post.created_at_readable}}</span>
            <article>
                {{post.body}}
            </article>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>

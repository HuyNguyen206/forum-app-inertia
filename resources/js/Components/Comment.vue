<script setup>
defineProps(['comment'])
</script>

<template>
    <div class="flex gap-2">
        <img class="w-10 h-10" :src="comment.user.profile_photo_url" alt="">
        <div>
            <h3 class="text-lg font-bold">{{ comment.user.name }}</h3>
            <p class="text-gray-700 text-sm mb-2">Posted on {{ comment.user.created_at }}</p>
            <p class="text-gray-700">{{ comment.body }}
            </p>
        </div>
    </div>
</template>

<style scoped>

</style>

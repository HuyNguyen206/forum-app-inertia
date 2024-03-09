<script setup>
import {router} from "@inertiajs/vue3";

const props = defineProps(['comment'])

const deleteComment = () => {
  router.delete(route('comments.destroy', props.comment), {preserveScroll: true})
}
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
        <button v-if="comment.is_owner" class="px-4 py-2 rounded  bg-red-600 text-white" type="button" @click="deleteComment">Delete</button>
</template>

<style scoped>

</style>

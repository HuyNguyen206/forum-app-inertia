<script setup>

const props = defineProps({
    comment:{
        type:Object,
        required:true
    }
})

const emit = defineEmits(['delete', 'edit'])
</script>

<template>
    <div class="flex gap-2">
        <img class="w-10 h-10" :src="comment.user.profile_photo_url" alt="">
        <div>
            <h3 class="text-lg font-bold">{{ comment.user.name }}</h3>
            <p class="text-gray-700 text-sm mb-2">Posted on {{ comment.created_at }}</p>
            <p class="text-gray-700">{{ comment.body }}
            </p>
        </div>
    </div>
    <div class="flex gap-1">
        <button v-if="comment.can?.update" class="px-4 py-2 rounded  bg-amber-600 text-white" @click="$emit('edit', comment.id)">Edit</button>
        <button v-if="comment.can?.delete" class="px-4 py-2 rounded  bg-red-600 text-white" type="button" @click="$emit('delete', comment.id)">Delete</button>
    </div>
</template>

<style scoped>

</style>

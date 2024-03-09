<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Paginator from "@/Components/paginator.vue";
import {computed} from "vue";
import {parseISO, formatDistance} from "date-fns";
import Comment from "@/Components/Comment.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

const props = defineProps(['post', 'comments'])

const formatedDate = computed(() => formatDistance(parseISO(props.post.created_at), new Date()))

const commentForm = useForm({
    body: ''
})

const addComment = () => commentForm.post(
    route('posts.comments.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset()
        }
    })
</script>

<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="font-bold text-2xl pb-2">{{post.title}} </h1>
            <span class="text-gray-500 text-sm py-2 inline-block">{{formatedDate}} by {{post.user.name}}</span>
            <article>
                <pre class="whitespace-pre-wrap">
                    {{post.body}}
                </pre>
            </article>
            <div class="comments">
                <div class="bg-gray-100 p-6">
                    <h2 class="text-lg font-bold mb-4">Comments ({{props.comments.meta.total}})</h2>
                    <div class="flex flex-col space-y-4">
                        <div v-for="comment in props.comments.data" :key="comment.id" class="bg-white p-4 rounded-lg shadow-md">
                              <Comment :comment="comment"></Comment>
                        </div>
                        <Paginator :meta="props.comments.meta" only="comments"></Paginator>

                        <form @submit.prevent="addComment" v-if="$page.props.auth.user" class="bg-white p-4 rounded-lg shadow-md">
                            <h3 class="text-lg font-bold mb-2">Add a comment</h3>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2" for="comment">
                                    Comment
                                </label>
                                <textarea
                                    v-model="commentForm.body"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="comment" rows="3" placeholder="Enter your comment"></textarea>
                            <InputError :message="commentForm.errors.body"></InputError>
                            </div>
                            <button
                                :disabled="commentForm.processing"
                                class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                type="submit">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
<!--                <div v-for="comment in props.comments.data" class="comment">-->
<!--                    {{comment.body}}-->
<!--                </div>-->
            </div>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Paginator from "@/Components/paginator.vue";
import {computed, ref} from "vue";
import {formatDistance, parseISO} from "date-fns";
import Comment from "@/Components/Comment.vue";
import {router, useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import {useConfirm} from "@/Utilities/Composable/useConfirm.js";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";

const props = defineProps(['post', 'comments'])

const formatedDate = computed(() => formatDistance(parseISO(props.post.created_at), new Date()))

const commentForm = useForm({
    body: ''
})

const commentEditId = ref(null);

const bodyComment = ref(null);

const {confirmation} = useConfirm()
const processComment = async () => {
    if (!commentEditId.value) {
        commentForm.post(
            route('posts.comments.store', props.post.id), {
                preserveScroll: true,
                onSuccess: () => {
                    commentForm.reset()
                }
            })

        return
    }
    if (! await confirmation('Are you sure?', 'Update comment?')) {
        bodyComment.value?.focus()
        console.log(123)
        return;
    }
    bodyComment.value?.focus()

    commentForm.patch(
        route('comments.update', {comment: commentEditId.value, page: props.comments.meta.current_page}), {
            preserveScroll: true,
            onSuccess: () => {
                cancelEditCommentMode();
            }
        })
}

const deleteComment = async (commentId) => {
    if (await confirmation('Are you sure', 'Delete this comment')) {
        router.delete(route('comments.destroy', {
            page: props.comments.meta.current_page,
            comment: commentId
        }), {preserveScroll: true})
    }
}

const editComment = (commentId) => {
    commentForm.body = props.comments.data.find((comment) => {
        return comment.id === commentId
    }).body
    commentEditId.value = commentId
    bodyComment.value?.focus()
}

const cancelEditCommentMode = () => {
    commentForm.reset()
    commentEditId.value = null
    bodyComment.value?.focus()
}
</script>

<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="font-bold text-2xl pb-2 capitalize">{{ post.title }} </h1>
            <span class="text-gray-500 text-sm py-2 inline-block">{{ formatedDate }} by {{ post.user.name }}</span>
            <article>
                <article class="prose prose-zinc prose-md nax-w-none" v-html="post.body_html">
                </article>
            </article>
            <div class="comments">
                <div class="bg-gray-100 p-6">
                    <h2 class="text-lg font-bold mb-4">Comments ({{ props.comments.meta.total }})</h2>
                    <div class="flex flex-col space-y-4">
                        <div v-for="comment in props.comments.data" :key="comment.id"
                             class="bg-white p-4 rounded-lg shadow-md">
                            <Comment :comment="comment" @edit="editComment" @delete="deleteComment"></Comment>
                        </div>
                        <Paginator :meta="props.comments.meta" only="comments"></Paginator>

                        <form @submit.prevent="processComment" v-if="$page.props.auth.user"
                              class="bg-white p-4 rounded-lg shadow-md">
                            <h3 class="text-lg font-bold mb-2">Add a comment</h3>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2" for="comment">
                                    Comment
                                </label>
                                <MarkdownEditor ref="bodyComment" v-model="commentForm.body" editorClass="min-h-[160px]" placeholder="Speak your mind"></MarkdownEditor>
                                <InputError :message="commentForm.errors.body"></InputError>
                            </div>
                            <div class="flex space-x-2">
                                <button
                                    :disabled="commentForm.processing"
                                    class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    type="submit">
                                    {{ commentEditId ? 'Update comment' : 'Submit' }}
                                </button>
                                <button @click="cancelEditCommentMode" v-if="commentEditId"
                                        class="bg-black hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Cancel
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>

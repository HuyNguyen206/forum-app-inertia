<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Paginator from "@/Components/paginator.vue";
import {computed, ref} from "vue";
import {formatDistance, parseISO} from "date-fns";
import Comment from "@/Components/Comment.vue";
import {Head, Link, router, useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import {useConfirm} from "@/Utilities/Composable/useConfirm.js";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";

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
            page: props.comments.data.length === 1 ? Math.max(props.comments.meta.current_page - 1, 1) : props.comments.meta.current_page,
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
    <Head>
        <link rel="canonical" :href="props.post.show_post_url_without_query_string">
    </Head>
    <AppLayout :title="post.title">
        <Container>
            <Pill :href="route('posts.index', {topicSlug: post.topic.slug})">{{post.topic.name}}</Pill>
            <div class="text-pink-500 font-bold my-2">  {{  post.likes_count_label }}</div>
            <Link  v-if="$page.props.auth.user" hover:text-blue-500 transition :href="route('likes.toggle', ['post', post.id])" method="patch">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"  :class="{'text-blue-500 font-weight': post.is_like }">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                </svg>
            </Link>
            <PageHeading class="mt-2">{{post.title}}</PageHeading>
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
                        <Paginator :meta="props.comments.meta" :only='["comments"]'></Paginator>

                        <form @submit.prevent="processComment" v-if="$page.props.auth.user"
                              class="bg-white p-4 rounded-lg shadow-md">
                            <h3 class="text-lg font-bold mb-2">Add a comment</h3>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2" for="comment">
                                    Comment
                                </label>
                                <MarkdownEditor ref="bodyComment" v-model="commentForm.body" editorClass="!min-h-[160px]" placeholder="Speak your mind"></MarkdownEditor>
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

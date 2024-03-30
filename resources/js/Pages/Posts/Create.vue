<script setup>
import {useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";

const form = useForm({
    title: "",
    body: "",
})

const createPost = () => form.post(route('posts.store'))

</script>

<template>
    <AppLayout title="Create a post">
        <Container>
            <h1 class="font-bold text-2xl pb-2">Create post </h1>

            <form @submit.prevent="createPost">
                <div>
                    <InputLabel for="name" value="Name" class="sr-only"/>
                    <TextInput
                        id="name"
                        v-model="form.title"
                        type="text"
                        class="mt-1 block w-full"
                        autofocus
                    />
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="body" value="Body" class="sr-only" />
                    <MarkdownEditor v-model="form.body"></MarkdownEditor>
                    <InputError :message="form.errors.body" class="mt-2" />

                </div>

                <PrimaryButton type="submit" class="mt-5">Submit</PrimaryButton>
            </form>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>

<script setup>
import {EditorContent, useEditor} from "@tiptap/vue-3";
import {StarterKit} from "@tiptap/starter-kit";
import {watch} from "vue";
import {Markdown} from "tiptap-markdown";

const editor = useEditor({
    extensions: [
        StarterKit,
        Markdown
    ],
    editorProps: {
        attributes: {
            class: ' min-h-[512px] mt-4 prose prose-sm max-w-none py-1.5 px-3 border-gray-300 bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm',
        },
    },
    onUpdate: () => emit('update:modelValue', editor.value?.storage.markdown.getMarkdown())
})

const props = defineProps({
    modelValue: ''
})

const emit = defineEmits(['update:modelValue'])

watch(() => props.modelValue, (value) => {
    if (value === editor.value?.storage.markdown.getMarkdown()) {
        return;
    }

    editor.value?.commands.setContent(value)
}, {immediate: true})
</script>

<template>
<div class="">
    <EditorContent :editor="editor">

    </EditorContent>
</div>
</template>

<style scoped>

</style>

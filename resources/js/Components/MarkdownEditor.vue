<script setup>
import {EditorContent, useEditor} from "@tiptap/vue-3";
import {StarterKit} from "@tiptap/starter-kit";
import {watch} from "vue";
import {Markdown} from "tiptap-markdown";
import 'remixicon/fonts/remixicon.css'
import {Link} from "@tiptap/extension-link";

const editor = useEditor({
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [2, 3, 4, 5, 6]
            },
            code: false,
            codeBlock: false,
        }),
        Markdown,
        Link
    ],
    editorProps: {
        attributes: {
            class: 'min-h-[512px] mt-4 prose prose-sm max-w-none py-1.5 px-3 border-gray-300',
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

const promptUserForHref = () => {
    if (editor.value?.isActive('link')) {
        editor.value?.chain().focus().unsetLink().run()
        return;
    }

    const href = prompt('Where do you want to link to?')

    if (!href) {
        return editor.value?.chain().focus().run()
    }

    return editor.value?.chain().focus().setLink({href}).run()
}
</script>

<template>
    <div v-if="editor" class="bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <menu type="toolbar" class="flex divide-x border-b">
            <li>
                <button
                    @click="() => editor.chain().focus().toggleBold().run()"
                    title="bold" type="button"
                    class="px-3 py-2 rounded hover:bg-indigo-500 hover:text-white transition"
                    :class="{
                    'bg-indigo-500 text-white': editor.isActive('bold'),
                }"
                >
                    <i class="ri-bold"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleItalic().run()"
                        title="italic" type="button"
                        class="px-3 py-2 rounded hover:bg-indigo-500 hover:text-white transition"
                        :class="{
                    'bg-indigo-500 text-white': editor.isActive('italic'),
                }"
                >
                    <i class="ri-italic"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleStrike().run()"
                        title="strike" type="button"
                        class="px-3 py-2 rounded hover:bg-indigo-500 hover:text-white transition"
                        :class="{
                    'bg-indigo-500 text-white': editor.isActive('strike'),
                }"
                >
                    <i class="ri-strikethrough"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleBlockquote().run()"
                        title="blockquote" type="button"
                        class="px-3 py-2 rounded hover:bg-indigo-500 hover:text-white transition"
                        :class="{
                    'bg-indigo-500 text-white': editor.isActive('blockquote'),
                }"
                >
                    <i class="ri-double-quotes-l"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleBulletList().run()"
                        title="bulletlist" type="button"
                        class="px-3 py-2 rounded hover:bg-indigo-500 hover:text-white transition"
                        :class="{
                    'bg-indigo-500 text-white': editor.isActive('bulletList'),
                }"
                >
                    <i class="ri-list-unordered"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleOrderedList().run()"
                        title="bulletlist" type="button"
                        class="px-3 py-2 rounded hover:bg-indigo-500 hover:text-white transition"
                        :class="{
                    'bg-indigo-500 text-white': editor.isActive('orderedList'),
                }"
                >
                    <i class="ri-list-ordered"></i>
                </button>
            </li>
            <li>
                <button @click="promptUserForHref"
                        title="add link" type="button"
                        class="px-3 py-2 rounded hover:bg-indigo-500 hover:text-white transition"
                        :class="{
                    'bg-indigo-500 text-white': editor.isActive('link'),
                }"
                >
                    <i class="ri-link"></i>
                </button>
            </li>
            <li v-for="level in 5">
                <button @click="() => editor.chain().focus().toggleHeading({level: level + 1}).run()"
                        :title="'heading ' + level" type="button"
                        class="px-3 py-2 rounded hover:bg-indigo-500 hover:text-white transition"
                        :class="{
                    'bg-indigo-500 text-white': editor.isActive('heading', {level: level + 1}),
                }"
                >
                    <i :class="'ri-h-' + level"></i>
                </button>
            </li>
        </menu>
        <EditorContent :editor="editor">

        </EditorContent>
    </div>
</template>

<style scoped>

</style>

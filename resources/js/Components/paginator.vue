<script setup>
import {Link} from '@inertiajs/vue3'
import {computed} from "vue";

const {meta} = defineProps({
    meta: {
        type: Object,
        required: true
    },
    only: {
        type:Array,
        default: () => []
    }
})
const previousPage = computed(() => meta.links[0])
const nextPage = computed(() => meta.links[meta.links.length - 1])
</script>

<template>
    <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
            <Link :only="[only]" :href="previousPage.url" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50" v-html="previousPage.label"></Link>
            <Link :only="[only]" :href="nextPage.url" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50" v-html="nextPage.label"></Link>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ meta.from }}</span>
                    to
                    <span class="font-medium">{{ meta.to }}</span>
                    of
                    <span class="font-medium">{{ meta.total }}</span>
                    results
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <Link :only="[only]" preserve-scroll v-for="page in meta.links" v-html="page.label" :href="page.url" :class="{'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': page.active,
                    'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0': !page.active}"
                       class="relative inline-flex items-center px-4 py-2 text-sm font-semibold">

                    </Link>
                </nav>
            </div>
        </div>
    </div>

</template>

<style scoped>

</style>

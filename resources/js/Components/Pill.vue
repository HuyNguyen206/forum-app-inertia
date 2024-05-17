<script setup>

import {Link} from "@inertiajs/vue3";

defineProps({
    href: {
        required: true
    },
    isActive: {
        default: false
    }
})
</script>

<template>
    <Link :class="{'bg-pink-500 text-white': isActive, 'font-bold rounded-full px-4 py-2 hover:bg-pink-500 hover:text-white transition border-pink-500 border-2': true}" :href="href">
        <slot></slot>
    </Link>
</template>

<style scoped>

</style>

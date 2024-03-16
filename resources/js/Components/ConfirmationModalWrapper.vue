<script setup>

import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {useConfirm} from "@/Utilities/Composable/useConfirm.js";
import {nextTick, ref, watchEffect} from "vue";

const {state, closeConfirmation, confirmAccept} = useConfirm()
const cancelRef = ref(null)
watchEffect(async () => {
    if (state.show) {
        await nextTick()
        cancelRef.value?.$el.focus()
    }
})

</script>

<template>
    <ConfirmationModal :show="state.show">
        <template #title>
            {{state.title}}
         </template>
        <template #content>
            {{state.message}}
         </template>
        <template #footer>
            <SecondaryButton ref="cancelRef" @click.prevent="closeConfirmation">Cancel</SecondaryButton>
            <PrimaryButton @click.prevent="confirmAccept" class="ml-3">Confirm</PrimaryButton>
        </template>
    </ConfirmationModal>
</template>

<style scoped>

</style>

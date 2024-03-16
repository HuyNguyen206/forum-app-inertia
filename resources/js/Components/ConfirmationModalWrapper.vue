<script setup>

import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {useConfirm} from "@/Utilities/Composable/useConfirm.js";

const {state, closeConfirmation, confirmAccept} = useConfirm()
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
            <SecondaryButton @click.prevent="closeConfirmation">Cancel</SecondaryButton>
            <PrimaryButton @click.prevent="confirmAccept" class="ml-3">Confirm</PrimaryButton>
        </template>
    </ConfirmationModal>
</template>

<style scoped>

</style>

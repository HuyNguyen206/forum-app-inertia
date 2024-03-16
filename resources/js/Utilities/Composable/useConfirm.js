import {reactive, readonly} from "vue";

const globalState = reactive({
    show: false,
    title: '',
    message: '',
    resolve: null
})

const resetModal = () => {
    globalState.show = false;
    globalState.title = '';
    globalState.message = '';
    globalState.resolve = null
}

const confirmation = (title, message) => {
    globalState.title = title;
    globalState.show = true;
    globalState.message = message;

    return new Promise((resolve) => {
        globalState.resolve = resolve;
    })
}

const closeConfirmation = () => {
    if (globalState.resolve) {
        globalState.resolve(false)
    }
    resetModal()
}

const confirmAccept = () => {
    if (globalState.resolve) {
        globalState.resolve(false)
    }
    resetModal()
}

export function  useConfirm() {
    return {
        state: readonly(globalState),
        confirmation,
        closeConfirmation,
        confirmAccept
    }
}

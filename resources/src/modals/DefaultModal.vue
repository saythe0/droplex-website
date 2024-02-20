<template>
    <BModal
        v-if="store.isAuth"
        v-model="store.modals.totp"
        @hide="store.toggleModal('totp')"
        centered="true"
        hideFooter="true"
        hideHeader="true"
        dialogClass="custom-modal__wrapper"
        contentClass="modal-content__custom"
        bodyClass="custom-modal"
    >
        <button
            class="modal-button-close"
            type="button"
            @click="store.toggleModal('totp')"
        >
            <i class="icon__close"></i>
        </button>
    </BModal>
</template>

<script setup>
import { ref } from "vue";
import api from "@/api";
import { notify } from "@kyvg/vue3-notification";
import useStore from "@/store/store";

const store = useStore();

const sum = ref(0);
const bonus = ref(0);
const selectMethod = ref(false);

const totp = ref({
    code: "OQB6 ZZGY HCPS X4AK",
    qrCode: "/qr-code.png",
    activationCode: "",
    deactivationCode: "",
});

function activation() {
    api.post("user/totp/activation", {
        totp: totp.value.code,
        code: totp.value.activationCode,
    }).then((response) => {
        if (response.data.success) store.updateUser();
    });
}

function deactivation() {
    api.post("user/totp/deactivation", {
        code: totp.value.deactivationCode,
    }).then((response) => {
        if (response.data.success) store.updateUser();
    });
}
</script>

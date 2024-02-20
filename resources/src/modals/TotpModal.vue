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

        <h1 class="custom-modal__title" v-if="!store.user.two_factor">
            Подключить двухэтапную авторизацию (2FA)
        </h1>
        <h1 class="custom-modal__title" v-else>
            Отключение двухэтапной авторизации (2FA)
        </h1>

        <div v-if="!store.user.two_factor">
            <div class="cabinet-block cabinet-block__bg-authenticator gap-96px">
                <div class="cabinet-block__head">
                    <h4 class="cabinet-block__name">
                        1. Установите приложение
                    </h4>
                    <p class="cabinet-block__description">
                        Установите софт (например Google Authenticator) для
                        генерации ключей авторизации. Если у Вас андроид,
                        зайдите в Google Play и найдите приложение, если айфон,
                        то в App Store.
                    </p>
                </div>
                <div class="cabinet-block__body flex-row gap-25px">
                    <button class="button button__dark px-big">
                        <i class="icon__app-store fz-24"></i>
                        App Store
                    </button>
                    <button class="button button__dark px-big">
                        <i class="icon__google-play fz-24"></i>
                        Google Play
                    </button>
                </div>
            </div>

            <div class="cabinet-block mt-25px">
                <div class="row g-0">
                    <div class="col-7 d-flex flex-column gap-36px">
                        <div class="cabinet-block__head mini">
                            <h4 class="cabinet-block__name">
                                2. Отсканируйте код
                            </h4>
                            <p class="cabinet-block__description">
                                Отсканируйте в приложение QR-код справа или
                                введите 16-ти значный код (который расположен
                                под QR-кодом)
                            </p>
                        </div>
                        <div class="cabinet-block__head mini gap-25px">
                            <h4 class="cabinet-block__name">
                                3. Введите ответную часть
                            </h4>
                            <div class="input-block">
                                <input
                                    type="number"
                                    class="input"
                                    placeholder="Введите ответную часть сюда"
                                    v-model="totp.enableCode"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div
                            class="mx-auto d-flex flex-column gap-25px"
                            style="max-width: 266px"
                        >
                            <div class="qr-code">
                                <img :src="totp.qrCode" alt="" />
                            </div>

                            <div class="input-block input-not-label">
                                <input
                                    type="text"
                                    class="input"
                                    v-model="totp.code"
                                    readonly
                                />
                                <button
                                    class="input-copy"
                                    type="button"
                                    @click="copyText(removeSpaces(totp.code))"
                                >
                                    <i class="icon__copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button
                class="button button__primary px-big mt-30px ms-auto"
                @click="activation"
            >
                Подключить двухэтапную авторизацию
            </button>
        </div>

        <div v-else>
            <div class="cabinet-block cabinet-block__bg-authenticator gap-25px">
                <div class="cabinet-block__head mini">
                    <h4 class="cabinet-block__name">1. Введите код ниже</h4>
                    <p class="cabinet-block__description">
                        Для отключение двухэтапной айтентификации, введите код
                        Google Authenticator ниже.
                    </p>
                </div>
                <div class="input-block" style="max-width: 357px">
                    <input
                        type="number"
                        class="input"
                        placeholder="Введите ответную часть сюда"
                        v-model="totp.disableCode"
                    />
                </div>
            </div>

            <button
                class="button button__primary px-big mt-30px ms-auto"
                @click="deactivation"
            >
                Выключить двухэтапную авторизацию
            </button>
        </div>
    </BModal>
</template>

<script setup>
import { onMounted, ref } from "vue";
import api from "@/api";
import { notify } from "@kyvg/vue3-notification";
import useStore from "@/store/store";

const store = useStore();

const totp = ref({
    code: "",
    qrCode: "",
    enableCode: "",
    disableCode: "",
});

function load() {
    api.get("user/totp/load").then((response) => {
        if (response.data.success) {
            totp.value.code = response.data.code;
            totp.value.qrCode = response.data.qrCode;
        }
    });
}

function activation() {
    api.post("user/totp/enable", {
        totp: totp.value.code,
        code: totp.value.enableCode,
    }).then((response) => {
        if (response.data.success) store.getUser();
    });
}

function deactivation() {
    api.post("user/totp/disable", {
        code: totp.value.disableCode,
    }).then((response) => {
        if (response.data.success) store.getUser();
    });
}

onMounted(() => {
    load();
});
</script>

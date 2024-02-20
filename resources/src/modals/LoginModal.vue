<template>
    <BModal
        v-if="!store.isAuth"
        v-model="store.modals.login"
        @hide="store.toggleModal('login')"
        centered="true"
        hideFooter="true"
        hideHeader="true"
        dialogClass="modal-dialog-minimum"
        bodyClass="signin p-0"
    >
        <button
            class="modal-button-close"
            type="button"
            @click="store.toggleModal('login')"
        >
            <i class="icon__close"></i>
        </button>

        <div class="d-flex align-items-center flex-row gap-20px w-100">
            <button
                class="button-back"
                type="button"
                @click="totpEnable = false"
                v-if="totpEnable"
            >
                <i class="icon__arrow-back"></i>
            </button>

            <img
                src="/assets/images/logo-with-text.svg"
                alt=""
                class="modal-droplex"
            />
        </div>

        <div class="d-flex flex-column gap-20px w-100" v-if="!totpEnable">
            <h3 class="signin__title">Авторизация</h3>
            <div class="d-flex flex-column gap-10px">
                <div class="input-block">
                    <input
                        type="text"
                        v-model="nickname"
                        id="loginNickname"
                        class="input"
                        placeholder="Введите свой никнейм"
                        autocomplete="name nickname username"
                    />
                    <label for="loginNickname" class="input-label">
                        Никнейм:
                    </label>
                </div>

                <div class="input-block">
                    <input
                        :type="passwordPreview ? 'text' : 'password'"
                        v-model="password"
                        id="loginPassword"
                        class="input"
                        placeholder="Ваш пароль"
                        autocomplete="current-password"
                    />
                    <button
                        class="input-preview"
                        @click="passwordPreview = !passwordPreview"
                        type="button"
                    >
                        <i
                            :class="
                                'icon__preview-' +
                                (passwordPreview ? 'close' : 'open')
                            "
                        ></i>
                    </button>
                    <label for="loginPassword" class="input-label">
                        Пароль:
                    </label>
                </div>

                <a class="link" @click="recovery" role="button">
                    Забыл пароль?
                </a>
            </div>
        </div>

        <div class="d-flex flex-column gap-30px" v-if="totpEnable">
            <div class="d-flex flex-column gap-15px">
                <h3 class="signin__title">ПОДТВЕРДИТЕ АВТОРИЗАЦИЮ</h3>
                <p class="signin__description">
                    Войдите в приложение и введите ниже временный шестизначный
                    код.
                </p>
            </div>
            <div class="d-flex flex-column gap-10px">
                <div class="code-block code-block_6">
                    <svg
                        class="d-block i-3"
                        width="13"
                        height="3"
                        viewBox="0 0 13 3"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <rect
                            width="13"
                            height="3"
                            fill="white"
                            fill-opacity="0.5"
                        />
                    </svg>
                    <input
                        type="number"
                        v-model="totpCodes.code1"
                        class="input i-0"
                    />
                    <input
                        type="number"
                        v-model="totpCodes.code2"
                        class="input i-1"
                    />
                    <input
                        type="number"
                        v-model="totpCodes.code3"
                        class="input i-2"
                    />
                    <input
                        type="number"
                        v-model="totpCodes.code4"
                        class="input i-4"
                    />
                    <input
                        type="number"
                        v-model="totpCodes.code5"
                        class="input i-5"
                    />
                    <input
                        type="number"
                        v-model="totpCodes.code6"
                        class="input i-6"
                    />
                </div>
            </div>
        </div>

        <img src="/assets/images/two-step-auth.svg" alt="" v-if="totpEnable" />

        <div
            class="w-100"
            :style="totpEnable ? 'margin-top: -40px;' : 'margin-top: 10px'"
        >
            <button
                class="button button__primary w-100"
                type="submit"
                @click="totpEnable ? loginTotp() : login()"
            >
                Войти
            </button>
        </div>
    </BModal>
</template>

<script setup>
import { ref } from "vue";
import api from "@/api";
import useStore from "@/store/store";

const store = useStore();

const totpEnable = ref(false);

const nickname = ref("");
const password = ref("");
const passwordPreview = ref(false);

const totpCodes = ref({
    code1: "",
    code2: "",
    code3: "",
    code4: "",
    code5: "",
    code6: "",
});

function login() {
    api.post("/auth/login", {
        name: nickname.value,
        password: password.value,
    }).then((response) => {
        if (response.data.success) {
            store.getUser();
            store.toggleModal("login");
        } else if (response.data.totp && !totpEnable.value) {
            totpEnable.value = true;
        }
    });
}

function loginTotp() {
    api.post("/auth/login/totp", {
        name: nickname.value,
        password: password.value,
        totp: Object.values(totpCodes.value).join(""),
    }).then((response) => {
        if (response.data.success) {
            store.getUser();
            store.toggleModal("login");
        }
    });
}

function recovery() {
    store.toggleModal("login");
    store.toggleModal("recovery");
}
</script>

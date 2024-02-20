<template>
    <BModal
        v-if="!store.isAuth"
        v-model="store.modals.register"
        @hide="store.toggleModal('register')"
        centered="true"
        hideFooter="true"
        hideHeader="true"
        dialogClass="modal-dialog-minimum"
        bodyClass="signin p-0"
    >
        <button
            class="modal-button-close"
            type="button"
            @click="store.toggleModal('register')"
        >
            <i class="icon__close"></i>
        </button>

        <div class="d-flex align-items-center flex-row gap-20px w-100">
            <img
                src="/assets/images/logo-with-text.svg"
                alt=""
                class="modal-droplex"
            />
        </div>

        <div class="d-flex flex-column gap-20px w-100">
            <h3 class="signin__title">Регистрация</h3>
            <div class="d-flex flex-column gap-10px">
                <div class="input-block">
                    <input
                        type="text"
                        v-model="nickname"
                        id="registerNickname"
                        class="input"
                        placeholder="Никнейм"
                        autocomplete="nickname username"
                    />
                    <label for="registerNickname" class="input-label">
                        Придумайте никнейм:
                    </label>
                </div>
                <div class="input-block">
                    <input
                        type="email"
                        v-model="email"
                        id="registerEmail"
                        class="input"
                        placeholder="E-mail"
                        autocomplete="email"
                    />
                    <label for="registerEmail" class="input-label">
                        Введите свою почту:
                    </label>
                </div>
                <div class="input-block">
                    <input
                        :type="passwordPreview ? 'text' : 'password'"
                        v-model="password"
                        id="registerPassword"
                        class="input"
                        placeholder="Ваш пароль"
                        autocomplete="new-password"
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
                    <label for="registerPassword" class="input-label">
                        Придумайте пароль:
                    </label>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column gap-15px w-100 mt-10px">
            <div class="checkbox-block">
                <input
                    type="checkbox"
                    v-model="rules"
                    id="registerRules"
                    class="checkbox"
                    checked
                />
                <label for="registerRules" class="checkbox-label">
                    согласен с
                    <RouterLink
                        :to="{ name: 'Rules' }"
                        class="link color-white"
                    >
                        правилами проекта
                    </RouterLink>
                </label>
            </div>

            <button class="button button__primary w-100" @click="register">
                Зарегистрироваться
            </button>
        </div>
    </BModal>
</template>

<script setup>
import { ref } from "vue";
import api from "@/api";
import useStore from "@/store/store";

const store = useStore();

const nickname = ref("");
const email = ref("");
const password = ref("");
const passwordPreview = ref(false);
const rules = ref(true);

function register() {
    api.post("/auth/register", {
        name: nickname.value,
        email: email.value,
        password: password.value,
        referral: localStorage.getItem("referral"),
        rules: rules.value,
    }).then((response) => {
        if (response.data.success) {
            store.getUser();
            store.toggleModal("register");
        }
    });
}
</script>

<template>
    <div class="cabinet">
        <div class="row g-30px">
            <div class="col-4">
                <div class="cabinet-block cabinet-block__bg-password">
                    <h4 class="cabinet-block__name">Смена пароля</h4>
                    <div class="cabinet-block__body gap-25px">
                        <div class="d-flex flex-column gap-10px">
                            <div class="input-block">
                                <input
                                    type="password"
                                    v-model="oldPassword"
                                    id="oldPassword"
                                    class="input"
                                    placeholder="Ваш пароль..."
                                />
                                <label for="oldPassword" class="input-label">
                                    Последний Ваш пароль:
                                </label>
                            </div>
                            <div class="input-block">
                                <input
                                    type="password"
                                    v-model="newPassword"
                                    id="newPassword"
                                    class="input"
                                    placeholder="Новый пароль..."
                                />
                                <label for="newPassword" class="input-label">
                                    Новый пароль:
                                </label>
                            </div>
                            <div class="input-block">
                                <input
                                    type="password"
                                    v-model="newPasswordConfirm"
                                    id="newPasswordConfirm"
                                    class="input"
                                    placeholder="Новый пароль..."
                                />
                                <label
                                    for="newPasswordConfirm"
                                    class="input-label"
                                >
                                    Повторите новый пароль:
                                </label>
                            </div>
                        </div>
                        <button
                            class="button button__dark"
                            @click="changePassword"
                        >
                            Сменить пароль
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="cabinet-block cabinet-block__bg-email">
                    <h4 class="cabinet-block__name">Смена E-mail</h4>
                    <div class="cabinet-block__body gap-25px">
                        <div class="d-flex flex-column gap-10px">
                            <div class="input-block">
                                <input
                                    type="email"
                                    v-model="oldEmail"
                                    id="oldEmail"
                                    class="input"
                                    placeholder="Старая почта..."
                                />
                                <label for="oldEmail" class="input-label">
                                    Последний Ваш E-mail:
                                </label>
                            </div>
                            <div class="input-block">
                                <input
                                    type="email"
                                    v-model="newEmail"
                                    id="newEmail"
                                    class="input"
                                    placeholder="Новая почта..."
                                />
                                <label for="newEmail" class="input-label">
                                    Новый E-mail:
                                </label>
                            </div>
                            <div class="input-block">
                                <input
                                    type="password"
                                    v-model="passwordEmail"
                                    id="passwordEmail"
                                    class="input"
                                    placeholder="Ваш пароль..."
                                />
                                <label for="passwordEmail" class="input-label">
                                    Ваш пароль:
                                </label>
                            </div>
                        </div>
                        <button
                            class="button button__dark"
                            @click="changeEmail"
                        >
                            Сменить почту
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-4 d-flex flex-column gap-30px">
                <div class="cabinet-block cabinet-block__bg-lag-zone">
                    <h4 class="cabinet-block__name">Застряли в лаг зоне?</h4>
                    <button
                        class="button button__dark mt-20px"
                        @click="store.toggleModal('spawnTeleport')"
                    >
                        Перейти
                        <i class="icon__arrow-right-button fz-12"></i>
                    </button>
                </div>

                <div class="cabinet-block cabinet-block__bg-totp h-100">
                    <h4 class="cabinet-block__name">
                        Двухфакторная авторизация
                    </h4>
                    <div class="mt-auto">
                        <button
                            class="button button__dark mt-20px w-100"
                            @click="store.toggleModal('totp')"
                        >
                            Перейти
                            <i class="icon__arrow-right-button fz-12"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="cabinet-block">
                    <h4 class="cabinet-block__name">Активность на аккаунте</h4>
                    <div class="table-normal mt-20px">
                        <div class="table-normal__grid">
                            <p class="table-normal__head">IP-адрес</p>
                            <p class="table-normal__head">Браузер</p>
                            <p class="table-normal__head">Дата входа</p>
                            <p class="table-normal__head">Дата выхода</p>
                        </div>
                        <div class="table-normal__body">
                            <div
                                class="table-normal__grid"
                                v-for="log in activities"
                            >
                                <p class="table-normal__text">
                                    {{ log.ip_address }}
                                    <i
                                        class="icon__cirle-green"
                                        v-if="log.logout_at == null"
                                    ></i>
                                </p>
                                <p class="table-normal__text">
                                    Браузер:
                                    {{
                                        log.userAgent.getBrowser().name ||
                                        "Неизвестно"
                                    }}
                                    {{ log.userAgent.getBrowser().version }}
                                    <br />
                                    OC:
                                    {{
                                        log.userAgent.getOS().name ||
                                        "Неизвестно"
                                    }}
                                    {{ log.userAgent.getOS().version }}
                                </p>
                                <p class="table-normal__text">
                                    {{ dateFormat(log.login_at) }}
                                </p>
                                <p class="table-normal__text">
                                    {{
                                        log.logout_at
                                            ? dateFormat(log.logout_at)
                                            : "Нет данных"
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import useStore from "@/store/store";
import api from "@/api";
import UAParser from "ua-parser-js";

const store = useStore();

const oldPassword = ref("");
const newPassword = ref("");
const newPasswordConfirm = ref("");

const oldEmail = ref("");
const newEmail = ref("");
const passwordEmail = ref("");

let activities = ref([]);

function changePassword() {
    api.post("/user/change", {
        type: "password",
        old_password: oldPassword.value,
        new_password: newPassword.value,
        new_password_confirm: newPasswordConfirm.value,
    }).then((response) => {
        if (response.data.success) store.getUser();
    });
}

function changeEmail() {
    api.post("/user/change", {
        type: "email",
        old_email: oldEmail.value,
        new_email: newEmail.value,
        password: passwordEmail.value,
    }).then((response) => {
        if (response.data.success) store.getUser();
    });
}

function getActivities() {
    api.get("/user/activities/auth").then((response) => {
        if (response.data.success) {
            activities.value = response.data.activities;

            activities.value.map((log) => {
                log.userAgent = new UAParser(log.user_agent);
                return log;
            });
        }
    });
}

onMounted(() => {
    getActivities();
});
</script>

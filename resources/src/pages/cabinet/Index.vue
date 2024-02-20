<template>
    <div class="cabinet pt-30px">
        <h1 class="cabinet__hello">
            👋 Добро пожаловать,
            <b>{{ store.user.name }}</b>
        </h1>

        <div class="mt-30px">
            <div class="row g-30px">
                <div class="col-auto">
                    <div class="cabinet-block cabinet-block__mini">
                        <h4 class="cabinet-block__name">Ваш скин</h4>
                        <button
                            :class="
                                'cabinet-block__button fade button button__dark icon__mini ' +
                                (previewSkin ? '' : 'show')
                            "
                            @click="
                                side2dSkin ? (side2dSkin = 0) : (side2dSkin = 1)
                            "
                        >
                            <i class="icon__rotation fz-26"></i>
                        </button>
                        <div class="cabinet-block__body mb-10px gap-0">
                            <div
                                :class="
                                    'skin__2d fade ' +
                                    (previewSkin ? 'h-0' : 'show')
                                "
                            >
                                <img
                                    :src="store.skin.front"
                                    alt="front"
                                    :class="
                                        'fade ' + (side2dSkin ? 'w-0' : 'show')
                                    "
                                />
                                <img
                                    :src="store.skin.back"
                                    alt="back"
                                    :class="
                                        'fade ' + (side2dSkin ? 'show' : 'w-0')
                                    "
                                />
                            </div>
                            <div
                                :class="
                                    'skin__3d fade ' +
                                    (previewSkin ? 'show' : 'h-0')
                                "
                            >
                                <canvas id="skin_container"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="mt-30px group-button">
                        <button
                            :class="
                                'group-button__button ' +
                                (previewSkin ? 'active' : '')
                            "
                            @click="previewSkin = 1"
                        >
                            Скин 3D
                        </button>
                        <button
                            :class="
                                'group-button__button ' +
                                (previewSkin ? '' : 'active')
                            "
                            @click="previewSkin = 0"
                        >
                            Скин 2D
                        </button>
                    </div>
                </div>

                <div class="col">
                    <div class="cabinet-block">
                        <h4 class="cabinet-block__name">Общая информация</h4>
                        <div class="cabinet-block__body">
                            <div class="table-dotted">
                                <div class="table-dotted__item">
                                    <p class="table-dotted__name">
                                        ID аккаунта:
                                    </p>
                                    <p
                                        class="table-dotted__value icon-after"
                                        @click="copyText('#' + store.user.id)"
                                    >
                                        #{{ store.user.id }}
                                    </p>
                                </div>
                                <div class="table-dotted__item">
                                    <p class="table-dotted__name">
                                        Игровой ник:
                                    </p>
                                    <p
                                        class="table-dotted__value icon-after"
                                        @click="copyText(store.user.name)"
                                    >
                                        {{ store.user.name }}
                                    </p>
                                </div>
                                <div class="table-dotted__item">
                                    <p class="table-dotted__name">
                                        Ваша почта:
                                    </p>
                                    <p
                                        class="table-dotted__value icon-after"
                                        @click="copyText(store.user.email)"
                                    >
                                        {{ store.user.email }}
                                    </p>
                                </div>
                                <div class="table-dotted__item">
                                    <p class="table-dotted__name">
                                        Дата регистрации:
                                    </p>
                                    <p
                                        class="table-dotted__value icon-after"
                                        @click="
                                            copyText(
                                                dateFormat(
                                                    store.user.created_at
                                                )
                                            )
                                        "
                                    >
                                        {{ dateFormat(store.user.created_at) }}
                                    </p>
                                </div>
                                <div class="table-dotted__item">
                                    <p class="table-dotted__name">
                                        Проведено на проекте:
                                    </p>
                                    <p
                                        class="table-dotted__value icon-after"
                                        @click="copyText('259 дней')"
                                    >
                                        259 дней
                                    </p>
                                </div>
                                <div class="table-dotted__item">
                                    <p class="table-dotted__name">
                                        Последняя авторизация:
                                    </p>
                                    <p
                                        class="table-dotted__value icon-after"
                                        @click="
                                            copyText(
                                                dateFormat(
                                                    store.user.last_auth_date
                                                )
                                            )
                                        "
                                    >
                                        {{
                                            dateFormat(
                                                store.user.last_auth_date
                                            )
                                        }}
                                    </p>
                                    <!-- При наводке будет показываться IP адрес -->
                                </div>
                                <div class="table-dotted__item">
                                    <p class="table-dotted__name">
                                        Защита 2FA:
                                    </p>
                                    <p
                                        class="table-dotted__value icon-after"
                                        @click="
                                            copyText(
                                                store.user.two_factor
                                                    ? 'подключено'
                                                    : 'не подключено'
                                            )
                                        "
                                    >
                                        {{
                                            store.user.two_factor
                                                ? "подключено"
                                                : "не подключено"
                                        }}
                                    </p>
                                </div>
                                <div class="table-dotted__item">
                                    <p class="table-dotted__name">
                                        HWID блокировка:
                                    </p>
                                    <p
                                        class="table-dotted__value icon-after"
                                        @click="
                                            copyText(
                                                store.hwid
                                                    ? 'присутствует'
                                                    : 'не обнаружено'
                                            )
                                        "
                                    >
                                        {{
                                            store.hwid
                                                ? "присутствует"
                                                : "не обнаружено"
                                        }}
                                    </p>
                                </div>
                            </div>

                            <div class="row g-10px">
                                <div class="col-6">
                                    <div
                                        class="data-block data-block__broken-blocks"
                                    >
                                        <p class="data-block__text">
                                            Сломано всего:
                                        </p>
                                        <p class="data-block__value">
                                            <b>~27к</b> блока
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div
                                        class="data-block data-block__supplied-blocks"
                                    >
                                        <p class="data-block__text">
                                            Поставленно всего:
                                        </p>
                                        <p class="data-block__value">
                                            <b>~19к</b> блоков
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="data-block data-block__deaths">
                                        <p class="data-block__text">
                                            Вас убили:
                                        </p>
                                        <p class="data-block__value">
                                            <b>17</b> смертей
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="data-block data-block__murders">
                                        <p class="data-block__text">
                                            Было сделано:
                                        </p>
                                        <p class="data-block__value">
                                            <b>36</b> убийств
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="cabinet-block cabinet-block__mini">
                        <h4 class="cabinet-block__name">Баланс аккаунта</h4>
                        <button
                            @click="update"
                            class="cabinet-block__button button button__dark icon__mini"
                        >
                            <i class="icon__update"></i>
                        </button>
                        <div class="cabinet-block__body">
                            <div class="d-flex flex-column gap-10px">
                                <div class="balance balance__rubles">
                                    <p class="balance__text">Рублевой счет:</p>
                                    <p class="balance__amount">
                                        {{ store.user.money }}₽
                                    </p>
                                </div>
                                <div class="balance balance__coins">
                                    <p class="balance__text">
                                        Бонусы на балансе:
                                    </p>
                                    <p class="balance__amount">
                                        {{ store.user.coins }}
                                        <i class="icon__coins fz-23"></i>
                                    </p>
                                </div>
                            </div>

                            <button
                                class="button button__primary"
                                @click="store.toggleModal('balance')"
                            >
                                Пополнить рубли
                                <i class="icon__arrow-right-button fz-12"></i>
                            </button>
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
import * as skinview3d from "skinview3d";

const store = useStore();

const previewSkin = ref(0);
const side2dSkin = ref(0);
var skinViewer = null;

function update() {
    store.updateUser();
}

function initSkin() {
    skinViewer = new skinview3d.SkinViewer({
        canvas: document.getElementById("skin_container"),
        width: 257,
        height: 342,
        skin: store.skin.file,
        cape: store.skin.cloak,
        zoom: 1,
    });

    skinViewer.animation = new skinview3d.WalkingAnimation();
    skinViewer.animation.speed = 0.8;
}

onMounted(() => {
    initSkin();
});
</script>

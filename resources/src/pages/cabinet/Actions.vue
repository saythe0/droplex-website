<template>
    <div class="cabinet">
        <div class="row g-30px">
            <div class="col-auto">
                <div class="cabinet-block cabinet-block__mini">
                    <h4 class="cabinet-block__name">Действия</h4>
                    <div class="cabinet-block__body gap-25px">
                        <nav class="nav nav__cabinet">
                            <router-link
                                :to="{ name: 'CabinetDonate' }"
                                class="nav__item"
                            >
                                <i class="icon__star fz-16"></i>
                                Привилегии
                            </router-link>
                            <router-link
                                :to="{ name: 'CabinetShopBlocks' }"
                                class="nav__item"
                            >
                                <i class="icon__cube fz-16"></i>
                                Магазин блоков
                            </router-link>
                            <router-link
                                :to="{ name: 'CabinetShopItems' }"
                                class="nav__item"
                            >
                                <i class="icon__blocks fz-16"></i>
                                Магазин предметов
                            </router-link>
                            <router-link
                                :to="{ name: 'CabinetGift' }"
                                class="nav__item disabled"
                            >
                                <i class="icon__gift fz-16"></i>
                                Подарочные коды
                            </router-link>
                            <router-link
                                :to="{ name: 'CabinetExchange' }"
                                class="nav__item disabled"
                            >
                                <i class="icon__sphere fz-16"></i>
                                Обмен монет
                            </router-link>
                            <router-link
                                :to="{ name: 'CabinetChat' }"
                                class="nav__item disabled"
                            >
                                <i class="icon__edit fz-16"></i>
                                Префиксы и суффиксы
                            </router-link>
                        </nav>

                        <div class="select-block">
                            <label
                                for="cabinetActionsServers"
                                class="select-block__label"
                                >Выберите сервер:</label
                            >
                            <select
                                id="cabinetActionsServers"
                                v-model="store.actionServer"
                            >
                                <option
                                    v-for="server in store.servers"
                                    :value="server.id"
                                >
                                    {{ server.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="cabinet-block cabinet-block__mini mt-30px">
                    <h4 class="cabinet-block__name">Баланс аккаунта</h4>
                    <button
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
                                <p class="balance__text">Коинов на балансе:</p>
                                <p class="balance__amount">
                                    {{ store.user.coins }}
                                    <i class="icon__coins fz-23"></i>
                                </p>
                            </div>
                        </div>

                        <button
                            class="button button__primary"
                            data-bs-toggle="modal"
                            data-bs-target="#BalanceModal"
                        >
                            Пополнить рубли
                            <i class="icon__arrow-right-button fz-12"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col d-flex flex-column gap-78px">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import useStore from "@/store/store";

const store = useStore();
</script>

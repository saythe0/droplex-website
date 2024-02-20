<template>
    <nav class="nav nav__main" id="nav">
        <div class="container nav__container">
            <router-link :to="{ name: 'Main' }" class="nav__logo">
                <img
                    src="/assets/images/logo-with-text.svg"
                    width="130"
                    alt=""
                    class="nav__logo-image"
                />
            </router-link>

            <div class="nav__menu">
                <router-link :to="{ name: 'Rules' }" class="nav__menu-item"
                    >Правила</router-link
                >
                <a
                    @click="store.toggleModal('launcher')"
                    class="nav__menu-item"
                >
                    Лаунчер
                </a>
                <div dropdown>
                    <a role="button" class="nav__menu-item">
                        <i class="icon__burger"></i> Сервера
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li>
                            <a class="dropdown-item" href="#">Another action</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                Something else here
                            </a>
                        </li>
                    </ul>
                </div>
                <router-link
                    :to="{ name: 'CabinetDonate' }"
                    class="nav__menu-item"
                >
                    Донат
                </router-link>
                <div dropdown>
                    <a role="button" class="nav__menu-item">
                        <i class="icon__burger"></i> Информация
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#">Action</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Another action</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                Something else here
                            </a>
                        </li>
                    </ul>
                </div>
                <router-link :to="{ name: 'Cabinet' }" class="nav__menu-item">
                    Кабинет
                </router-link>
            </div>

            <div class="nav__user" v-if="store.isAuth">
                <router-link :to="{ name: 'Cabinet' }" class="nav__user-info">
                    <img :src="store.skin.head" alt="" class="nav__user-head" />
                    <p class="nav__user-name">{{ store.user.name }}</p>
                </router-link>
                <button
                    class="nav__user-button nav__user-cart"
                    tooltip="bottom"
                    :data-bs-title="'В корзине ' + store.cart.count + ' шт.'"
                >
                    <i class="icon__cart-2"></i>
                    <p class="nav__user-cart-num">{{ store.cart.count }}</p>
                </button>
                <button
                    @click="store.logout"
                    class="nav__user-button nav__user-logout"
                >
                    <i class="icon__logout"></i>
                </button>
            </div>

            <div class="nav__buttons" v-else>
                <button
                    class="button button__dark button_not-blur mini"
                    @click="store.toggleModal('login')"
                >
                    Вход
                </button>
                <button
                    class="button button__primary mini px-big"
                    @click="store.toggleModal('register')"
                >
                    Регистрация
                </button>
            </div>

            <button
                :class="'nav__burger' + (mobileMenu ? ' active' : '')"
                @click="
                    mobileMenu = !mobileMenu;
                    mobileMenuClick = true;
                "
                id="mobileMenuButton"
            >
                <svg viewBox="0 0 64 48">
                    <path
                        d="M19,15 L45,15 C70,15 58,-2 49.0177126,7 L19,37"
                    ></path>
                    <path
                        d="M19,24 L45,24 C61.2371586,24 57,49 41,33 L32,24"
                    ></path>
                    <path d="M45,33 L19,33 C-8,33 6,-2 22,14 L45,37"></path>
                </svg>
            </button>
        </div>
    </nav>

    <div
        :class="
            'mobile-menu' +
            (mobileMenu ? ' visible' : ' closed') +
            (mobileMenuClick ? '' : ' d-none')
        "
        id="mobileMenuWrapper"
    >
        <div class="container">
            <div class="mobile-menu__body mobile">
                <div
                    class="d-flex flex-column gap-row-12px"
                    style="gap: 12px 0px"
                    v-if="!store.isAuth"
                >
                    <button
                        class="button button__dark"
                        @click="store.toggleModal('login')"
                    >
                        Вход
                    </button>
                    <button
                        class="button button__primary"
                        @click="store.toggleModal('register')"
                    >
                        Регистрация
                    </button>
                </div>
                <hr class="mobile__hr" v-if="!store.isAuth" />

                <div class="mobile__menu">
                    <router-link
                        :to="{ name: 'Rules' }"
                        class="mobile__menu-item"
                    >
                        <i class="icon__star"></i> Правила проекта
                    </router-link>
                    <div dropdown>
                        <a role="button" class="mobile__menu-item">
                            <i class="icon__server"></i> Список серверов
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#">Action</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    Another action
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    Something else here
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div dropdown>
                        <a role="button" class="mobile__menu-item">
                            <i class="icon__burger"></i> Подробная информация
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#">Action</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    Another action
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    Something else here
                                </a>
                            </li>
                        </ul>
                    </div>
                    <router-link
                        :to="{ name: 'Cabinet' }"
                        class="mobile__menu-item"
                    >
                        <i class="icon__user"></i> Личный кабинет
                    </router-link>
                    <router-link
                        :to="{ name: 'Cabinet' }"
                        class="mobile__menu-item"
                    >
                        <i class="icon__verified"></i> Техническая поддержка
                    </router-link>
                </div>

                <hr class="mobile__hr" v-if="store.isAuth" />
                <div
                    class="nav__user d-flex flex-column mx-auto"
                    v-if="store.isAuth"
                >
                    <router-link
                        :to="{ name: 'Cabinet' }"
                        class="nav__user-info"
                    >
                        <img
                            :src="store.skin.head"
                            alt=""
                            class="nav__user-head"
                        />
                        <p class="nav__user-name">{{ store.user.name }}</p>
                    </router-link>
                    <div class="d-flex" style="gap: 14px">
                        <button
                            class="nav__user-button nav__user-cart"
                            tooltip="bottom"
                            :data-bs-title="
                                'В корзине ' + store.cart.count + ' шт.'
                            "
                        >
                            Корзина <i class="icon__cart-2"></i>
                            <span class="nav__user-cart-num">{{
                                store.cart.count
                            }}</span>
                        </button>
                        <button
                            @click="store.logout"
                            class="nav__user-button nav__user-logout"
                        >
                            Выйти <i class="icon__logout"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import useStore from "@/store/store";

const store = useStore();

const mobileMenu = ref(false);
const mobileMenuClick = ref(false);
</script>

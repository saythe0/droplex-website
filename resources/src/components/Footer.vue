<template>
    <footer class="footer mt-auto" id="footer">
        <div class="footer__content">
            <div class="container-lg">
                <div class="footer__container">
                    <router-link :to="{ name: 'Main' }" class="footer__logo">
                        <img src="/assets/images/black-logo.svg" alt="" />
                    </router-link>
                    <div class="footer__hr"></div>
                    <div class="footer__menu footer__menu-main">
                        <p class="footer__title">Основные</p>
                        <div class="footer__links">
                            <router-link
                                :to="{ name: 'Cabinet' }"
                                class="footer__links-item bottom-line"
                            >
                                Личный кабинет
                            </router-link>
                            <router-link
                                :to="{ name: 'FAQ' }"
                                class="footer__links-item bottom-line"
                            >
                                F. A. Q.
                            </router-link>
                            <router-link
                                :to="{ name: 'CabinetCustom' }"
                                class="footer__links-item bottom-line"
                            >
                                Персонализация
                            </router-link>
                            <router-link
                                :to="{ name: 'Rules' }"
                                class="footer__links-item bottom-line"
                            >
                                Правила
                            </router-link>
                            <router-link
                                :to="{ name: 'CabinetSafety' }"
                                class="footer__links-item bottom-line"
                            >
                                Безопасность
                            </router-link>
                            <router-link
                                :to="{ name: 'Team' }"
                                class="footer__links-item bottom-line"
                            >
                                Администрация
                            </router-link>
                            <router-link
                                :to="{ name: 'CabinetShopBlocks' }"
                                class="footer__links-item bottom-line"
                            >
                                Магазин блоков
                            </router-link>
                            <a
                                @click="store.toggleModal('launcher')"
                                class="footer__links-item bottom-line"
                            >
                                Скачать лаунчер
                            </a>
                        </div>
                    </div>
                    <div class="footer__hr"></div>
                    <div class="footer__menu">
                        <p class="footer__title">Контактные данные</p>
                        <div class="footer__links d-flex flex-column">
                            <router-link
                                :to="{ name: 'Privacy' }"
                                class="footer__links-item bottom-line"
                            >
                                Конфиденциальность
                            </router-link>
                            <router-link
                                :to="{ name: 'PublicOffer' }"
                                class="footer__links-item bottom-line"
                            >
                                Публичная оферта
                            </router-link>
                            <div class="d-flex gap-3">
                                <a
                                    :href="'https://vk.me/' + vkGroupId"
                                    target="_blank"
                                    class="footer__links-item icon__vk"
                                ></a>
                                <a
                                    :href="discordUrl"
                                    target="_blank"
                                    class="footer__links-item icon__ds"
                                ></a>
                                <a
                                    :href="youtubeUrl"
                                    target="_blank"
                                    class="footer__links-item icon icon__yt"
                                ></a>
                            </div>
                        </div>
                    </div>
                    <div class="footer__hr"></div>
                    <div class="footer__question">
                        <p class="footer__title">Остались вопросы?</p>
                        <a
                            :href="'https://vk.me/' + vkGroupId"
                            target="_blank"
                            class="button button__dark mini"
                            >Задать вопрос</a
                        >
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import useStore from "@/store/store";

const store = useStore();
</script>

<template>
    <div class="container">
        <div class="section" id="news">
            <h1 class="section__title">Новости серверов</h1>
            <div class="grid-3">
                <div class="news-block" v-for="item in news">
                    <div class="news-block__head">
                        <img
                            :src="item.photo"
                            alt=""
                            class="news-block__head-image"
                        />
                        <div class="news-block__data">
                            <p class="news-block__data-text">
                                <i class="icon__view"></i>
                                {{ item.views }}
                            </p>
                            <p class="news-block__data-text">
                                <i class="icon__like"></i>
                                {{ item.likes }}
                            </p>
                            <p class="news-block__data-text">
                                <i class="icon__comment"></i>
                                {{ item.comments }}
                            </p>
                            <p class="news-block__data-text">
                                <i class="icon__share"></i>
                                {{ item.reposts }}
                            </p>
                        </div>
                    </div>
                    <div class="news-block__body">
                        <div class="news-block__content">
                            <h2 class="news-block__title">
                                {{ item.title }}
                            </h2>
                            <p class="news-block__text">
                                {{ item.text }}
                            </p>
                        </div>
                        <a
                            :href="item.link"
                            class="button button__primary mini ms-auto mt-auto"
                            target="_blank"
                        >
                            Перейти
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="section" id="servers">
            <h1 class="section__title">Сервера проекта</h1>
            <div class="grid-3">
                <div
                    class="server-block server-block_download"
                    style="
                        background-image: url(/assets/images/download-block.svg);
                    "
                >
                    <div class="server-block__content">
                        <div class="server-block__head">
                            <h2 class="server-block__title">
                                <i class="icon__download"></i>
                                Скачайте лаунчер!
                            </h2>
                            <p class="server-block__text">
                                Без нашего лаунчера, Вы не сможете начать игру.
                                Скачайте лаунчер по кнопке снизу.
                            </p>
                        </div>
                    </div>
                    <div class="server-block__footer">
                        <button
                            class="button button__dark mini w-100"
                            @click="store.toggleModal('launcher')"
                        >
                            Скачать лаунчер
                        </button>
                    </div>
                </div>

                <div
                    class="server-block"
                    :style="
                        'background-image: url(/assets/images/servers/backgrounds/' +
                        server.image +
                        '.png);'
                    "
                    v-for="server in store.servers"
                >
                    <div class="server-block__content">
                        <div class="server-block__head">
                            <h2 class="server-block__title">
                                <img
                                    :src="
                                        '/assets/images/servers/icons/' +
                                        server.icon +
                                        '.svg'
                                    "
                                    alt=""
                                />
                                {{ server.name }}
                            </h2>
                            <p class="server-block__description">
                                {{ server.description }}
                            </p>
                        </div>
                        <div class="server-block__separator"></div>
                        <div
                            class="d-flex flex-column gap-10px align-items-center"
                        >
                            <p class="server-block__text">Сейчас на сервере:</p>
                            <h1 class="server-block__online">
                                {{ server.online }}<span>чел.</span>
                            </h1>
                        </div>
                    </div>
                    <div class="server-block__footer">
                        <div class="server-block__online-bar">
                            <div
                                :style="'width: ' + server.online_percent + '%'"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import api from "@/api";
import useStore from "@/store/store";

const store = useStore();

const news = ref(null);

onMounted(() => {
    api.get("news/3").then((response) => {
        news.value = response.data.news;
    });
});
</script>

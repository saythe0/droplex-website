<template>
    <header class="header">
        <div class="container header__container">
            <div class="header__head">
                <div class="header__text">
                    <h1 class="header__title">
                        <span>Droplex</span>
                        <br />
                        <span>игровые сервера minecraft с модами</span>
                        <span>MineCraft проект</span>
                    </h1>
                    <p class="header__description">
                        Каждый игрок ищет уютную атмосферу и интересный
                        геймплей, отзывчивую модерацию и баланс на сервере.
                        <br />
                        Всё это есть на проекте нового поколения - Droplex!
                    </p>
                </div>
                <div class="header__buttons">
                    <button class="button button__primary">Начать игру</button>
                    <button class="button button__dark">Наши сервера</button>
                </div>
            </div>
            <div class="header__footer">
                <div class="header__footer-menu">
                    <a href="/vk" target="_blank" class="header__footer-link">
                        <i class="icon__vk"></i>
                    </a>
                    <a href="/ds" target="_blank" class="header__footer-link">
                        <i class="icon__ds"></i>
                    </a>
                    <a href="/yt" target="_blank" class="header__footer-link">
                        <i class="icon__yt"></i>
                    </a>
                </div>
                <hr class="header__footer-line" />
                <p class="header__footer-date">
                    {{ moment(new Date()).format("YYYY") }}
                </p>
            </div>
        </div>
    </header>
</template>

<script setup>
import useStore from "@/store/store";
import moment from "moment";

const store = useStore();
</script>

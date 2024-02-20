<template>
    <div class="wrapper">
        <notifications pauseOnHover="true" />

        <Navigation />

        <Header v-if="route.name == 'Main'" />

        <main class="main mt-100px">
            <router-view />
        </main>

        <Footer />
    </div>

    <Modals />
</template>

<script setup>
import { ref, watchEffect } from "vue";
import Navigation from "@/components/Navigation.vue";
import Footer from "@/components/Footer.vue";
import Modals from "./Modals.vue";
import { useRoute } from "vue-router";
import mixin from "../js/mixins";
import Header from "./components/Header.vue";

const route = useRoute();
const data = mixin.data();

watchEffect(() => {
    $("title").html(
        data.projectName + " | " + route.meta.title || data.projectName
    );
    $("meta[property='og:title']").attr(
        "content",
        data.projectName + " | " + route.meta.title || data.projectName
    );
    $("meta[property='og:description']").attr(
        "content",

        "Каждый игрок ищет уютную атмосферу и интересный геймплей, отзывчивую модерацию и баланс на сервере. Всё это есть на проекте нового поколения - " +
            data.projectName +
            "!"
    );
});
</script>

<template>
    <div class="cabinet-block">
        <h4 class="cabinet-block__name">Привилегии</h4>

        <div class="row g-20px">
            <div
                class="col-12 col-xl-6 col-xxl-4"
                v-for="(donate, index) in donates"
            >
                <div :class="'donate-block donate-block__item-' + index">
                    <div class="donate-block__chapter">
                        <h1 class="donate-block__name">{{ donate.name }}</h1>
                        <div
                            class="select-block select-block__mini w-auto"
                            data-select
                        >
                            <select name="" id="dsd" v-model="period">
                                <option value="1" selected>1 месяц</option>
                                <option value="2">2 месяца</option>
                                <option value="3">3 месяца</option>
                                <option value="6">6 месяцев</option>
                            </select>
                        </div>
                    </div>
                    <div class="donate-block__chapter">
                        <p class="donate-block__amount">{{ donate.price }}₽</p>
                        <button
                            class="button button__primary px-big mini"
                            @click="buy(donate.id)"
                        >
                            Купить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import api from "@/api";
import useStore from "@/store/store";

const store = useStore();

const period = ref(1);
const donates = ref([]);

function buy(donateId) {
    api.post("shop/buy/donate", {
        id: donateId,
        period: period.value,
        server: store.actionServer,
    }).then((res) => {
        console.log(res.data);
    });
}

function load() {
    api.post("shop/load", {
        type: "donates",
        server: store.actionServer,
    }).then((res) => {
        donates.value = res.data.donates;
    });
}

watch(
    () => store.actionServer,
    (newValue, oldValue) => {
        load();
    }
);

onMounted(() => {
    load();
});
</script>

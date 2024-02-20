<template>
    <div class="row g-30px">
        <div class="col-12 col-xl-6">
            <div class="cabinet-block gap-25px">
                <h4 class="cabinet-block__name">Обмен коинов</h4>
                <div class="row g-15px">
                    <div class="col-5">
                        <div class="input-block" v-if="!changePosition">
                            <input
                                type="number"
                                class="input"
                                id="exchangeRubles"
                                v-model="rubles"
                                @input="changeRubles"
                            />
                            <label for="exchangeRubles" class="input-label">
                                Рубли:
                            </label>
                        </div>
                        <div class="input-block" v-else>
                            <input
                                type="number"
                                class="input"
                                id="exchangeCoins"
                                v-model="coins"
                                @input="changeCoins"
                            />
                            <label for="exchangeCoins" class="input-label">
                                Коины:
                            </label>
                        </div>
                    </div>
                    <div class="col-2 d-flex">
                        <!-- @click="changePosition = !changePosition" -->
                        <button
                            class="button button__dark mt-auto"
                            style="padding: 14.5px 14px"
                        >
                            <i class="icon__exchange fz-20"></i>
                        </button>
                    </div>
                    <div class="col-5">
                        <div class="input-block" v-if="!changePosition">
                            <input
                                type="number"
                                class="input not-focus"
                                id="exchangeCoins"
                                v-model="coins"
                                readonly
                            />
                            <label for="exchangeCoins" class="input-label">
                                Коины:
                            </label>
                        </div>
                        <div class="input-block" v-else>
                            <input
                                type="number"
                                class="input not-focus"
                                id="exchangeRubles"
                                v-model="rubles"
                                readonly
                            />
                            <label for="exchangeRubles" class="input-label">
                                Рубли:
                            </label>
                        </div>
                    </div>
                </div>
                <button
                    class="button button__primary mt-10px"
                    @click="exchange"
                >
                    Обменять
                    {{ changePosition ? coins + " коин." : rubles + " руб." }}
                    <br />
                    на

                    {{ changePosition ? rubles + " руб." : coins + " коин." }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import api from "@/api";
import useStore from "@/store/store";

const store = useStore();

const changePosition = ref(0);
const rubles = ref(0);
const coins = ref(0);

function changeRubles() {
    coins.value = rubles.value * 2;
}

function changeCoins() {
    rubles.value = coins.value / 2;
}

function exchange() {
    api.post("user/exchange", {
        rubles: rubles.value,
        coins: coins.value,
    }).then((res) => {
        console.log(res.data);
    });
}

function load() {
    console.log("loaded");
}

onMounted(() => {
    load();
});
</script>

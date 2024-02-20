<template>
    <BModal
        v-if="store.isAuth && (!store.user.hd_skin || !store.user.hd_cloak)"
        v-model="store.modals.buyHD"
        @hide="store.toggleModal('buyHD')"
        centered="true"
        hideFooter="true"
        hideHeader="true"
        dialogClass="custom-modal__wrapper"
        contentClass="modal-content__custom"
        bodyClass="custom-modal"
    >
        <button
            class="modal-button-close"
            type="button"
            @click="store.toggleModal('buyHD')"
        >
            <i class="icon__close"></i>
        </button>

        <h1 class="custom-modal__title">Доступ к HD скинам и плащам.</h1>

        <div class="row g-30px align-items-center">
            <div class="col-md-6 col-12">
                <div
                    class="cabinet-block cabinet-block__bg-hd-skin"
                    style="min-height: 236px"
                >
                    <h4 class="cabinet-block__name">Доступ к HD скинам</h4>
                    <div
                        class="cabinet-block__body gap-15px align-items-start mt-auto"
                    >
                        <p class="cabinet-block__text">
                            <b>{{ skin_price }} ₽</b> / навсегда
                        </p>
                        <button
                            :class="
                                'button button__dark px-very-big' +
                                (store.user.hd_skin ? ' button__disabled' : '')
                            "
                            :disabled="store.user.hd_skin ? true : false"
                            @click="buySkin"
                        >
                            Купить
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div
                    class="cabinet-block cabinet-block__bg-hd-cloak"
                    style="min-height: 236px"
                >
                    <h4 class="cabinet-block__name">Доступ к HD плащам</h4>
                    <div
                        class="cabinet-block__body gap-15px align-items-start mt-auto"
                    >
                        <p class="cabinet-block__text">
                            <b>{{ cloak_price }} ₽</b> / навсегда
                        </p>
                        <button
                            :class="
                                'button button__dark px-very-big' +
                                (store.user.hd_cloak ? ' button__disabled' : '')
                            "
                            :disabled="store.user.hd_cloak ? true : false"
                            @click="buyCloak"
                        >
                            Купить
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div
                    class="cabinet-block cabinet-block__bg-hd"
                    style="min-height: 236px"
                >
                    <div class="position-relative w-fit-content">
                        <h4 class="cabinet-block__name">
                            Доступ к HD скинам и HD плащам
                        </h4>
                        <p class="cabinet-block__discount">-{{ discount }}%</p>
                    </div>
                    <div
                        class="cabinet-block__body gap-15px align-items-start mt-auto"
                    >
                        <div>
                            <p
                                class="cabinet-block__text opacity-50 fz-14 text-decoration-line-through"
                            >
                                <b>
                                    {{
                                        Number(skin_price) + Number(cloak_price)
                                    }}
                                    ₽ / навсегда
                                </b>
                            </p>
                            <p class="cabinet-block__text">
                                <b>{{ hd_price }} ₽</b> / навсегда
                            </p>
                        </div>
                        <button
                            :class="
                                'button button__dark' +
                                (store.user.hd_skin || store.user.hd_cloak
                                    ? ' button__disabled'
                                    : '')
                            "
                            style="min-width: 50%"
                            :disabled="
                                store.user.hd_skin || store.user.hd_cloak
                                    ? true
                                    : false
                            "
                            @click="buyHD"
                        >
                            Купить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </BModal>
</template>

<script setup>
import { onMounted, ref } from "vue";
import api from "@/api";
import useStore from "@/store/store";

const store = useStore();

const skin_price = ref(0);
const cloak_price = ref(0);
const discount = ref(0);
const hd_price = ref(0);

function buySkin() {
    api.post("user/buy/skin").then((res) => {
        if (res.data.success) store.getUser();
    });
}

function buyCloak() {
    api.post("user/buy/cloak").then((res) => {
        if (res.data.success) store.getUser();
    });
}

function buyHD() {
    api.post("user/buy/hd").then((res) => {
        if (res.data.success) store.getUser();
    });
}

function load() {
    api.get("core/hd").then((res) => {
        skin_price.value = res.data.skin_price;
        cloak_price.value = res.data.cloak_price;
        discount.value = res.data.discount;
        hd_price.value = res.data.hd_price;
    });
}

onMounted(() => {
    load();
});
</script>

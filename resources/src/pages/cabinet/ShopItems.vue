<template>
    <div class="cabinet-block">
        <h4 class="cabinet-block__name">Магазин предметов</h4>

        <div class="row g-20px">
            <div class="col-12 col-xl-6 col-xxl-4" v-for="item in items">
                <div class="shop-block">
                    <h5 class="shop-block__text">{{ item.name }}</h5>
                    <div class="shop-block__image">
                        <img :src="item.image" alt="" />
                    </div>
                    <div class="shop-block__footer">
                        <p class="shop-block__text">{{ item.price }}₽</p>
                        <button
                            class="button button__primary px-big mini"
                            @click="openModalBuy(item)"
                        >
                            Купить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <BModal
        v-if="dataItem"
        v-model="modalBuy"
        @hide="modalBuy = false"
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
            @click="modalBuy = false"
        >
            <i class="icon__close"></i>
        </button>

        <h1 class="custom-modal__title">Покупка {{ dataItem.name }}</h1>

        <div class="d-flex flex-column gap-30px">
            <div class="row g-28px align-items-center">
                <div class="col-auto">
                    <div class="block__image">
                        <img :src="dataItem.image" alt="" />
                    </div>
                </div>
                <div class="col">
                    <div class="cabinet-block">
                        <div class="table-dotted">
                            <div class="table-dotted__item">
                                <p class="table-dotted__name">
                                    Номер предмета:
                                </p>
                                <p
                                    class="table-dotted__value icon-after"
                                    @click="copyText('#' + dataItem.id)"
                                >
                                    #{{ dataItem.id }}
                                </p>
                            </div>

                            <div
                                class="table-dotted__item"
                                v-if="dataItem.item_id"
                            >
                                <p class="table-dotted__name">ID предмета:</p>
                                <p
                                    class="table-dotted__value icon-after"
                                    @click="copyText(dataItem.item_id)"
                                >
                                    {{ dataItem.item_id }}
                                </p>
                            </div>

                            <div
                                class="table-dotted__item"
                                v-if="dataItem.item_type"
                            >
                                <p class="table-dotted__name">
                                    Полное название:
                                </p>
                                <p
                                    class="table-dotted__value icon-after"
                                    @click="copyText(dataItem.item_type)"
                                >
                                    {{ dataItem.item_type }}
                                </p>
                            </div>

                            <div class="table-dotted__item">
                                <p class="table-dotted__name">Стоимость:</p>
                                <p
                                    class="table-dotted__value icon-after"
                                    @click="
                                        copyText(
                                            dataItem.price +
                                                ' руб. за ' +
                                                dataItem.count +
                                                ' шт.'
                                        )
                                    "
                                >
                                    {{ dataItem.price }} руб. за
                                    {{ dataItem.count }} шт.
                                </p>
                            </div>

                            <div class="table-dotted__item">
                                <p class="table-dotted__name">Зачарование:</p>
                                <p
                                    class="table-dotted__value icon-after"
                                    @click="
                                        copyText(
                                            dataItem.enchantments_status
                                                ? 'Возможно'
                                                : 'Недоступно'
                                        )
                                    "
                                >
                                    {{
                                        dataItem.enchantments_status
                                            ? "Возможно"
                                            : "Недоступно"
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="d-flex justify-content-between align-items-center gap-15px"
            >
                <!-- <h4 class="text-22">Сервер: {{ store.actionServer }}</h4> -->
                <a
                    :href="'https://vk.me/' + serverVkGroupId"
                    target="_blank"
                    class="ms-auto button button__primary px-x2-big"
                    v-if="dataItem.buy_vk"
                >
                    Купить через группу ВК
                </a>
                <button
                    class="button button__primary px-x2-big"
                    v-else
                    @click="buy"
                >
                    Купить
                </button>
            </div>
        </div>
    </BModal>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import api from "@/api";
import useStore from "@/store/store";

const store = useStore();

const items = ref([]);
const dataItem = ref([]);
const modalBuy = ref(false);
const count = ref(1);
const serverVkGroupId = ref("");

function openModalBuy(itemId) {
    dataItem.value = itemId;
    modalBuy.value = true;
    count.value = 1;
}

function buy(itemId) {
    api.post("/shop/buy/item", {
        id: itemId,
        count: count.value,
        server: store.actionServer,
    }).then((res) => {
        console.log(res.data);
        if (res.data.success) modalBuy.value = false;
    });
}

function load() {
    api.post("/shop/load", {
        type: "items",
        server: store.actionServer,
    }).then((res) => {
        items.value = res.data.items;
        serverVkGroupId.value = res.data.group_vk_id;
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

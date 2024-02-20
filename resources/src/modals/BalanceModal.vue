<template>
    <BModal
        v-if="store.isAuth"
        v-model="store.modals.balance"
        @hide="store.toggleModal('balance')"
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
            @click="store.toggleModal('balance')"
        >
            <i class="icon__close"></i>
        </button>

        <h1 class="custom-modal__title">Пополнить баланс</h1>

        <div class="row g-30px">
            <div class="col-4">
                <div
                    class="balance-block"
                    :class="bonusPercent == 0 ? 'active' : ''"
                    @click="addBonus(0)"
                >
                    <img
                        src="/assets/images/payments/coins-1.png"
                        alt=""
                        class="balance-block__image"
                    />
                    <p class="balance-text">200 ₽</p>
                </div>
            </div>

            <div class="col-4">
                <div
                    class="balance-block"
                    :class="bonusPercent == 10 ? 'active' : ''"
                    @click="addBonus(10)"
                >
                    <p class="balance-block__factor">+ 10%</p>
                    <img
                        src="/assets/images/payments/coins-2.png"
                        alt=""
                        class="balance-block__image"
                    />
                    <p class="balance-text">500 ₽ + 50</p>
                </div>
            </div>

            <div class="col-4">
                <div
                    class="balance-block"
                    :class="bonusPercent == 20 ? 'active' : ''"
                    @click="addBonus(20)"
                >
                    <p class="balance-block__factor">+ 20%</p>
                    <img
                        src="/assets/images/payments/coins-3.png"
                        alt=""
                        class="balance-block__image"
                    />
                    <p class="balance-text">1000 ₽ + 200</p>
                </div>
            </div>

            <div class="col-12">
                <div class="cabinet-block cabinet-block__bg-money gap-78px">
                    <h4 class="cabinet-block__name">
                        Введите желаемую сумму для пополнения:
                    </h4>
                    <div class="d-flex flex-wrap align-items-end gap-25px">
                        <div
                            class="input-block input-not-label"
                            style="max-width: 322px"
                        >
                            <input
                                type="number"
                                class="input"
                                placeholder="Введите сумму..."
                                min="1"
                                v-model="sum"
                            />
                            <div class="input-group">
                                <button
                                    class="button button__dark"
                                    @click="sum += 10"
                                >
                                    +10 ₽
                                </button>
                                <button
                                    class="button button__dark"
                                    @click="sum += 100"
                                >
                                    +100 ₽
                                </button>
                            </div>
                        </div>

                        <div class="balance-bonus">
                            <p class="balance-bonus__text">Бонус:</p>
                            <p class="balance-bonus__value">
                                + {{ bonus ? bonus : "0" }}₽
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="col-12 d-flex align-items-center justify-content-end gap-30px"
            >
                <div class="balance-text">{{ sum ? sum : "0" }} ₽</div>
                <button
                    class="button button__primary px-very-big"
                    @click="replenishment"
                >
                    Перейти к оплате
                </button>
            </div>
        </div>
    </BModal>
</template>

<script setup>
import { ref, watch } from "vue";
import api from "@/api";
import useStore from "@/store/store";

const store = useStore();

const sum = ref(200);
const bonus = ref(0);
const bonusPercent = ref(0);
const selectMethod = ref("freekassa");

function replenishment() {
    api.post("user/replenishment", {
        method: selectMethod.value,
        amount: sum.value,
    }).then((response) => {
        if (response.data.success && response.data.url)
            window.location = response.data.url;
    });
}

function addBonus(value) {
    if (value == 0) {
        sum.value = 200;
        bonus.value = 0;
        bonusPercent.value = 0;
    } else if (value == 10) {
        sum.value = 500;
        bonus.value = 50;
        bonusPercent.value = 10;
    } else if (value == 20) {
        sum.value = 1000;
        bonus.value = 200;
        bonusPercent.value = 20;
    }
}

watch(sum, async (newSum, oldSum) => {
    if (newSum > 0 && newSum < 500) {
        bonus.value = 0;
        bonusPercent.value = 0;
    } else if (newSum >= 500 && newSum < 1000) {
        bonus.value = Math.round((newSum / 100) * 10);
        bonusPercent.value = 10;
    } else if (newSum >= 1000) {
        bonus.value = Math.round((newSum / 100) * 20);
        bonusPercent.value = 20;
    }

    if (newSum < 0) {
        sum.value = 1;
    }
});
</script>

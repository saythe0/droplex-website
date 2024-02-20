<template>
    <BModal
        v-if="store.isAuth"
        v-model="store.modals.spawnTeleport"
        @hide="store.toggleModal('spawnTeleport')"
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
            @click="store.toggleModal('spawnTeleport')"
        >
            <i class="icon__close"></i>
        </button>

        <h1 class="custom-modal__title">Телепортация на спавн</h1>

        <div class="cabinet-block cabinet-block__bg-lag-zone">
            <div class="cabinet-block__head">
                <h4 class="cabinet-block__name">Ты застрял в баг-зоне?</h4>
                <p class="cabinet-block__description">
                    Бесплатная телепортация к спавну. Если вы потеряли ресурсы,
                    отпишите
                    <a href="#" class="link d-inline-flex">администраторам</a>.
                </p>
            </div>
            <div class="cabinet-block__body gap-15px" style="max-width: 322px">
                <div class="select-block">
                    <label
                        for="spawnTeleportServers"
                        class="select-block__label"
                        >Выберите сервер:</label
                    >
                    <select id="spawnTeleportServers" v-model="server">
                        <option
                            v-for="server in store.servers"
                            :value="server.id"
                        >
                            {{ server.name }}
                        </option>
                    </select>
                </div>

                <button class="button button__dark mt-15px" @click="teleport">
                    Телепортироваться
                </button>
            </div>
        </div>
    </BModal>
</template>

<script setup>
import { ref } from "vue";
import api from "@/api";
import { notify } from "@kyvg/vue3-notification";
import useStore from "@/store/store";

const store = useStore();

const server = ref(false);

function teleport() {
    if (!server.value) {
        notify({
            title: "Ошибка",
            text: "Выберите сервер для телепортации",
            type: "warn",
        });
        return;
    }

    api.post("user/teleport", {
        server: server.value,
    });
}
</script>

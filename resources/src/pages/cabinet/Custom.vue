<template>
    <div class="cabinet">
        <div class="row g-30px">
            <div class="col-auto d-flex flex-column gap-30px">
                <div class="cabinet-block cabinet-block__mini gap-20px">
                    <h4 class="cabinet-block__name">Ваш скин</h4>
                    <button
                        :class="
                            'cabinet-block__button fade button button__dark icon__mini ' +
                            (previewSkin ? '' : 'show')
                        "
                        @click="
                            side2dSkin ? (side2dSkin = 0) : (side2dSkin = 1)
                        "
                    >
                        <i class="icon__rotation fz-26"></i>
                    </button>
                    <div class="cabinet-block__body gap-0">
                        <div
                            :class="
                                'skin__2d fade ' +
                                (previewSkin ? 'h-0' : 'show')
                            "
                        >
                            <img
                                :src="store.skin.front"
                                alt="front"
                                :class="'fade ' + (side2dSkin ? 'w-0' : 'show')"
                            />
                            <img
                                :src="store.skin.back"
                                alt="back"
                                :class="'fade ' + (side2dSkin ? 'show' : 'w-0')"
                            />
                        </div>
                        <div
                            :class="
                                'skin__3d fade ' +
                                (previewSkin ? 'show' : 'h-0')
                            "
                        >
                            <canvas id="skin_container"></canvas>
                        </div>
                    </div>
                    <div class="cabinet-block__buttons group-button">
                        <button
                            :class="
                                'group-button__button ' +
                                (previewSkin ? 'active' : '')
                            "
                            @click="previewSkin = 1"
                        >
                            Скин 3D
                        </button>
                        <button
                            :class="
                                'group-button__button ' +
                                (previewSkin ? '' : 'active')
                            "
                            @click="previewSkin = 0"
                        >
                            Скин 2D
                        </button>
                    </div>
                </div>

                <div class="cabinet-block cabinet-block__mini g-20px">
                    <div class="row g-20px">
                        <div class="col">
                            <button
                                class="button button__dark px-0 w-100"
                                @click.prevent="uploadSkin"
                            >
                                Загрузить скин
                            </button>
                        </div>
                        <div class="col-auto" v-if="store.skinLoad">
                            <button
                                class="button button__dark p-triangle"
                                @click.prevent="deleteData('skin')"
                            >
                                <i class="icon__delete fz-20"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row g-20px">
                        <div class="col">
                            <button
                                class="button button__dark px-0 w-100"
                                @click.prevent="uploadCloak"
                            >
                                Загрузить плащ
                            </button>
                        </div>
                        <div class="col-auto" v-if="store.cloakLoad">
                            <button
                                class="button button__dark p-triangle"
                                @click.prevent="deleteData('cloak')"
                            >
                                <i class="icon__delete fz-20"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    class="cabinet-block cabinet-block__mini cabinet-block__bg-hd-buy gap-30px"
                    v-if="!store.user.hd_skin || !store.user.hd_cloak"
                >
                    <h4 class="cabinet-block__name">
                        HD доступ <span>(скины\плащи)</span>
                    </h4>
                    <div class="mt-auto">
                        <button
                            class="button button__primary w-100"
                            @click="store.toggleModal('buyHD')"
                        >
                            Купить доступ HD
                        </button>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="cabinet-block gap-20px">
                    <div class="d-flex flex-row align-items-start gap-20px">
                        <h4 class="cabinet-block__name">Каталог скинов</h4>

                        <div class="group-button group-button__mini ms-auto">
                            <button class="group-button__button">16x</button>
                            <button class="group-button__button">32x</button>
                            <button class="group-button__button">HD</button>
                        </div>
                    </div>

                    <div
                        class="row"
                        style="--bs-gutter-x: 17px; --bs-gutter-y: 25px"
                    >
                        <!-- <div class="col-3">
                            <div class="catalog-skin">
                                <img
                                    src="./skin-2d.png"
                                    alt=""
                                    class="catalog-skin__preview"
                                />
                                <div class="catalog-skin__button">
                                    <button
                                        class="button button__primary px-0 w-100 button__disabled"
                                        disabled
                                    >
                                        <i class="icon__install"></i>
                                        Установить
                                    </button>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <input
            style="display: none"
            id="skin_file"
            type="file"
            @change.prevent="uploadSkin($event)"
        />
        <input
            style="display: none"
            id="cloak_file"
            type="file"
            @change.prevent="uploadCloak($event)"
        />
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import useStore from "@/store/store";
import api from "@/api";
import * as skinview3d from "skinview3d";

const store = useStore();

const previewSkin = ref(1);
const side2dSkin = ref(0);
var skinViewer = null;

function uploadSkin(event) {
    if (event.target.files) {
        var formData = new FormData();
        formData.append("file", event.target.files[0]);
        upload(formData, "skin");
    } else $("#skin_file").click();
}

function uploadCloak(event) {
    if (event.target.files) {
        var formData = new FormData();
        formData.append("file", event.target.files[0]);
        upload(formData, "cloak");
    } else $("#cloak_file").click();
}

function upload(formData, type) {
    api.post("upload/" + type, formData, {
        headers: { "Content-Type": "multipart/form-data" },
    }).then(async (response) => {
        await localStorage.setItem(type + "_load", true);

        if (response.data.success) {
            await store.updateSkin();
            update3D();
        }
    });
}

function deleteData(type) {
    api.post("delete/" + type).then(async (response) => {
        await localStorage.setItem(type + "_load", false);

        if (response.data.success) {
            await store.updateSkin();
            update3D();
        }
    });
}

function update3D() {
    skinViewer.loadSkin(store.skin.file);
    skinViewer.loadCape(store.skin.cloak);
}

function initSkin() {
    skinViewer = new skinview3d.SkinViewer({
        canvas: document.getElementById("skin_container"),
        width: 257,
        height: 342,
        skin: store.skin.file,
        cape: store.skin.cloak,
        zoom: 0.9,
    });

    skinViewer.animation = new skinview3d.WalkingAnimation();
    skinViewer.animation.speed = 0.8;
}

onMounted(() => {
    initSkin();
});
</script>

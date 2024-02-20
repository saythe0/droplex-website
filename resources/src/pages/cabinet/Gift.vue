<template>
    <div class="row g-30px">
        <div class="col-12 col-xl-6">
            <div class="cabinet-block gap-25px">
                <h4 class="cabinet-block__name">Активировать промокод:</h4>
                <div class="input-block">
                    <!-- рандом типо может быть сделать не знаю ещё, речь про placeholder -->
                    <input
                        type="text"
                        class="input"
                        placeholder="XXX-XXXXXX-XX-XXXX"
                        id="activateCode"
                        v-model="code"
                    />
                    <label for="activateCode" class="input-label">
                        Введите промокод:
                    </label>
                </div>
                <button class="button button__primary" @click="activateCode">
                    Применить
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

const code = ref("");

function activateCode() {
    api.post("user/code/activate", {
        code: code.value,
    }).then((response) => {
        console.log(response.data);
        if (response.data.success) code.value = "";
    });
}
</script>

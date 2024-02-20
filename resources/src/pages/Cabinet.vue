<template>
    <div class="container gap-30px">
        <nav class="nav nav__page add-after">
            <div class="row g-0 w-100" style="--bs-gutter-x: 1px">
                <router-link
                    :to="{ name: 'Cabinet' }"
                    class="nav__item col-12 col-lg-3 col-sm-6"
                >
                    <i class="icon__user"></i>
                    Информация
                </router-link>
                <router-link
                    :to="{ name: 'CabinetCustom' }"
                    class="nav__item col-12 col-lg-3 col-sm-6"
                >
                    <i class="icon__custom"></i>
                    Персонализация
                </router-link>
                <router-link
                    :to="{ name: 'CabinetSafety' }"
                    class="nav__item col-12 col-lg-3 col-sm-6"
                >
                    <i class="icon__unlock"></i>
                    Безопасность
                </router-link>
                <router-link
                    :to="{ name: 'CabinetDonate' }"
                    class="nav__item col-12 col-lg-3 col-sm-6"
                >
                    <i class="icon__cart"></i>
                    Действия
                </router-link>
            </div>
        </nav>

        <router-view v-if="store.isAuth" />
    </div>
</template>

<script setup>
import useStore from "@/store/store";

const store = useStore();
</script>

<template>
    <LoginModal v-if="!store.isAuth" />
    <RegisterModal v-if="!store.isAuth" />
    <BalanceModal v-if="store.isAuth" />
    <TotpModal v-if="store.isAuth" />
    <SpawnTeleportModal v-if="store.isAuth" />
    <BuyHDModal
        v-if="store.isAuth && (!store.user.hd_skin || !store.user.hd_cloak)"
    />
</template>

<script setup>
import LoginModal from "@/modals/LoginModal.vue";
import RegisterModal from "@/modals/RegisterModal.vue";
import BalanceModal from "@/modals/BalanceModal.vue";
import TotpModal from "@/modals/TotpModal.vue";
import SpawnTeleportModal from "@/modals/SpawnTeleportModal.vue";
import BuyHDModal from "@/modals/BuyHDModal.vue";

import useStore from "@/store/store";

const store = useStore();
</script>

import { defineStore } from "pinia";
import api from "@/api.js";
import router from "@/router/router";
import { notify } from "@kyvg/vue3-notification";

const useStore = defineStore("store", {
    state: () => ({
        authUser: null,
        UserBan: null,
        UserDonates: null,
        UserPermissions: null,
        serverList: null,
        authorized: false,
        hwidBan: false,
        loadedData: false,

        skinLoadUser: false,
        cloakLoadUser: false,

        cartData: {
            count: 0,
            items: null,
        },

        modalList: {
            login: false,
            register: false,
            recovery: false,
            launcher: false,
            buyHD: false,
            balance: false,
            spawnTeleport: false,
            totp: false,
            buyShop: false,
        },

        skinList: {
            front: "/skin/default/front/342",
            back: "/skin/default/back/342",
            file: "/skin/default/file",
            cloak: "/skin/default/cloak",
            head: "/skin/default/head",
        },

        actionServer: "",
    }),
    getters: {
        isLoad: (state) => state.loadedData,
        user: (state) => state.authUser,
        bans: (state) => state.UserBan,
        donates: (state) => state.UserDonates,
        permissions: (state) => state.permissions,
        servers: (state) => state.serverList,
        isAuth: (state) => state.authorized,
        hwid: (state) => state.hwidBan,

        skinLoad: (state) => state.skinLoadUser,
        cloakLoad: (state) => state.cloakLoadUser,

        cart: (state) => state.cartData,

        skin: (state) => state.skinList,

        modals: (state) => state.modalList,
    },
    actions: {
        updateSkin() {
            this.skinList = {
                front:
                    "/skin/" +
                    (this.authUser ? this.authUser?.uuid : "default") +
                    "/front/342?v=" +
                    Date.now(),
                back:
                    "/skin/" +
                    (this.authUser ? this.authUser?.uuid : "default") +
                    "/back/342?v=" +
                    Date.now(),
                file:
                    "/skin/" +
                    (this.authUser ? this.authUser?.uuid : "default") +
                    "/file?v=" +
                    Date.now(),
                cloak:
                    "/skin/" +
                    (this.authUser ? this.authUser?.uuid : "default") +
                    "/cloak?v=" +
                    Date.now(),
                head:
                    "/skin/" +
                    (this.authUser ? this.authUser?.uuid : "default") +
                    "/head?v=" +
                    Date.now(),
            };

            if (localStorage.getItem("skin_load") === null) {
                localStorage.setItem("skin_load", false);
            } else if (localStorage.getItem("skin_load") == "true") {
                this.skinLoadUser = true;
            } else if (localStorage.getItem("skin_load") == "false") {
                this.skinLoadUser = false;
            }

            if (localStorage.getItem("cloak_load") === null) {
                localStorage.setItem("cloak_load", false);
            } else if (localStorage.getItem("cloak_load") == "true") {
                this.cloakLoadUser = true;
            } else if (localStorage.getItem("cloak_load") == "false") {
                this.cloakLoadUser = false;
            }
        },
        setActionServer(serverId) {
            this.actionServer = serverId;
        },
        toggleModal(modalName) {
            this.modalList[modalName] = !this.modalList[modalName];
        },
        async logout() {
            await api.get("logout");
            await this.getUser();
            router.push({ name: "Main" });
        },
        async getUser() {
            await api.get("core/load").then((res) => {
                this.authUser = res.data.user;
                this.UserBan = res.data.bans;
                this.UserDonates = res.data.donates;
                this.UserPermissions = res.data.permissions;
                this.serverList = res.data.servers;
                this.hwidBan = res.data.hwid;
                this.cartData = res.data.cart;
                this.authorized = res.data.authorized;

                if (this.serverList.length > 0) {
                    this.actionServer = this.serverList[0].id;
                }

                this.loadedData = true;
            });

            this.updateSkin();
        },
        updateUser() {
            this.getUser();

            notify({
                title: "Успешно!",
                text: "Информация обновлена",
                type: "success",
            });
        },
    },
});

export default useStore;

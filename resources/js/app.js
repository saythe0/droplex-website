import { createApp } from "vue";
import App from "@/App.vue";
import router from "@/router/router";
import { createPinia } from "pinia";
import useStore from "@/store/store";
import notifications, { notify } from "@kyvg/vue3-notification";
import mixins from "./mixins";
import BootstrapVueNext from "bootstrap-vue-next";
import * as bootstrap from "bootstrap";
import jQuery from "jquery";
import api from "@/api";

import "bootstrap-vue-next/dist/bootstrap-vue-next.css";

const pinia = createPinia();
const app = createApp(App);
const $ = jQuery;
window.$ = $;

app.use(bootstrap);
app.use(BootstrapVueNext);
app.use(pinia);
app.mixin(mixins);
app.use(router);

app.use(notifications);

app.mount("#app");

const store = useStore();

router.beforeEach(async (to, from, next) => {
    if (!store.isLoad) await store.getUser();

    if (to.meta.offline) {
        next({ name: "Main" });
        notify({
            title: "Ошибка",
            text: "Данная страница недоступна",
            type: "error",
        });
    } else if (to.matched.some((record) => record.meta.auth) && !store.isAuth) {
        next({ name: "Main" });
        store.toggleModal("login");
    } else {
        next();
    }
});

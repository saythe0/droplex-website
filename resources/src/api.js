import axios from "axios";
import { notify } from "@kyvg/vue3-notification";
import Cookies from "js-cookie";

const instance = axios.create({
    baseURL: "/api/",
    headers: {
        // "X-XSRF-TOKEN": Cookies.get("XSRF-TOKEN"),
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
    },
});

instance.defaults.withCredentials = true;

instance.interceptors.response.use(
    function (response) {
        if (response.data.hasOwnProperty("message")) {
            if (response.data.success) {
                notify({
                    title: "Успешно!",
                    text: response.data.message ? response.data.message : "",
                    type: "success",
                });
            } else {
                if (response.data.errors) {
                    Object.values(response.data.errors).forEach((arr) => {
                        arr.forEach((err) => {
                            notify({
                                title: "Ошибка",
                                text: err,
                                type: "error",
                            });
                        });
                    });
                } else {
                    notify({
                        title: "Ошибка",
                        text: response.data.message,
                        type: "error",
                    });
                }
            }
        }
        return response;
    },
    function (error) {
        notify({
            title: "Ошибка",
            text: error.message,
            type: "error",
        });
        console.error(`API error: ${error}`);
    }
);

export default instance;

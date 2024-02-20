import moment from "moment";
import "moment/locale/ru";

let projectName = import.meta.env.VITE_PROJECT_NAME;
let cdnUrl = import.meta.env.VITE_CDN_URL;
let vkGroupId = import.meta.env.VITE_VK_GROUP_ID;
let discordUrl = import.meta.env.VITE_DISCORD_URL;
let youtubeUrl = import.meta.env.VITE_YOUTUBE_URL;

export default {
    data() {
        return {
            projectName: projectName,
            vkGroupId: vkGroupId,
            discordUrl: discordUrl,
            youtubeUrl: youtubeUrl,
        };
    },
    mounted() {
        this.init();
        this.checkReferral();
    },
    methods: {
        dateFormat(date) {
            if (!date) return "";
            return moment(date).format("DD.MM.YYYY (HH:mm МСК)");
        },
        removeSpaces(text) {
            return text.replace(/ /g, "");
        },
        copyText(text) {
            navigator.clipboard.writeText(text);
        },
        init() {
            // tooltips
            $('[tooltip="top"]').attr("data-bs-placement", "top");
            $('[tooltip="left"]').attr("data-bs-placement", "left");
            $('[tooltip="bottom"]').attr("data-bs-placement", "bottom");
            $('[tooltip="right"]').attr("data-bs-placement", "right");

            // dropdown
            $("[dropdown]").addClass("position-relative");
            $('[dropdown] [role="button"]').attr("data-bs-toggle", "dropdown");
        },
        checkReferral() {
            var ref = this.$route.query.ref;
            if (ref) {
                localStorage.setItem("referral", ref);
            }
        },
    },
};

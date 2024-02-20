import Main from "@/pages/Main.vue";
import FAQ from "@/pages/FAQ.vue";
import Team from "@/pages/Team.vue";

import Rules from "@/pages/Rules.vue";
import Privacy from "@/pages/Privacy.vue";
import PublicOffer from "@/pages/PublicOffer.vue";

import Cabinet from "@/pages/Cabinet.vue";
import CabinetIndex from "@/pages/cabinet/Index.vue";
import CabinetCustom from "@/pages/cabinet/Custom.vue";
import CabinetSafety from "@/pages/cabinet/Safety.vue";
import CabinetActions from "@/pages/cabinet/Actions.vue";
import CabinetDonate from "@/pages/cabinet/Donate.vue";
import CabinetShopBlocks from "@/pages/cabinet/ShopBlocks.vue";
import CabinetShopItems from "@/pages/cabinet/ShopItems.vue";
import CabinetGift from "@/pages/cabinet/Gift.vue";
import CabinetExchange from "@/pages/cabinet/Exchange.vue";
import CabinetChat from "@/pages/cabinet/Chat.vue";

import NotFound from "@/pages/NotFound.vue";

const routes = [
    {
        path: "/",
        component: Main,
        name: "Main",
        meta: {
            auth: false,
            offline: false,
            title: "Main",
            description: "Описание страницы",
        },
    },
    {
        path: "/page/rules",
        component: Rules,
        name: "Rules",
        meta: {
            auth: false,
            offline: false,
            title: "Rules",
            description: "Описание страницы",
        },
    },
    {
        path: "/page/privacy",
        component: Privacy,
        name: "Privacy",
        meta: {
            auth: false,
            offline: false,
            title: "Privacy Policy",
            description: "Описание страницы",
        },
    },
    {
        path: "/page/public_offer",
        component: PublicOffer,
        name: "PublicOffer",
        meta: {
            auth: false,
            offline: false,
            title: "Public Offer",
            description: "Описание страницы",
        },
    },
    {
        path: "/page/team",
        component: Team,
        name: "Team",
        meta: {
            auth: false,
            offline: false,
            title: "Team",
            description: "Описание страницы",
        },
    },
    {
        path: "/page/faq",
        component: FAQ,
        name: "FAQ",
        meta: {
            auth: false,
            offline: false,
            title: "FAQ",
            description: "Описание страницы",
        },
    },
    {
        path: "/",
        component: Cabinet,
        meta: {
            auth: true,
            offline: false,
            title: "Cabinet",
            description: "Описание страницы",
        },
        children: [
            {
                path: "/cabinet",
                component: CabinetIndex,
                name: "Cabinet",
            },
            {
                path: "/cabinet/custom",
                component: CabinetCustom,
                name: "CabinetCustom",
            },
            {
                path: "/cabinet/safety",
                component: CabinetSafety,
                name: "CabinetSafety",
            },
            {
                path: "/cabinet/actions",
                component: CabinetActions,
                name: "CabinetActions",
                children: [
                    {
                        path: "/cabinet/donate",
                        component: CabinetDonate,
                        name: "CabinetDonate",
                    },
                    {
                        path: "/cabinet/blocks",
                        component: CabinetShopBlocks,
                        name: "CabinetShopBlocks",
                    },
                    {
                        path: "/cabinet/items",
                        component: CabinetShopItems,
                        name: "CabinetShopItems",
                    },
                    {
                        path: "/cabinet/gift",
                        component: CabinetGift,
                        name: "CabinetGift",
                    },
                    {
                        path: "/cabinet/exchange",
                        component: CabinetExchange,
                        name: "CabinetExchange",
                    },
                    {
                        path: "/cabinet/chat",
                        component: CabinetChat,
                        name: "CabinetChat",
                    },
                ],
            },
        ],
    },
    {
        path: "/:pathMatch(.*)*",
        component: NotFound,
        name: "Not Found",
        meta: {
            auth: false,
            offline: false,
            title: "Not Found",
            description: "Описание страницы",
        },
    },
];

export default routes;

import ChefComponent from "../../components/admin/chefs/ChefComponent.vue";
import ChefListComponent from "../../components/admin/chefs/ChefListComponent.vue";
import ChefShowComponent from "../../components/admin/chefs/ChefShowComponent.vue";
import ChefOrderDetailsComponent from "../../components/admin/chefs/ChefOrderDetailsComponent.vue";


export default [
    {
        path: "/admin/chefs",
        component: ChefComponent,
        name: "admin.chefs",
        redirect: {name: "admin.chefs.list"},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "chefs",
            breadcrumb: "chefs",
        },
        children: [
            {
                path: "",
                component: ChefListComponent,
                name: "admin.chefs.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "chefs",
                    breadcrumb: "",
                }
            },
            {
                path: "show/:id",
                component: ChefShowComponent,
                name: "admin.chefs.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "chefs",
                    breadcrumb: "view",
                }
            },
            {
                path: "show/:id/:orderId",
                component: ChefOrderDetailsComponent,
                name: "admin.chefs.order.details",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "chefs",
                    breadcrumb: "order_details",
                }
            },
        ],
    },
];

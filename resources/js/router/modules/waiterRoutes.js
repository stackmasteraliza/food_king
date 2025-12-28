import WaiterComponent from "../../components/admin/waiters/WaiterComponent.vue";
import WaiterListComponent from "../../components/admin/waiters/WaiterListComponent.vue";
import WaiterShowComponent from "../../components/admin/waiters/WaiterShowComponent.vue";
import WaiterOrderDetailsComponent from "../../components/admin/waiters/WaiterOrderDetailsComponent.vue";


export default [
    {
        path: "/admin/waiters",
        component: WaiterComponent,
        name: "admin.waiters",
        redirect: {name: "admin.waiters.list"},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "waiters",
            breadcrumb: "waiters",
        },
        children: [
            {
                path: "",
                component: WaiterListComponent,
                name: "admin.waiters.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "waiters",
                    breadcrumb: "",
                }
            },
            {
                path: "show/:id",
                component: WaiterShowComponent,
                name: "admin.waiters.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "waiters",
                    breadcrumb: "view",
                }
            },
            {
                path: "show/:id/:orderId",
                component: WaiterOrderDetailsComponent,
                name: "admin.waiters.order.details",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "waiters",
                    breadcrumb: "order_details",
                }
            },
        ],
    },
];

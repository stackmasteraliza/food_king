import SubscriberComponent from "../../components/admin/subscribers/SubscriberComponent";
import SubscriberListComponent from "../../components/admin/subscribers/SubscriberListComponent";

export default [
    {
        path: "/admin/subscribers",
        component: SubscriberComponent,
        name: "admin.subscribers",
        redirect: { name: "admin.subscribers.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "subscribers",
            breadcrumb: "subscribers",
        },
        children: [
            {
                path: "",
                component: SubscriberListComponent,
                name: "admin.subscribers.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "subscribers",
                    breadcrumb: "",
                },
            },
        ],
    },
];

import KitchenDisplaySystemComponent from "../../components/admin/kitchenDisplaySystem/KitchenDisplaySystemComponent";

export default [
    {
        path: "/admin/kitchen-display-system",
        component: KitchenDisplaySystemComponent,
        name: "admin.kitchen-display-system",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "kitchen-display-system",
        },
    },
];

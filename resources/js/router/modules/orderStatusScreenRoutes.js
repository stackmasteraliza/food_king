import OrderStatusScreenComponent from "../../components/admin/orderStatusScreen/OrderStatusScreenComponent";

export default [
    {
        path: "/admin/order-status-screen",
        component: OrderStatusScreenComponent,
        name: "admin.order-status-screen",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "order-status-screen",
        },
    },
];

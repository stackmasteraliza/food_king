import PosReturnComponent from "../../components/admin/pos/PosReturnComponent";

export default [
    {
        path: "/admin/pos",
        component: PosReturnComponent,
        name: "admin.PosReturn",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "pos",
        },
    },
];

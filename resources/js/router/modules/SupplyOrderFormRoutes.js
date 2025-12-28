import SupplyOrderForm from "../../components/admin/invetoryManagement/SupplyOrderForm";

export default [
    {
        path: "/admin/SupplyOrderForm",
        component: SupplyOrderForm,
        name: "admin.SupplyOrderForm",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "SupplyOrderForm",
            breadcrumb: 'SupplyOrderForm',
        },
    },
];

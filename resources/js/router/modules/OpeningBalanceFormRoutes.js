import OpeningBalanceForm from "../../components/admin/invetoryManagement/OpeningBalanceForm";

export default [
    {
        path: "/admin/OpeningBalanceForm",
        component: OpeningBalanceForm,
        name: "admin.OpeningBalanceForm",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "OpeningBalanceForm",
            breadcrumb: 'OpeningBalanceForm',
        },
    },
];

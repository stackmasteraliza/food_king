import DisbursementOrderForm from "../../components/admin/invetoryManagement/DisbursementOrderForm";

export default [
    {
        path: "/admin/DisbursementOrderForm",
        component: DisbursementOrderForm,
        name: "admin.DisbursementOrderForm",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "DisbursementOrderForm",
            breadcrumb: 'DisbursementOrderForm',
        },
    },
];

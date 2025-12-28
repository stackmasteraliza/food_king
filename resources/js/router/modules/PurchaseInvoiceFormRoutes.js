import PurchaseInvoiceForm from "../../components/admin/invetoryManagement/PurchaseInvoiceForm";

export default [
    {
        path: "/admin/PurchaseInvoiceForm",
        component: PurchaseInvoiceForm,
        name: "admin.PurchaseInvoiceForm",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "PurchaseInvoiceForm",
            breadcrumb: 'PurchaseInvoiceForm',
        },
    },
];

import InventoryReport from "../../components/admin/invetoryManagement/InventoryReport";

export default [
    {
        path: "/admin/InventoryReport",
        component: InventoryReport,
        name: "admin.InventoryReport",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "InventoryReport",
            breadcrumb: 'InventoryReport',
        },
    },
];

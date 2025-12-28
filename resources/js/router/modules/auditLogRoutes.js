import auditLogComponent from "../../components/admin/auditLog/auditLogComponent";
import auditLogListComponent from "../../components/admin/auditLog/auditLogListComponent";

export default [
    {
        path: "/admin/auditLog",
        component: auditLogComponent,
        name: "admin.auditLog",
        redirect: { name: "admin.auditLog.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "auditLog",
            breadcrumb: "auditLog",
        },
        children: [
            {
                path: "",
                component: auditLogListComponent,
                name: "admin.auditLog.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "auditLog",
                    breadcrumb: "",
                },
            },
        ],

    },
];  

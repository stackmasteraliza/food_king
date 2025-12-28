import posSessionComponent from "../../components/admin/posSession/posSessionComponent";

export default [
    {
        path: "/admin/posSession",
        component: posSessionComponent,
        name: "admin.posSession",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "posSession",
            breadcrumb: 'posSession',
        },
    },
];

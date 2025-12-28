import PushNotificationComponent from "../../components/admin/pushNotification/PushNotificationComponent";
import PushNotificationListComponent from "../../components/admin/pushNotification/PushNotificationListComponent";
import PushNotificationShowComponent from "../../components/admin/pushNotification/PushNotificationShowComponent";

export default [
    {
        path: '/admin/push-notifications',
        component: PushNotificationComponent,
        name: 'admin.push-notifications',
        redirect: { name: 'admin.push-notification.list' },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'push-notifications',
            breadcrumb: 'push_notifications'
        },
        children: [
            {
                path: '',
                component: PushNotificationListComponent,
                name: 'admin.push-notification.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'push-notifications',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: PushNotificationShowComponent,
                name: "admin.push-notification.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "push-notifications",
                    breadcrumb: "view",
                },
            },
        ]
    }
]

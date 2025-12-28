import AppMarketplaceComponent from "../../components/admin/AppMarketplace/AppMarketplaceComponent";

export default [
    {
        path: '/admin/AppMarketplace',
        component: AppMarketplaceComponent,
        name: 'admin.AppMarketplace.list',
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'AppMarketplace',
            breadcrumb: 'AppMarketplace'
        }
    }
]

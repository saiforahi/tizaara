import Vue from 'vue'
import Router from 'vue-router'
import multiguard from 'vue-router-multiguard';
import store from '../core/services/store'

Vue.use(Router)

const isLoggedIn = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        next();
    } else next({name: 'home'})
}

const isApproval = (to, from, next) => {
    if (store.getters.currentUser.status == 2) {
        next();
    } else next({name: 'verify-admin'})
}

export default new Router({
    mode: 'history',
    routes: [
        {
            path: "/",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Tizaara.com: Suppliers,  Manufacturers,  Exporters  Importers online B2B marketplace'},
                    path: "/",
                    name: "home",
                    component: () => import("@/components/Home")
                },
                {
                    meta: {title: 'Ecom Zone | Tizaara'},
                    path: "/ecom-zone",
                    name: "Ecommerce Zone",
                    component: () => import("@/components/page/EcomZoneList")
                },
                {
                    meta: {title: 'Flash Deal | Tizaara'},
                    path: "/flash-deal/:slug",
                    name: "Flash Deal",
                    component: () => import("@/components/page/FlashDeal")
                },
                {
                    meta: {title: 'Categories | Tizaara'},
                    path: "/category/:cat?/:sub?/:subcat?",
                    name: "category",
                    component: () => import("@/components/product/new")
                },
                // {
                //     meta: {title: 'Categories'},
                //     path: "/category/:cat/:sub",
                //     name: "category-sub",
                //     component: () => import("@/components/product/new")
                // },
                {
                    meta: {title: 'All Categories List | Tizaara'},
                    path: "/categories",
                    name: "All Categories",
                    component: () => import("@/components/others/Categories"),
                },
                { //single product page routes
                    meta: {title: 'Single Product'},
                    path: "/single/product/:slug",
                    name: "single.product",
                    props:true,
                    component: () => import("@/components/product/SingleProduct"),
                },
                { //top city product page routes
                    meta: {title: 'Top city product | Tizaara'},
                    path: "/top/city/product:top_city_id",
                    name: "top.city.product",
                    props:true,
                    component: () => import("@/components/product/TopCityProduct"),
                },
                { //popular items product page routes
                    meta: {title: 'Popular items | Tizaara'},
                    path: "/popular/items",
                    name: "popular.item",
                    component: () => import("@/components/product/PopularItem"),
                },
                { //top supplier page routes
                    meta: {title: 'Top Supplier | Tizaara'},
                    path: "/top/suppliers",
                    name: "top.suppliers",
                    component: () => import("@/components/product/TopSupplier"),
                },
                { //verified supplier page routes
                    meta: {title: 'Verified Supplier | Tizaara'},
                    path: "/verified/suppliers",
                    name: "verified.suppliers",
                    component: () => import("@/components/product/VerifiedSupplier"),
                },
                { //cart page route
                    meta: {title: 'Cart | Tizaara'},
                    path: "/cart/view",
                    name: "cart.view",
                    props:true,
                    component: () => import("@/components/cart/Cart"),
                },
                { //cart page route
                    meta: {title: 'Checkout | Tizaara'},
                    path: "/cart/checkout",
                    name: "cart.checkout",
                    props:true,
                    component: () => import("@/components/cart/Checkout"),
                },
                { //cart page route
                    meta: {title: 'Checkout Success | Tizaara'},
                    path: "/checkout/success",
                    name: "checkout.success",
                    props:true,
                    component: () => import("@/components/cart/Success"),
                },
                { //cart page route
                    meta: {title: 'Checkout Error | Tizaara'},
                    path: "/checkout/error",
                    name: "checkout.error",
                    props:true,
                    component: () => import("@/components/cart/Error"),
                },
                { //cart page route
                    meta: {title: 'Checkout Error | Tizaara'},
                    path: "/checkout/cancel",
                    name: "checkout.cancel",
                    props:true,
                    component: () => import("@/components/cart/Error"),
                },
                {
                    meta: {title: 'Terms and Condition | Tizaara'},
                    path: "/terms-conditions",
                    name: "terms-conditions",
                    component: () => import("@/components/page/Terms"),
                },
                {
                    meta: {title: 'Privacy Policy | Tizaara'},
                    path: "/privacy-policy",
                    name: "privacy-policy",
                    component: () => import("@/components/page/Privacy"),
                },
                {
                    meta: {title: 'About Us | Tizaara'},
                    path: "/about-us",
                    name: "about-us",
                    component: () => import("@/components/page/About"),
                },
                {
                    meta: {title: 'Join our Sales Team | Tizaara'},
                    path: "/join-sales",
                    name: "join-sales",
                    component: () => import("@/components/page/JoinSales"),
                },
                {
                    meta: {title: 'Help Center | Tizaara'},
                    path: "/help-center",
                    name: "help-center",
                    component: () => import("@/components/page/Help"),
                },
                {
                    meta: {title: 'Help Center | Tizaara'},
                    path: "/help-category",
                    name: "help-category",
                    component: () => import("@/components/page/HelpCategory"),
                }
            ]
        },
        {
            path: "/my-favorite",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'My Favorite | Tizaara'},
                    path: "/",
                    name: "my-favorite",
                    component: () => import("@/components/user/MyFavorite"),
                }
            ]
        },
        {
            path: "/contact-supplier",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Contact Supplier | Tizaara'},
                    path: ":id",
                    name: "contact-supplier",
                    component: () => import("@/components/page/ContactSupplier")
                },
            ]
        },
        {
            path: "/company/profile",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Company Profile | Tizaara'},
                    path: ":display_name",
                    name: "company-profile",
                    props: true,
                    component: () => import("@/components/page/CompnayProfile")
                },
            ]
        },
        {
            path: "/membership-plan",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Membership plan | Tizaara'},
                    path: "/",
                    name: "membership-plan",
                    component: () => import("@/components/page/MembershipPlan"),
                }
            ]
        },
        {
            path: "/dashboard",
            component: () => import("@/components/dashboard/layout/Layout"),
            beforeEnter: multiguard([isLoggedIn, isApproval]),
            children: [
                {
                    meta: {title: 'Dashboard'},
                    path: "/",
                    name: "Dashboard",
                    component: () => import("@/components/dashboard/Dashboard")
                },
                {
                    meta: {title: 'Create new product'},
                    path: "/dashboard/product-create",
                    name: "product-create",
                    component: () => import("@/components/dashboard/product/ProductCreate")
                },
                {
                    meta: {title: 'Edit product'},
                    path: "/dashboard/product/:id/edit",
                    name: "product-edit",
                    component: () => import("@/components/dashboard/product/ProductUpdate")
                },
                {
                    meta: {title: 'Product List'},
                    path: "/dashboard/product-list",
                    name: "product-list",
                    component: () => import("@/components/dashboard/product/ProductList")
                },
                {
                    meta: {title: 'Product Request'},
                    path: "/dashboard/product-request/:type?",
                    name: "product-request",
                    component: () => import("@/components/dashboard/product/ProductRequest")
                },
                {
                    meta: {title: 'Message'},
                    path: "/dashboard/message",
                    name: "Message",
                    component: () => import("@/components/dashboard/message/Conversation")
                },
                {
                    meta: {title: 'Profile'},
                    path: "/dashboard/profile",
                    name: "Profile",
                    component: () => import("@/components/dashboard/profile/Profile")
                },
                {
                    meta: {title: 'Order'},
                    path: "/dashboard/supplier/order",
                    name: "dashboard.supplier.order",
                    component: () => import("@/components/dashboard/supplier/Order")
                },
                {
                    meta: {title: 'Password Change'},
                    path: "/dashboard/password/change",
                    name: "password.change",
                    component: () => import("@/components/dashboard/PasswordChange")
                },
                {
                    meta: {title: 'Quotation List'},
                    path: "/dashboard/quotation",
                    name: "quotation-list",
                    component: () => import("@/components/dashboard/marketing/Quotation")
                },
                {
                    meta: {title: 'Membership plan'},
                    path: "/dashboard/membership/plan",
                    name: "dashboard.membership.plan",
                    component: () => import("@/components/dashboard/membership/MemberShipPlan")
                },
                {
                    meta: {title: 'Verify'},
                    path: "/dashboard/verify/supplier",
                    name: "dashboard.verify.supplier",
                    component: () => import("@/components/dashboard/supplier/VerifySupplier.vue")
                },
                {
                    meta: {title: 'Transaction'},
                    path: "/dashboard/buyer/transaction",
                    name: "dashboard.buyer.transaction",
                    component: () => import("@/components/dashboard/buyer/Transaction")
                },
                {
                    meta: {title: 'Quotation'},
                    path: "/dashboard/buyer/quotation",
                    name: "dashboard.buyer.quotation",
                    component: () => import("@/components/dashboard/buyer/Quotation")
                },
                {
                    meta: {title: 'Orders'},
                    path: "/dashboard/buyer/order",
                    name: "dashboard.buyer.order",
                    component: () => import("@/components/dashboard/buyer/Order")
                },
            ]
        },
        {
            path: "/verify",
            component: () => import("@/components/dashboard/layout/Layout"),
            beforeEnter: multiguard([isLoggedIn]),
            children: [
                {
                    meta: {title: 'User verify admin'},
                    path: "/verify/admin",
                    name: "verify-admin",
                    component: () => import("@/components/dashboard/other/Verify")
                }
            ]
        },
        {
            path: "*",
            name: "error",
            meta: {title: 'Error'},
            component: () => import("@/components/others/Error"),
        },
    ]
})

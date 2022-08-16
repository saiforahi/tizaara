import Vue from 'vue'
import Router from 'vue-router'


Vue.use(Router)

export default new Router({
    mode: 'history',
    routes: [
        {
            path: "/",
            redirect: "/dashboard",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Dashboard'},
                    path: "/dashboard",
                    name: "dashboard",
                    component: () => import("@/components/Dashboard.vue")
                }
            ]
        },
        {
            path: "/profile",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Profile'},
                    path: "/admin/profile",
                    name: "admin-profile",
                    component: () => import("@/components/profile")
                },
            ]
        },
        {
            path: "/product",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Brand '},
                    path: "/product/brand",
                    name: "Brand",
                    component: () => import("@/components/product/Brand")
                },
                {
                    meta: {title: 'Category'},
                    path: "/product/category",
                    name: "Category",
                    component: () => import("@/components/product/Category")
                },
                {
                    meta: {title: 'Sub-Category'},
                    path: "/product/sub-category",
                    name: "Subcategory",
                    component: () => import("@/components/product/SubCategory")
                },
                {
                    meta: {title: 'Sub-Subcategory'},
                    path: "/product/sub-subcategory",
                    name: "Sub Subcategory",
                    component: () => import("@/components/product/SubSubCategory")
                },
                {
                    meta: {title: 'Properties'},
                    path: "/product/properties",
                    name: "Properties",
                    component: () => import("@/components/product/Property")
                },
                {
                    meta: {title: 'Create New Properties'},
                    path: "/product/properties-create",
                    name: "New Properties",
                    component: () => import("@/components/product/product/PropertyCreate")
                },
                {
                    meta: {title: 'In House Products'},
                    path: "/product/product-admin",
                    name: "In House Products",
                    component: () => import("@/components/product/Product")
                },
                {
                    meta: {title: 'Seller Products'},
                    path: "/product/seller-product",
                    name: "seller-product",
                    component: () => import("@/components/product/SellerProduct")
                },
                {
                    meta: {title: 'Create New Products'},
                    path: "/product/product-admin-create",
                    name: "Create New Products",
                    component: () => import("@/components/product/product/ProductCreate")
                },
                {
                    meta: {title: 'Product Edit'},
                    path: "/product/product-edit/:id",
                    name: "Product Edit",
                    component: () => import("@/components/product/product/ProductEdit")
                },
                {
                    meta: {title: 'Products Group'},
                    path: "/product/product-group",
                    name: "Products Group",
                    component: () => import("@/components/product/Grope")
                },
                {
                    meta: {title: 'Create Products Group'},
                    path: "/product/product-group-create",
                    name: "Create Products Group",
                    component: () => import("@/components/product/product/GroupCreate")
                },
            ]
        },
        {
            path: "/product-upload",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Color '},
                    path: "/upload-manage/color",
                    name: "Color",
                    component: () => import("@/components/ecommerce/Color")
                },
                {
                    meta: {title: 'Attribute '},
                    path: "/upload-manage/attribute",
                    name: "Attribute",
                    component: () => import("@/components/ecommerce/Attribute")
                },
                {
                    meta: {title: 'Unit '},
                    path: "/upload-manage/unit",
                    name: "Unit",
                    component: () => import("@/components/ecommerce/Unit")
                },
                {
                    meta: {title: 'Business Type '},
                    path: "/upload-manage/business-type",
                    name: "Business Type",
                    component: () => import("@/components/ecommerce/BusinessType")
                },
                {
                    meta: {title: 'Currency '},
                    path: "/upload-manage/currency",
                    name: "Currency",
                    component: () => import("@/components/ecommerce/Currency")
                }
            ]
        },
        {
            path: "/customer",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'All Customer'},
                    path: "/user/customer-list",
                    name: "all-customer",
                    component: () => import("@/components/customer/CustomerList")
                },
            ]
        },
        {
            path: "/seller",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'All Seller'},
                    path: "/seller/all-seller",
                    name: "all-seller",
                    component: () => import("@/components/seller/AllSeller")
                },
                {
                    meta: {title: 'Verify Request'},
                    path: "/seller/verify/request",
                    name: "seller-verify-request",
                    component: () => import("@/components/seller/SellerVerifyRequest")
                },
                {
                    meta: {title: 'Seller Commission'},
                    path: "/seller/seller-commission",
                    name: "seller-commission",
                    component: () => import("@/components/seller/Commission")
                },
                {
                    meta: {title: 'Seller Packages'},
                    path: "/seller/seller-packages",
                    name: "seller-packages",
                    component: () => import("@/components/seller/Packages")
                },
                {
                    meta: {title: 'Membership Plan'},
                    path: "/seller/membership-plan",
                    name: "membership-plan",
                    component: () => import("@/components/seller/MembershipPlan")
                },
                {
                    meta: {title: 'Guest User Settings'},
                    path: "/seller/guest-user-settings",
                    name: "guest-user-settings",
                    component: () => import("@/components/seller/GuestUserSettings")
                },
            ]
        },
        {
            path: "/package-payment",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Package Payment'},
                    path: "/",
                    name: "package-payment",
                    component: () => import("@/components/PackagePayment")
                },
            ]
        },
        {
            path: "/advertisement",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Advertisement Manage'},
                    path: "/advertisement",
                    name: "advertisement",
                    component: () => import("@/components/Advertisement")
                },
            ]
        },
        {
            path: "/marketing",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Flash Deals'},
                    path: "/marketing/flash-deals",
                    name: "Flash Deals",
                    component: () => import("@/components/marketing/Flash")
                },
                {
                    meta: {title: 'Orders'},
                    path: "/marketing/order",
                    name: "order",
                    component: () => import("@/components/marketing/Order")
                },
                {
                    meta: {title: 'Newsletters'},
                    path: "/marketing/newsletter",
                    name: "Newsletters",
                    component: () => import("@/components/marketing/Newsletter")
                },
                {
                    meta: {title: 'Create New Flash Deals'},
                    path: "/marketing/flash-deals-create",
                    name: "Flash Deals Create",
                    component: () => import("@/components/marketing/NewFlash")
                },
                {
                    meta: {title: 'Supplier Flash Deals'},
                    path: "/marketing/flash-deals-supplier",
                    name: "Supplier Flash Deals",
                    component: () => import("@/components/marketing/FlashRequest")
                },
                {
                    meta: {title: 'Quotation List'},
                    path: "/marketing/quotation",
                    name: "Quotation List",
                    component: () => import("@/components/marketing/Quotation")
                },
                {
                    meta: {title: 'Subscriber List'},
                    path: "/marketing/subscribers",
                    name: "subscriber",
                    component: () => import("@/components/marketing/Subscribers")
                }
            ]
        },
        {
            path: "/frontend",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Home Settings '},
                    path: "/frontend/home",
                    name: "Home Settings",
                    component: () => import("@/components/frontend/Home")
                },
                {
                    meta: {title: 'General Settings '},
                    path: "/frontend/general-settings",
                    name: "General Settings",
                    component: () => import("@/components/frontend/General")
                },
                {
                    meta: {title: 'Testimonial'},
                    path: "/frontend/testimonial",
                    name: "Testimonial",
                    component: () => import("@/components/frontend/Testimonial")
                },
                {
                    meta: {title: 'Logo Settings '},
                    path: "/frontend/logo",
                    name: "Logo Settings",
                    component: () => import("@/components/frontend/Logo")
                }
            ]
        },
        {
            path: "/region",
            component: () => import("@/components/layout/Layout"),
            children: [
                {
                    meta: {title: 'Country '},
                    path: "/region/country",
                    name: "Country",
                    component: () => import("@/components/region/Country")
                },
                {
                    meta: {title: 'Division '},
                    path: "/region/division",
                    name: "Division",
                    component: () => import("@/components/region/Division")
                },
                {
                    meta: {title: 'City '},
                    path: "/region/city",
                    name: "City",
                    component: () => import("@/components/region/City")
                },
                {
                    meta: {title: 'Area '},
                    path: "/region/area",
                    name: "Area",
                    component: () => import("@/components/region/Area")
                }
            ]
        },
        {
            path: "/",
            component: () => import("@/components/auth/Auth"),
            children: [
                {
                    meta: {title: 'Login'},
                    name: "login",
                    path: "/login",
                    component: () => import("@/components/auth/Login")
                }
            ]
        },
    ]
})


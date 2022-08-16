export default [
    {
        _name: 'CSidebarNav',
        _children: [
            {
                _name: 'CSidebarNavItem',
                name: 'Dashboard',
                to: '/dashboard',
                icon: 'cil-home',
            },
            {
                _name: 'CSidebarNavDropdown',
                name: 'Product / Service',
                route: '/product',
                icon: 'cil-cart',
                items: [
                    {
                        name: 'Brand',
                        to: '/product/brand'
                    },
                    {
                        name: 'Category',
                        to: '/product/category'
                    },
                    {
                        name: 'Subcategory',
                        to: '/product/sub-category'
                    },
                    {
                        name: 'Sub Subcategory',
                        to: '/product/sub-subcategory'
                    },
                    {
                        name: 'Properties',
                        to: '/product/properties'
                    },
                    {
                        name: 'In House Products',
                        to: '/product/product-admin'
                    },
                    {
                        name: 'Seller Products',
                        to: '/product/seller-product'
                    },
                    {
                        name: 'Products Group',
                        to: '/product/product-group'
                    }

                ]
            },
            {
                _name: 'CSidebarNavDropdown',
                name: 'Customers',
                route: '/customer',
                icon: 'cil-user-plus',
                items: [
                    {
                        name: 'Customer List',
                        to: '/user/customer-list'
                    },
                ]
            },
            {
                _name: 'CSidebarNavDropdown',
                name: 'Sellers',
                route: '/seller',
                icon: 'cil-user',
                items: [
                    {
                        name: 'All Seller',
                        to: '/seller/all-seller'
                    },
                    {
                        name: 'Verify Request',
                        to: '/seller/verify/request'
                    },
                    {
                        name: 'Seller Commission',
                        to: '/seller/seller-commission'
                    },
                    {
                        name: 'Membership Plan',
                        to: '/seller/membership-plan'
                    },
                    {
                        name: 'Guest User Settings',
                        to: '/seller/guest-user-settings'
                    },
                ]
            },
            {
                _name: 'CSidebarNavItem',
                name: 'Package Payment',
                route: 'package-payment',
                to: '/package-payment',
                icon: 'cil-map'
            },
            {
                _name: 'CSidebarNavItem',
                name: 'Advertisement',
                route: 'package-payment',
                to: '/advertisement',
                icon: 'cil-map'
            },
            {
                _name: 'CSidebarNavDropdown',
                name: 'Marketing',
                route: '/marketing',
                icon: 'cil-bullhorn',
                items: [
                    {
                        name: 'Flash Deals',
                        to: '/marketing/flash-deals'
                    },
                    {
                        name: 'Orders',
                        to: '/marketing/order'
                    },
                    {
                        name: 'Newsletters',
                        to: '/marketing/newsletter'
                    },
                    {
                        name: 'Quotation',
                        to: '/marketing/quotation'
                    },
                    {
                        name: 'Subscribers',
                        to: '/marketing/subscribers'
                    },
                    {
                        name: 'Coupon',
                        to: '/marketing/coupon'
                    }
                ]
            },
            {
                _name: 'CSidebarNavDropdown',
                name: 'Frontend Settings',
                route: '/frontend',
                icon: 'cil-screen-desktop',
                items: [
                    {
                        name: 'Home',
                        to: '/frontend/home'
                    },
                    {
                        name: 'General Settings',
                        to: '/frontend/general-settings'
                    },
                    {
                        name: 'Testimonial',
                        to: '/frontend/testimonial'
                    },
                    {
                        name: 'Logo Settings',
                        to: '/frontend/logo'
                    }
                ]
            },
            {
                _name: 'CSidebarNavDropdown',
                name: 'E-commerce Setup',
                route: '/upload-manage',
                icon: 'cil-settings',
                items: [
                    {
                        name: 'Color',
                        to: '/upload-manage/color'
                    },
                    {
                        name: 'Attribute',
                        to: '/upload-manage/attribute'
                    },
                    {
                        name: 'Unit',
                        to: '/upload-manage/unit'
                    },
                    {
                        name: 'Business Type',
                        to: '/upload-manage/business-type'
                    },
                    {
                        name: 'Currency',
                        to: '/upload-manage/currency'
                    },
                ]
            },
            {
                _name: 'CSidebarNavDropdown',
                name: 'Region',
                route: '/region',
                icon: 'cil-map',
                items: [
                    {
                        name: 'Country',
                        to: '/region/country'
                    },
                    {
                        name: 'Division',
                        to: '/region/division'
                    },
                    {
                        name: 'City',
                        to: '/region/city'
                    },
                    {
                        name: 'Area',
                        to: '/region/area'
                    }
                ]
            }
        ]
    }
]

import Vue from 'vue'
import Vuex from 'vuex'
import auth from './auth.module';
import general from './general.module';
import category from './module/category';
import subcategory from './module/subcategory';
import subsubcategory from './module/subsubcategory';
import brand from './module/brand';
import unit from './module/unit';
import property from './module/property';
import color from './module/color';
import attribute from './module/attribute';
import currency from './module/currency';
import country from './module/country';
import division from './module/division';
import city from './module/city';
import area from './module/area';
import personal from './module/personal';
import companybasic from './module/companybasic';
import homeslider from "@/core/services/store/module/homeslider";
import businesstype from "@/core/services/store/module/businesstype";
import quotation from "@/core/services/store/module/quotation";
import search from "@/core/services/store/module/search";
import page_manage from "@/core/services/store/module/page_manage";
import help from "@/core/services/store/module/help";
import flash_deals from "@/core/services/store/module/flash_deals";
import testimonial from "@/core/services/store/module/testimonial";
import product_request from "@/core/services/store/module/product_request";
import certificate from "./module/certificate";
import rndQcPO from "./module/rndQcPO";
import factory from "./module/factory";
import nearestPort from "./module/nearestPort";
import companyTrade from "./module/companyTrade";
import singleProduct from "./module/singleProduct";
import product from "./module/product";
import reviews from "./module/reviews";
import productFavorites from "./module/productFavorites";
import message from "./module/message";
import dashboard from "./module/dashboard";
import cart from "./module/cart";
import ecom_zone_products from "./module/ecom_zone_products";
import advertisement from "@/core/services/store/module/advertisement";

Vue.use(Vuex)


export default new Vuex.Store({
    modules: {
        auth,
        general,
        category,
        subcategory,
        subsubcategory,
        brand,
        unit,
        property,
        color,
        attribute,
        currency,
        country,
        division,
        city,
        area,cart,
        personal,
        companybasic,
        homeslider,
        businesstype,
        quotation,
        search,
        page_manage,
        help,
        ecom_zone_products,
        flash_deals,
        testimonial,
        product_request,
        certificate,
        rndQcPO,
        factory,
        nearestPort,
        companyTrade,
        singleProduct,
        product,
        reviews,
        productFavorites,
        message,
        dashboard,
        advertisement,
    }
})

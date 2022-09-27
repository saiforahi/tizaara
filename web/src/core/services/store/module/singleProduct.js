import ApiService from "@/core/services/api.service";

// action types
export const SINGLE_PRODUCT_DETAILS = "singleProduct_details";
export const SINGLE_PRODUCT_PRICE = "singleProduct_price";
export const AVAILABLE_PRODUCT_QTY = "singleProduct_price";
export const PRODUCT_VARIANT = "singleProduct_price";
export const PRODUCT_QUANTITY_CHANGE = "product/quantity/change";

// mutation types
export const SET_SINGLE_PRODUCT = "setSingle/product";
export const SET_RELATED_PRODUCT = "related/products";
export const SET_PRODUCT_FLASH_DEAL = "flashDeal/products";
export const SET_PRODUCT_ECOM_ZONE = "ecomZone/products";
export const SET_PRODUCT_PRICE = "product/price";
export const SET_AVAILABLE_PRODUCT_QTY = "product/qty/set";
export const SET_PRODUCT_VARIANT = "product/variant/change";
export const SET_PRODUCT_QUANTITY_CHANGE = "set/product/quantity/change";


const state = {
    product: {},
    variant:[],
    related_products:[],
    flash_deal:null,
    ecom_zone:null,
    price:0,
    quantity:1,
    discount_price:0,
    available_product_qty:0,
};

const getters = {
    product(state) {
        return state.product;
    },
    variant(state) {
        return state.variant;
    },
    related_products(state) {
        return state.related_products;
    },
    flash_deal(state) {
        return state.flash_deal;
    },
    ecom_zone(state){
        return state.ecom_zone
    },
    price(state){
      return state.price;
    },
    quantity(state){
        return state.quantity;
    },
    discount_price(state) {
        if (state.flash_deal){
            if (state.flash_deal.discount_type ===  'Flat'){
                state.discount_price = (state.price-state.flash_deal.discount);
            }else{
                state.discount_price = (state.price-(state.price*(state.flash_deal.discount/100)));
            }
        } else if(state.product.discount_variation ==0 && state.product.discount){
            if (state.product.discount_type ===  'Flat'){
                state.discount_price = (state.price-state.product.discount);
            }else{
                state.discount_price = (state.price-(state.price*(state.product.discount/100)));
            }
        }else if(state.product.discount_variation ==1 && state.product.discount_variation_data.length>0){
            let data = state.product.discount_variation_data.filter((item,key)=>{
                if(item.min_qty<= state.quantity && state.product.discount_variation_data.length==(key+1)){
                    return item;
                }else if(item.min_qty<= state.quantity && state.quantity<state.product.discount_variation_data[(key+1)].min_qty){
                    return item;
                }
            });
            if (data.length>0){
                state.discount_price = (state.price-(state.price*(data[0].percent_off/100)));
            }else state.discount_price =0;
        }
        return state.discount_price;
    },
    available_product_qty(state){
        return state.available_product_qty;
    },
    company_basic(state){
        if (state.product.user && state.product.user.company_basic_info){
            return state.product.user.company_basic_info;
        }
        return {};
    },
    company_details(state){
        if (state.product.user && state.product.user.company_details){
            return state.product.user.company_details;
        }
        return {};
    },
    property_options(state){
        if (state.product.property_options) return JSON.parse(state.product.property_options);
        else return [];
    },
    colors(state){
        if (state.product.colors) return JSON.parse(state.product.colors);
        else return [];
    },
    color_images(state){
        if (state.product.color_image) return JSON.parse(state.product.color_image);
        else return [];
    },
    attribute_options(state){
        if (state.product.attribute_options) return JSON.parse(state.product.attribute_options);
        else return [];
    }
};

const mutations = {
    [SET_SINGLE_PRODUCT](state, product) {
        state.product = product;
        if(product.priceType==0){
            state.price=product.unit_price;
        }
        else if (product.priceType==1){
            state.price=product.product_stock?product.product_stock[0].price:0;
            state.variant=product.product_stock?JSON.parse(product.product_stock[0].variant):[];
            state.available_product_qty=product.product_stock?JSON.parse(product.product_stock[0].qty):0;
        }
    },
    [SET_RELATED_PRODUCT](state, related_products) {
        state.related_products = related_products;
    },
    [SET_PRODUCT_FLASH_DEAL](state, flash_deal_products) {
        if (flash_deal_products.length>0){
            let flash = flash_deal_products.filter(item=>{
                if (item.flash_deal) return item;
            });
            if (flash.length>0) {
                state.flash_deal = flash[flash.length - 1];
            }else state.flash_deal=null;
        }else state.flash_deal=null;
    },
    [SET_PRODUCT_ECOM_ZONE](state, ecom_zone_product_list) {
        if (ecom_zone_product_list.length>0){
            let flash = ecom_zone_product_list.filter(item=>{
                if (item.product_id == state.product.id) return item;
            });
            if (flash.length>0) {
                state.ecom_zone = flash[flash.length - 1];
            }else state.ecom_zone=null;
        }else state.ecom_zone=null;
    },
    [SET_PRODUCT_PRICE](state, price) {
        state.price=price;
    },
    [SET_AVAILABLE_PRODUCT_QTY](state, qty) {
        state.available_product_qty=qty;
    },
    [SET_PRODUCT_VARIANT](state, data) {
        state.variant[data.index]=data.variant;
        if (state.product.priceType ==1 && state.product.product_stock) {
            let product_stock = state.product.product_stock.find(value => value.variant === JSON.stringify(state.variant));
            if (product_stock) {
                state.price=product_stock.price;
                state.available_product_qty=product_stock.qty;
            }
        }
        state.quantity=1;//if variation change discount price reset
    },
    [SET_PRODUCT_QUANTITY_CHANGE](state, qty) {
        state.quantity=qty;
    },
};

const actions = {
    [SINGLE_PRODUCT_DETAILS]({commit},slug) {
        ApiService.get(`single/product/information/${slug}`).then((response)=>{
            console.log('sp',response.data)
            let data =response.data;
            commit(SET_SINGLE_PRODUCT,data.product)
            commit(SET_RELATED_PRODUCT,data.related_products)
            commit(SET_PRODUCT_FLASH_DEAL,data.product.flash_deal_products)
            commit(SET_PRODUCT_ECOM_ZONE,data.product.ecom_zone_products)
        }).catch((error)=>{
            //
        });
    },
    [SINGLE_PRODUCT_PRICE]({commit},price) {
        commit(SET_SINGLE_PRODUCT,price)
    },
    [AVAILABLE_PRODUCT_QTY]({commit},qty) {
        commit(SET_SINGLE_PRODUCT,qty)
    },
    [PRODUCT_VARIANT]({commit},data) {
        commit(SET_PRODUCT_VARIANT,data);
    },
    [PRODUCT_QUANTITY_CHANGE]({commit},qty) {
        commit(SET_PRODUCT_QUANTITY_CHANGE,qty);
    },
};

export default {
    state,
    actions,
    mutations,
    getters
};
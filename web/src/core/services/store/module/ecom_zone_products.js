import ApiService from "@/core/services/api.service";

// action types
export const ECOM_ZONE_PRODUCT_LIST = "ecom_zone/list";

// mutation types
export const SET_ECOM_ZONE_PRODUCT_LIST = "setEcomZoneProducts";
export const ECOM_ZONE_REMOVE = "deleteEcomZoneProduct";
export const ECOM_ZONE_ADD = "addNewEcomZoneProduct";

const state = {
    ecom_zone_products: [],
};


const getters = {
    ecom_zone_products: state => state.ecom_zone_products,

    // getFlashDealBySlug: state => slug => state.ecom_zone_products.find(value => value.slug === slug),
};

const mutations = {
    [SET_ECOM_ZONE_PRODUCT_LIST](state, data) {
        data.length > 0 ? state.ecom_zone_products = data : '';
    },
    [ECOM_ZONE_REMOVE](state, dataId) {
        let index = state.ecom_zone_products.findIndex(value => value.id === dataId);
        state.ecom_zone_products.splice(index, 1);
    },
    [ECOM_ZONE_ADD](state, data) {
        state.ecom_zone_products.unshift(data);
    },
};

const actions = {
    [ECOM_ZONE_PRODUCT_LIST]({commit}) {
        ApiService.get("ecom-zone-products")
            .then(({data}) => {
                commit(SET_ECOM_ZONE_PRODUCT_LIST, data);
            })
            .catch(({response}) => {
                console.log(response);
            });
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};
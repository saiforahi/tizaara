import ApiService from "@/core/services/api.service";
// action types
export const SEARCH_PRODUCT = "product/search";

// mutation types
export const SET_PRODUCT_SEARCH = "pushProductSearch";
export const ERROR_SET_LOADING = "loadingProductSearch";

const state = {
    product: [],
    loading: false,
};

const getters = {
    getProductName: state => text => state.product.filter(value => value.name.toLowerCase().indexOf(text) > -1),

    searchProductLoad: state => !state.loading,
};

const mutations = {
    [SET_PRODUCT_SEARCH](state, data) {
        data.forEach(function (item, index) {
            state.product.find(value => value.id === item.id) ? '' : state.product.push(item);
        })
        state.loading = false;
    },

    [ERROR_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }

};

const actions = {
    [SEARCH_PRODUCT]({commit}, data) {
        commit(ERROR_SET_LOADING)
        ApiService.get("product-name", false, {
            params: {
                n: data
            }
        })
            .then(res => {
                commit(SET_PRODUCT_SEARCH, res.data);
            })
            .catch(err => {
                console.log(err);
            })
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};
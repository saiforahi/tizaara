import ApiService from "@/core/services/api.service";

// action types
export const CATEGORY_LIST = "category/list";
export const HOME_CATEGORY_PRODUCT = "home/category/product";

// mutation types
export const SET_CATEGORY_LIST = "setCategoryList";
export const SET_CATEGORY_PRODUCT = "setCategoryProduct";
export const SET_ERROR = "setError";
export const CATEGORY_SET_LOADING = "categorySetLoading";

const state = {
    errors: null,
    loading: false,
    category: [],
    category_product: [],
};

const getters = {
    categoryList: state => state.category,

    homeCategoryProduct: state => state.category_product,

    categoryErrors(state) {
        return state.errors;
    },
    categoryIsLoaded(state) {
        return !state.loading;
    },
    getCategoryById: state => id => state.category.find(value => value.id === id),

    getCategoryBySlug: state => slug => state.category.find(value => value.slug === slug),
};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_CATEGORY_LIST](state, category) {
        state.category = category;
    },
    [SET_CATEGORY_PRODUCT](state, category) {
        state.category_product = category;
    },
    [CATEGORY_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }
};

const actions = {
    [CATEGORY_LIST]({commit}) {
        return new Promise((resolve, reject) => {
            commit(CATEGORY_SET_LOADING)
            ApiService.get("category")
                .then(({data}) => {
                    commit(SET_CATEGORY_LIST, data);
                    resolve();
                })
                .catch(({response}) => {
                    commit(SET_ERROR, response.data.errors);
                    reject(response)
                });
        });
    },

    [HOME_CATEGORY_PRODUCT]({commit}) {
        ApiService.get("home-category-listing")
            .then(({data}) => {
                commit(SET_CATEGORY_PRODUCT, data);
            })
            .catch(({response}) => {
                commit(SET_ERROR, response.data.errors);
            });
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};

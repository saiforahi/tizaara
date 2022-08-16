import ApiService from "@/core/services/api.service";

// action types
export const CATEGORY_LIST = "category/list";
export const HOME_CATEGORY_PRODUCT = "home/category/product";

// mutation types
export const SET_CATEGORY_LIST = "setCategoryList";
export const SET_CATEGORY_PRODUCT = "setCategoryProduct";
export const CATEGORY_ADD = "addNewCategory";
export const CATEGORY_MODIFY = "modifyCategory";
export const CATEGORY_REMOVE = "deleteCategory";
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

    categoryErrors(state) {
        return state.errors;
    },
    categoryIsLoaded(state) {
        return !state.loading;
    },
    getCategoryById: state => id => state.category.find(value => value.id === id),

    getCatListingId(state) {
        let data = [];
        for (let i = 0; i < state.category.length; i++) {
            if (state.category[i].serial != null) {
                data.push(state.category[i].id);
            }
        }
        return data;
    },

    getCatHomeListingId(state) {
        let data = [];
        for (let i = 0; i < state.category_product.length; i++) {
            data.push(state.category_product[i].id);
        }
        return data;
    }
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
    [CATEGORY_REMOVE](state, categoryId) {
        let index = state.category.findIndex(value => value.id === categoryId);
        state.category.splice(index, 1);
        state.loading = false;
    },
    [CATEGORY_MODIFY](state, categories) {
        let index = state.category.findIndex(value => value.id === categories.id)
        state.category[index] = categories;
        state.loading = false;
    },
    [CATEGORY_ADD](state, category) {
        state.category.unshift(category);
    },
    [CATEGORY_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }
};

const actions = {
    [CATEGORY_LIST]({commit}) {
        commit(CATEGORY_SET_LOADING)
        ApiService.get("category")
            .then(({data}) => {
                commit(SET_CATEGORY_LIST, data);
            })
            .catch(({response}) => {
                commit(SET_ERROR, response.data.errors);
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

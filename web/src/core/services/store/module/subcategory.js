import ApiService from "@/core/services/api.service";

// action types
export const SUBCATEGORY_LIST = "subcategory/list";

// mutation types
export const SET_SUBCATEGORY_LIST = "setSubcategoryList";
export const SET_ERROR = "setError";
export const SUBCATEGORY_SET_LOADING = "subcategorySetLoading";

const state = {
    errors: null,
    loading: false,
    subcategory: [],
};

const getters = {
    subcategoryList(state) {
        return state.subcategory;
    },

    subcategoryIsLoaded(state) {
        return !state.loading;
    },

    getSubcategoryById: state => id => state.subcategory.filter(value => value.category_id === id),

    getSubcategoryNameById: state => id => state.subcategory.find(value => value.id === id),

    getSubcategoryNameBySlug: state => slug => state.subcategory.find(value => value.slug === slug),

};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_SUBCATEGORY_LIST](state, category) {
        state.subcategory = category;
    },
    [SUBCATEGORY_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }
};

const actions = {
    [SUBCATEGORY_LIST]({commit}) {
        commit(SUBCATEGORY_SET_LOADING)
        ApiService.get("subcategory")
            .then(({data}) => {
                commit(SET_SUBCATEGORY_LIST, data);
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

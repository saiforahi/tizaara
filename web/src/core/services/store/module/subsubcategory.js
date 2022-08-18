import ApiService from "@/core/services/api.service";

// action types
export const SUBSUBCATEGORY_LIST = "subsubcategory/list";

// mutation types
export const SET_SUBSUBCATEGORY_LIST = "setSubsubcategoryList";
export const SET_ERROR = "setError";
export const SUBSUBCATEGORY_SET_LOADING = "subsubcategorySetLoading";

const state = {
    errors: null,
    loading: false,
    subsubcategory: [],
};

const getters = {
    subsubcategoryList(state) {
        return state.subsubcategory;
    },
    subsubcategoryErrors(state) {
        return state.errors;
    },
    subsubcategoryIsLoaded(state) {
        return !state.loading;
    },
    getSubsubcategoryById: state => id => state.subsubcategory.filter(value => value.sub_category_id === id),

    getSubsubcategoryNameById: state => id => state.subsubcategory.find(value => value.id === id),

    getSubsubcategoryNameBySlug: state => slug => state.subsubcategory.find(value => value.slug === slug),
};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_SUBSUBCATEGORY_LIST](state, subsubcategory) {
        state.subsubcategory = subsubcategory;
    },
    [SUBSUBCATEGORY_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }
};

const actions = {
    [SUBSUBCATEGORY_LIST]({commit}) {
        commit(SUBSUBCATEGORY_SET_LOADING)
        ApiService.get("subsubcategory")
            .then(({data}) => {
                commit(SET_SUBSUBCATEGORY_LIST, data);
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

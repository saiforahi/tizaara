import ApiService from "@/core/services/api.service";

// action types
export const SUBCATEGORY_LIST = "subcategory/list";

// mutation types
export const SET_SUBCATEGORY_LIST = "setSubcategoryList";
export const SUBCATEGORY_ADD = "addNewSubcategory";
export const SUBCATEGORY_MODIFY = "modifySubcategory";
export const SUBCATEGORY_REMOVE = "deleteSubcategory";
export const SET_ERROR = "setError";
export const SUBCATEGORY_SET_LOADING = "subcategorySetLoading";

const state = {
    errors: null,
    loading: false,
    subcategory: {},
};

const getters = {
    subcategoryList(state) {
        return state.subcategory;
    },
    subcategoryErrors(state) {
        return state.errors;
    },
    subcategoryIsLoaded(state) {
        return !state.loading;
    },

    getSubcategoryById: state => id => state.subcategory.filter(value => value.category_id === id),

    getSubcategoryNameById: state => id => state.subcategory.find(value => value.id === id),

    getSubListingId: state => id => {
        let alldata = state.subcategory.filter(value => value.category_id === id && value.serial !== null);

        let data = [];
        for (let i = 0; i < alldata.length; i++) {
            data.push(alldata[i].id);
        }
        return data;
    }

};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_SUBCATEGORY_LIST](state, category) {
        state.subcategory = category;
    },
    [SUBCATEGORY_REMOVE](state, categoryId) {
        let index = state.subcategory.findIndex(value => value.id === categoryId);
        state.subcategory.splice(index, 1);
        state.loading = false;
    },
    [SUBCATEGORY_MODIFY](state, categories) {
        let index = state.subcategory.findIndex(value => value.id === categories.id)
        state.subcategory[index] = categories;
        state.loading = false;
    },
    [SUBCATEGORY_ADD](state, category) {
        state.subcategory.unshift(category);
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

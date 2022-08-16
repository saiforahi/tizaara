import ApiService from "@/core/services/api.service";

// action types
export const SUBSUBCATEGORY_LIST = "subsubcategory/list";

// mutation types
export const SET_SUBSUBCATEGORY_LIST = "setSubsubcategoryList";
export const SUBSUBCATEGORY_ADD = "addNewSubsubcategory";
export const SUBSUBCATEGORY_MODIFY = "modifySubsubcategory";
export const SUBSUBCATEGORY_REMOVE = "deleteSubsubcategory";
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

    getSubSubListingId: state => id => {
        let alldata = state.subsubcategory.filter(value => value.sub_category_id === id && value.serial !== null);

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
    [SET_SUBSUBCATEGORY_LIST](state, subsubcategory) {
        state.subsubcategory = subsubcategory;
    },
    [SUBSUBCATEGORY_REMOVE](state, subsubcategoryId) {
        let index = state.subsubcategory.findIndex(value => value.id === subsubcategoryId);
        state.subsubcategory.splice(index, 1);
        state.loading = false;
    },
    [SUBSUBCATEGORY_MODIFY](state, subsubcategory) {
        let index = state.subsubcategory.findIndex(value => value.id === subsubcategory.id)
        state.subsubcategory[index] = subsubcategory;
        state.loading = false;
    },
    [SUBSUBCATEGORY_ADD](state, subsubcategory) {
        state.subsubcategory.unshift(subsubcategory);
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

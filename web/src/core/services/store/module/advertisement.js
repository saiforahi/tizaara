import ApiService from "@/core/services/api.service";

// action types
export const ADVERTISEMENT_LIST = "advertisement/list";

// mutation types
export const SET_ADVERTISEMENT_LIST = "setAdvertisementList";
export const SET_ERROR = "setError";
export const ADVERTISEMENT_SET_LOADING = "advertisementSetLoading";

const state = {
    errors: null,
    loading: false,
    advertisement: [],
};

const getters = {
    advertisementList(state) {
        return state.advertisement;
    },
    advertisementErrors(state) {
        return state.errors;
    },
    advertisementIsLoaded(state) {
        return !state.loading;
    },
    getAdvertisementById: state => id => state.advertisement.find(value => value.add_no === id),
};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_ADVERTISEMENT_LIST](state, advertisement) {
        advertisement.length > 0 ? state.advertisement = advertisement : '';
    },
    [ADVERTISEMENT_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }
};

const actions = {
    [ADVERTISEMENT_LIST]({commit}) {
        commit(ADVERTISEMENT_SET_LOADING)
        ApiService.get("advertisement")
            .then(({data}) => {
                commit(SET_ADVERTISEMENT_LIST, data);
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

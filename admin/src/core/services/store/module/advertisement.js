import ApiService from "@/core/services/api.service";

// action types
export const ADVERTISEMENT_LIST = "advertisement/list";

// mutation types
export const SET_ADVERTISEMENT_LIST = "setAdvertisementList";
export const ADVERTISEMENT_ADD = "addNewAdvertisement";
export const ADVERTISEMENT_MODIFY = "modifyAdvertisement";
export const ADVERTISEMENT_REMOVE = "deleteAdvertisement";
export const SET_ERROR = "setError";
export const ADVERTISEMENT_SET_LOADING = "advertisementSetLoading";

const state = {
    errors: null,
    loading: false,
    advertisements: [],
};

const getters = {
    advertisementList(state) {
        return state.advertisements;
    },
    advertisementErrors(state) {
        return state.errors;
    },
    advertisementIsLoaded(state) {
        return !state.loading;
    },

    getAdvertisementById: state => id => state.advertisements.find(value => value.id === id),
    getAdvertisementByNo: state => no => state.advertisements.find(value => value.add_no === no),
    getAdvertisementListingId(state) {
        let data = [];
        for (let i = 0; i < state.advertisements.length; i++) {
            if (state.advertisements[i].banner != null) {
                data.push(state.advertisements[i].id);
            }
        }
        return data;
    }
    
};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_ADVERTISEMENT_LIST](state, advertisements) {
        advertisements.length > 0 ? state.advertisements = advertisements : '';
    },
    [ADVERTISEMENT_REMOVE](state, advertisementId) {
        let index = state.advertisements.findIndex(value => value.id === advertisementId);
        state.advertisements.splice(index, 1);
        state.loading = false;
    },
    [ADVERTISEMENT_MODIFY](state, advertisement) {
        let index = state.advertisements.findIndex(value => value.id === advertisement.id)
        state.advertisements[index] = advertisement;
        state.loading = false;
    },
    [ADVERTISEMENT_ADD](state, advertisement) {
        state.advertisements.unshift(advertisement);
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
                console.log('adds',data)
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

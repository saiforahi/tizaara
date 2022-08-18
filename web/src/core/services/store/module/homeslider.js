import ApiService from "@/core/services/api.service";

// action types
export const HOMEBANNER_LIST = "brand/homeBanner";

// mutation types
export const SET_HOMEBANNER_LIST = "setHomeBannerList";


const state = {
    homeBanner: [],
};

const getters = {
    getHomeBanner(state) {
        return state.homeBanner;
    },
}

const mutations = {
    [SET_HOMEBANNER_LIST](state, homeBanner) {
        state.homeBanner = homeBanner;
    },
}

const actions = {
    [HOMEBANNER_LIST]({commit}) {
        ApiService.get("home-banner")
            .then(({data}) => {
                commit(SET_HOMEBANNER_LIST, data);
            })
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};
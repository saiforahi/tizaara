import ApiService from "@/core/services/api.service";

// action types
export const USER_FAVORITE_LIST = "user/favorite/list";

// mutation types
export const SET_USER_FAVORITE_LIST = "set/user/favorite/list";

const state = {
    user_favorites:[],//login user all favorites info are store here
};

const getters = {
    user_favorites(state) {
        return state.user_favorites;
    },
};

const mutations = {
    [SET_USER_FAVORITE_LIST](state, data) {
        state.user_favorites = data;
    }
};

const actions = {
    [USER_FAVORITE_LIST]({commit}) {
        ApiService.post('user/product/favorite/list')
            .then(({data}) => {
                commit(SET_USER_FAVORITE_LIST, data);
            })
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};
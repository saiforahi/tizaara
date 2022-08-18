import ApiService from "@/core/services/api.service";

// action types
export const COLOR_LIST = "color/list";

// mutation types
export const SET_COLOR_LIST = "setColorList";

const state = {
    color: []
};

const getters = {
    colorList(state) {
        return state.color;
    }
};

const mutations = {
    [SET_COLOR_LIST](state, colors) {
        colors.length > 0 ? state.color = colors : [];
    }
};

const actions = {
    [COLOR_LIST]({commit}) {
        ApiService.get("color")
            .then(({data}) => {
                commit(SET_COLOR_LIST, data);
            })
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};

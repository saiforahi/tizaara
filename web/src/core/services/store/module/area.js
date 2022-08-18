import ApiService from "@/core/services/api.service";

// action types
export const AREA_LIST = "area/list";

// mutation types
export const SET_AREA_LIST = "setAreaList";

const state = {
    area: [],
};

const getters = {
    areaList(state) {
        return state.area;
    },

    getAreaById: state => id => state.area.filter(value => value.city_id === id),

    getAreaNameById: state => id => state.area.find(value => value.id === id),
};

const mutations = {
    [SET_AREA_LIST](state, area) {
        state.area = area;
    }
};

const actions = {
    [AREA_LIST]({commit}) {
        ApiService.get("area")
            .then(({data}) => {
                commit(SET_AREA_LIST, data);
            })
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};
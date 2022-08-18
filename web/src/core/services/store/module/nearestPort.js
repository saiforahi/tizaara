import ApiService from "@/core/services/api.service";

// action types
export const NEAREST_PORT_LIST = "nearestPort";

// mutation types
export const SET_NEAREST_PORT_LIST = "setNearestPort";


const state = {
    nearest_ports: [],

};

const getters = {
    nearest_ports(state) {
        return state.nearest_ports;
    },
};

const mutations = {
    [SET_NEAREST_PORT_LIST](state, nearest_ports) {
        state.nearest_ports = nearest_ports;
    }
};

const actions = {
    [NEAREST_PORT_LIST]({commit}) {
        ApiService.get("company/nearest/ports")
            .then(({data}) => {
                commit(SET_NEAREST_PORT_LIST, data);
            })
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};
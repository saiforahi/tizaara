import ApiService from "@/core/services/api.service";

// action types
export const DASHBOARD_INFORMATION = "dashboardInformation";

// mutation types
export const SET_DASHBOARD_INFORMATION = "setdashboardInformation";

const state = {
    dashboard: {},
};

const getters = {
    dashboard(state) {
        return state.dashboard;
    },
};

const mutations = {
    [SET_DASHBOARD_INFORMATION](state, dashboard) {
        state.dashboard = dashboard;
    },
};

const actions = {
    [DASHBOARD_INFORMATION]({commit}) {
        ApiService.get("admin/get/dashboard/information")
            .then((response) => {
                commit(SET_DASHBOARD_INFORMATION, response.data);
            })
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};

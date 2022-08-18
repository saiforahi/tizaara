import ApiService from "@/core/services/api.service";

// action types
export const GET_SUPPLIER_DASHBOARD_INFORMATION = "getSupplierDashboardInformation";
export const GET_BUYER_DASHBOARD_INFORMATION = "getBuyerDashboardInformation";

// mutation types
export const SET_SUPPLIER_DASHBOARD_INFORMATION = "setSupplierDashboardInformation";
export const SET_BUYER_DASHBOARD_INFORMATION = "setBuyerDashboardInformation";

const state = {
    dashboard_supplier: {},
    dashboard_buyer: {},
};

const getters = {
    dashboard_supplier(state) {
        return state.dashboard_supplier;
    },
    dashboard_buyer(state) {
        return state.dashboard_buyer;
    },
};

const mutations = {
    [SET_SUPPLIER_DASHBOARD_INFORMATION](state, dashboard_supplier) {
        state.dashboard_supplier = dashboard_supplier;
    },
    [SET_BUYER_DASHBOARD_INFORMATION](state, dashboard_buyer) {
        state.dashboard_buyer = dashboard_buyer;
    },
};

const actions = {
    [GET_SUPPLIER_DASHBOARD_INFORMATION]({commit}) {
        ApiService.get("get/dashboard/supplier/related/information")
            .then((response) => {
                commit(SET_SUPPLIER_DASHBOARD_INFORMATION, response.data);
            });
    },
    [GET_BUYER_DASHBOARD_INFORMATION]({commit}) {
        ApiService.get("get/dashboard/buyer/related/information")
            .then((response) => {
                commit(SET_BUYER_DASHBOARD_INFORMATION, response.data);
            });
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};

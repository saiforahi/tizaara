import ApiService from "@/core/services/api.service";

// action types
export const ORDER_LIST = "orderList";
export const ORDER_STATUS_CHANGE = "approvalStatusChange";

// mutation types
export const SET_ORDER_LIST = "setOrderList";

const state = {
    orders: [],
};

const getters = {
    orderList(state) {
        return state.orders;
    },
};

const mutations = {
    [SET_ORDER_LIST](state, data) {
        state.orders = data;
    },
    [ORDER_STATUS_CHANGE](state, [status,index]) {
        state.orders[index].approval_status = status;
    },
};

const actions = {
    [ORDER_LIST]({commit}) {
        ApiService.get("admin/get/all/order")
            .then((response) => {
                commit(SET_ORDER_LIST, response.data);
            })
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};

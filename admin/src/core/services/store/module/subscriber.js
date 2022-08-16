import ApiService from "@/core/services/api.service";
// action types
export const SUBSCRIBER_LIST = "subscribers/list";

// mutation types
export const SET_SUBSCRIBER_LIST = "setQuotationList";
export const SUBSCRIBER_REMOVE = "deleteQuotation";

const state = {
    subscribers: [],
};

const getters = {
    subscribers(state) {
        return state.subscribers;
    },
};

const mutations = {
    [SET_SUBSCRIBER_LIST](state, subscribers) {
        state.subscribers = subscribers;
    },

    [SUBSCRIBER_REMOVE](state, id) {
        let index = state.subscribers.findIndex(item => item.id === id);
        if (index>=0) state.subscribers.splice(index, 1);
    },
};

const actions = {
    [SUBSCRIBER_LIST]({commit}) {
        ApiService.get("get/all/subscriber")
            .then(({data}) => {
                commit(SET_SUBSCRIBER_LIST, data);
            })
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};
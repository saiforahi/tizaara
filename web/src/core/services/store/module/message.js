import ApiService from "@/core/services/api.service";

// action types
export const COUNT_UNSEEN_MESSAGE = "count/unseen/message";
export const SET_MESSAGES = "set/updated/message";
export const SET_MESSAGES_EMPTY = "set/message/empty";
export const SET_CONVERSATION__TYPE = "set/conversation/types";
export const SET_USER_TYPE = "set/user/type";

// mutation types
export const SET_COUNT_UNSEEN_MESSAGE = "set/count/unseen/message";
export const UPDATE_MESSAGES = "user/message/updated";
export const MAKE_MESSAGE_EMPTY = "make/user/message/empty";
export const GET_CONVERSATION_TYPE = "get/conversation/types";
export const MAKE_USER_TYPE = "make/user/type";

const state = { 
    unseen_message_count: 0,
    messages:[],//all messages store here
    chat_conversation_types:[],//store auth  user unique conversation user and conversation type like make conversation as a  buyer or  supplier
    user_type:'',
};

const getters = {
    unseen_message_count(state) {
        return state.unseen_message_count;
    },
    messages(state) {
        return state.messages;
    },
    chat_conversation_types(state) {
        return state.chat_conversation_types.map(item=>{
            if (item.message_type === state.user_type) return item;
        }).filter(Boolean);
    },
    user_type(state) {
        return state.user_type;
    },
};

const mutations = {
    [SET_COUNT_UNSEEN_MESSAGE](state, data) {
        state.unseen_message_count = data;
    },
    [SET_MESSAGES](state, data) {
        state.messages.push(data);
    },
    [SET_MESSAGES_EMPTY](state) {
        state.messages=[];
    },
    [SET_CONVERSATION__TYPE](state,data) {
        state.chat_conversation_types=data;
    },
    [SET_USER_TYPE](state,data) {
        state.user_type=data;
    },
};

const actions = {
    [COUNT_UNSEEN_MESSAGE]({commit}) {
        ApiService.get("user/unseen/message/count")
            .then(({data}) => {
                commit(SET_COUNT_UNSEEN_MESSAGE, data);
            });
    },
    [UPDATE_MESSAGES]({commit},data) {
        commit(SET_MESSAGES, data);
    },
    [MAKE_MESSAGE_EMPTY]({commit}) {
        commit(SET_MESSAGES_EMPTY);
    },
    [GET_CONVERSATION_TYPE]({commit}) {
        ApiService.get("user/chat/conversation/types")
            .then(({data}) => {
                commit(SET_CONVERSATION__TYPE, data);
            });
    },
    [MAKE_USER_TYPE]({commit},type) {
        commit(SET_USER_TYPE,type);
    },
    
};

export default {
    state,
    actions,
    mutations,
    getters
};
import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";


// action types
export const VERIFY_AUTH = "verifyAuth";
export const LOGIN = "login";
export const LOGOUT = "logout";
export const REGISTER = "register";
export const UPDATE_USER = "updateUser";

// mutation types
export const PURGE_AUTH = "logOut";
export const SET_AUTH = "setUser";
export const SET_AUTH_USERS = "auth/user";
export const SET_ERROR = "setError";

const state = {
    errors: {},
    user: {},
    isAuthenticated: !!JwtService.getToken(),
};

const getters = {
    currentUser(state) {
        return state.user;
    },
    isSupplier(state) {
        return state.user.account_type == 1 || state.user.account_type == 0;
    },
    isBuyer(state) {
        return state.user.account_type == 2 || state.user.account_type == 0;
    },
    isAuthenticated(state) {
        return state.isAuthenticated;
    },
    user(state){
        return state.user;
    }
};

const actions = {
    [LOGIN](context, credentials) {
        return new Promise(resolve => {
            ApiService.post("user/login", credentials)
                .then(({data}) => {
                    context.commit(SET_AUTH, data);
                    resolve(data);
                })
                .catch(({response}) => {
                    context.commit(SET_ERROR, response.data.errors);
                });
        });
    },
    [LOGOUT](context) {
        ApiService.post("user/logout");
        context.commit(PURGE_AUTH);
    },
    [REGISTER](context, data) {
        return new Promise(resolve => {
            context.commit(SET_AUTH, data);
            resolve(data);
        });
    },
    [VERIFY_AUTH](context) {
        if (JwtService.getToken()) {
            ApiService.setHeader();
            ApiService.get("user/profile")
                .then(({data}) => {
                    context.commit(SET_AUTH_USERS, data.user);
                })
                .catch(({response}) => {
                    context.commit(SET_ERROR, response.data.errors);
                });
        } else {
            context.commit(PURGE_AUTH);
        }
    },
    [UPDATE_USER](context, payload) {
        const {email, username, password, image, bio} = payload;
        const user = {email, username, bio, image};
        if (password) {
            user.password = password;
        }

        return ApiService.put("user", user).then(({data}) => {
            context.commit(SET_AUTH, data);
            return data;
        });
    }
};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
    },
    [SET_AUTH](state, user) {
        state.isAuthenticated = true;
        state.errors = {};
        JwtService.saveToken(user.token);
    },
    [SET_AUTH_USERS](state, user) {
        state.user = user;
    },
    [PURGE_AUTH](state) {
        state.isAuthenticated = false;
        state.user = {};
        state.errors = {};
        JwtService.destroyToken();
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};

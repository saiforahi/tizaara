import Vue from "vue";
import store from "@/core/services/store"
import axios from "axios";
import VueAxios from "vue-axios";
import JwtService from "@/core/services/jwt.service";
import {LOGOUT} from "./store/auth.module";

import router from "../../router";
import {api_base_url} from "../config/app";

/**
 * Service to call HTTP request via Axios
 */
const ApiService = {
    init() {
        Vue.use(VueAxios, axios);
        Vue.axios.defaults.baseURL = api_base_url;
        Vue.axios.interceptors.response.use(responce => {
            return Promise.resolve(responce);
        }, error => {
            if (error.response.status === 401) {
                store.dispatch(LOGOUT);
                router.push({name: "login"});
            }
            return Promise.reject(error);
        });
    },

    /**
     * Set the default HTTP request headers
     */
    setHeader() {
        Vue.axios.defaults.headers.common[
            "Authorization"
            ] = `Bearer ${JwtService.getToken()}`;
    },

    query(resource, params) {
        return Vue.axios.get(resource, params).catch(error => {
            // console.log(error);
            throw new Error(`[KT] ApiService ${error}`);
        });
    },

    /**
     * Send the GET HTTP request
     * @param resource
     * @param slug
     * @returns {*}
     */
    get(resource, slug = "", pram = null) {
        return Vue.axios.get(`${resource}` + (slug ? slug : ''), pram).catch(error => {
            // console.log(error);
            throw new Error(`[KT] ApiService ${error}`);
        });
    },

    /**
     * Set the POST HTTP request
     * @param resource
     * @param params
     * @returns {*}
     */
    post(resource, params) {
        return Vue.axios.post(`${resource}`, params);
    },

    /**
     * Send the UPDATE HTTP request
     * @param resource
     * @param slug
     * @param params
     * @returns {IDBRequest<IDBValidKey> | Promise<void>}
     */
    update(resource, slug, params) {
        return Vue.axios.put(`${resource}/${slug}`, params);
    },

    /**
     * Send the PUT HTTP request
     * @param resource
     * @param params
     * @returns {IDBRequest<IDBValidKey> | Promise<void>}
     */
    put(resource, params) {
        if (params instanceof FormData)
            params.append('_method', 'put');
        else
            params['_method'] = 'put';

        return this.post(resource, params);
    },

    /**
     * Send the DELETE HTTP request
     * @param resource
     * @returns {*}
     */
    delete(resource) {
        return Vue.axios.delete(resource).catch(error => {
            // console.log(error);
            throw new Error(`[RWV] ApiService ${error}`);
        });
    }
};

export default ApiService;

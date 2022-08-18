import axios from "axios";
import VueAxios from "vue-axios";
import Vue from "vue";
import {api_base_url} from "../config/app";
import JwtService from "@/core/services/jwt.service";
import store from "@/core/services/store"
import router from "../../router";
import {LOGOUT} from "@/core/services/store/auth.module";

const ApiService = {
    init() {
        Vue.use(VueAxios, axios);
        Vue.axios.defaults.baseURL = api_base_url;
        Vue.axios.interceptors.response.use(responce => {
            return Promise.resolve(responce);
        }, error => {
            if (error.response.status === 401 && JwtService.getToken()) {
                store.dispatch(LOGOUT);
                router.push({name: "home"});
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
     * @param pram
     * @returns {*}
     */
    get(resource, slug = "", pram = null) {
        return Vue.axios.get(`${resource}` + (slug ? slug : ''), pram).catch(error => {
            // console.log(error);
            throw new Error(`[KT] ApiService ${error}`);
        });
    },

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
}

export default ApiService;

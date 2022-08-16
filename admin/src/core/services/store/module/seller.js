import ApiService from "@/core/services/api.service";

export default {
    state: {
        seller: [],
        seller_package: [],
    },

    getters: {
        sellerList: state => state.seller,
        sellerPackageList: state => state.seller_package,
    },

    actions: {
        SELLER_LIST({commit}) {
            return new Promise((resolve, reject) => {
                ApiService.get("seller")
                    .then(({data}) => {
                        commit('SET_SELLER_LIST', data);
                    })
                    .catch(error => {
                        reject(error)
                    })
            });
        },
        UPDATE_STATUS({commit}, properties) {
            return new Promise((resolve, reject) => {
                ApiService.post("seller-status-update", properties)
                    .then(({data}) => {
                        commit('SET_SELLER_STATUS', data);
                    })
                    .catch(error => {
                        reject(error)
                    })
            });
        },
        SELLER_PACKAGE_LIST({commit}) {
            return new Promise((resolve, reject) => {
                ApiService.get("seller-package")
                    .then(({data}) => {
                        commit('SET_SELLER_PACKAGE_LIST', data);
                    })
                    .catch(error => {
                        reject(error)
                    })
            });
        },
    },
    mutations: {
        SET_SELLER_LIST: (state, data) => {
            state.seller = data;
        },
        SET_SELLER_STATUS: (state, data) => {
            state.seller.find(value => value.id === data.id).status = data.status;
        },
        SELLER_MODIFY: (state, data) => {
            Object.assign(state.seller.find(element => element.id === data.id), data);
        },
        SELLER_ADD: (state, data) => {
            state.seller.unshift(data);
        },
        SELLER_REMOVE: (state, data) => {
            let index = state.seller.findIndex(value => value.id === data);
            state.seller.splice(index, 1);
        },
        SET_SELLER_PACKAGE_LIST: (state, data) => {
            state.seller_package = data;
        },
        SELLER_PACKAGE_ADD: (state, data) => {
            state.seller_package.unshift(data);
        },
        SELLER_PACKAGE_MODIFY: (state, data) => {
            Object.assign(state.seller_package.find(element => element.id === data.id), data);
        },
        SELLER_PACKAGE_REMOVE: (state, data) => {
            let index = state.seller_package.findIndex(value => value.id === data);
            state.seller_package.splice(index, 1);
        },
    }

}

import ApiService from "@/core/services/api.service";

export default {
    state: {
        customer: [],
    },

    getters: {
        customerList: state => state.customer,
    },

    actions: {
        CUSTOMER_LIST({commit}) {
            return new Promise((resolve, reject) => {
                ApiService.get("customer")
                    .then(({data}) => {
                        commit('SET_CUSTOMER_LIST', data);
                    })
                    .catch(error => {
                        reject(error)
                    })
            });
        },
        UPDATE_CUSTOMER_STATUS({commit}, properties) {
            return new Promise((resolve, reject) => {
                ApiService.post("seller-status-update", properties)
                  .then(({data}) => {
                      commit('SET_CUSTOMER_STATUS', data);
                  })
                  .catch(error => {
                      reject(error)
                  })
            });
        },
    },
    mutations: {
        SET_CUSTOMER_LIST: (state, data) => {
            state.customer = data;
        },
        SET_CUSTOMER_STATUS: (state, data) => {
            state.customer.find(value => value.id === data.id).status = data.status;
        },
        CUSTOMER_MODIFY: (state, data) => {
            Object.assign(state.customer.find(element => element.id === data.id), data);
        },
        CUSTOMER_ADD: (state, data) => {
            state.customer.unshift(data);
        },
        CUSTOMER_REMOVE: (state, data) => {
            let index = state.customer.findIndex(value => value.id === data);
            state.customer.splice(index, 1);
        }
    }

}

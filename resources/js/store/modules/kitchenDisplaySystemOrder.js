import axios from 'axios'
import appService from "../../services/appService";


export const kitchenDisplaySystemOrder = {
    namespaced: true,
    state: {
        lists: [],
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        orderItems: function (state) {
            return state.orderItems;
        },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/kds-order';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit('lists', res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        changeStatus: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post(`admin/kds-order/change-status/${payload.id}`, payload).then((res) => {
                    context.dispatch("lists", payload).then().catch();
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        orderItems: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/kds-order/items';
                axios.get(url).then((res) => {
                    context.commit('orderItems', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload
        },
        orderItems: function (state, payload) {
            state.orderItems = payload
        },
    },
}

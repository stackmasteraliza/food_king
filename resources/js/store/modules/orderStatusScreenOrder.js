import axios from 'axios'


export const orderStatusScreenOrder = {
    namespaced: true,
    state: {
        lists: [],
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        mostPopularItems: function (state) {
            return state.mostPopularItems;
        },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/oss-order';
                axios.get(url).then((res) => {
                    context.commit('lists', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        mostPopularItems: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/oss-order/popular-items';
                axios.get(url).then((res) => {
                    context.commit('mostPopularItems', res.data.data);
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
        mostPopularItems: function (state, payload) {
            state.mostPopularItems = payload
        },
    },
}

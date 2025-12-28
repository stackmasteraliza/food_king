import axios from 'axios'
import appService from "../../services/appService";


export const user = {
    namespaced: true,
    state: {
        lists: [],
        addressLists: [],
        page: {},
        pagination: [],
        addressPage: {},
        addressPagination: [],
        temp: {
            temp_id: null,
            isEditing: false,
        },
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        addressLists: function (state) {
            return state.addressLists;
        },
        pagination: function (state) {
            return state.pagination
        },
        page: function (state) {
            return state.page;
        },
        addressPagination: function (state) {
            return state.addressPagination
        },
        addressPage: function (state) {
            return state.addressPage;
        },
        temp: function (state) {
            return state.temp;
        },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/users';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit('lists', res.data.data);
                        context.commit('page', res.data.meta);
                        context.commit('pagination', res.data);
                    }

                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        save: function (context, payload) {
            return new Promise((resolve, reject) => {
                let method = axios.post;
                let url = "/admin/users";
                if (this.state["user"].temp.isEditing) {
                    method = axios.post;
                    url = `/admin/users/${this.state["user"].temp.temp_id}`;
                }
                method(url, payload.form)
                    .then((res) => {
                        context
                            .dispatch("lists", payload.search)
                            .then()
                            .catch();
                        context.commit("reset");
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        addressLists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = `admin/users/address/${payload.id}`;
                if (payload) {
                    url = url + appService.requestHandler(payload.search);
                }
                axios
                    .get(url)
                    .then((res) => {
                        if (
                            typeof payload.vuex === "undefined" ||
                            payload.vuex === true
                        ) {
                            context.commit("addressLists", res.data.data);
                            context.commit("addressPage", res.data.meta);
                            context.commit("addressPagination", res.data);
                        }

                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        saveAddress: function (context, payload) {
            return new Promise((resolve, reject) => {
                let method = axios.post;
                let url = `/admin/users/address/${payload.id}`;
                if (this.state["user"].temp.isEditing) {
                    method = axios.put;
                    url = `/admin/users/address/${payload.id}/${this.state["user"].temp.temp_id}`;
                }
                method(url, payload.form).then(res => {
                    context.dispatch('addressLists', { id: payload.id, search: payload.search }).then().catch();
                    context.commit('reset');
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        editAddress: function (context, payload) {
            context.commit("temp", payload);
        },
        reset: function (context) {
            context.commit("reset");
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload
        },
        addressLists: function (state, payload) {
            state.addressLists = payload
        },
        pagination: function (state, payload) {
            state.pagination = payload;
        },
        page: function (state, payload) {
            if (typeof payload !== "undefined" && payload !== null) {
                state.page = {
                    from: payload.from,
                    to: payload.to,
                    total: payload.total
                }
            }
        },
        addressPagination: function (state, payload) {
            state.addressPagination = payload;
        },
        addressPage: function (state, payload) {
            if (typeof payload !== "undefined" && payload !== null) {
                state.addressPage = {
                    from: payload.from,
                    to: payload.to,
                    total: payload.total,
                };
            }
        },
        temp: function (state, payload) {
            state.temp.temp_id = payload;
            state.temp.isEditing = true;
        },
        reset: function (state) {
            state.temp.temp_id = null;
            state.temp.isEditing = false;
        },
    },
}

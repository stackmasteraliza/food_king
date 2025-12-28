<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.kiosk_machines") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="user_id" class="db-field-title required">
                                {{ $t("label.user") }}
                            </label>
                            <vue-select class="db-field-control f-b-custom-select" id="user_id"
                                v-bind:class="errors.user_id ? 'invalid' : ''" v-model="props.form.user_id"
                                :options="users" label-by="name_email" value-by="id" :closeOnSelect="true"
                                :searchable="true" :clearOnClose="true" placeholder="--" search-placeholder="--" />
                            <small class="db-field-alert" v-if="errors.user_id">{{
                                errors.user_id[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="machine_id" class="db-field-title required">{{
                                $t("label.machine_id")
                                }}</label>
                            <input v-model="props.form.machine_id" v-bind:class="errors.machine_id ? 'invalid' : ''"
                                type="text" id="machine_id" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.machine_id">{{
                                errors.machine_id[0]
                                }}</small>
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label for="username" class="db-field-title required">{{
                                $t("label.username")
                                }}</label>
                            <input v-model="props.form.username" v-bind:class="errors.username ? 'invalid' : ''"
                                type="text" id="username" class="db-field-control" autocomplete="off" />
                            <small class="db-field-alert" v-if="errors.username">{{
                                errors.username[0]
                                }}</small>
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label for="password" class="db-field-title required">{{
                                $t("label.password")
                                }}</label>
                            <input v-model="props.form.password" v-bind:class="errors.password ? 'invalid' : ''"
                                type="password" id="password" class="db-field-control" autocomplete="off" />
                            <small class="db-field-alert" v-if="errors.password">{{
                                errors.password[0]
                                }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="branch_id" class="db-field-title required">
                                {{ $t("label.branch") }}
                            </label>
                            <vue-select class="db-field-control f-b-custom-select" id="branch_id"
                                v-bind:class="errors.branch_id ? 'invalid' : ''" v-model="props.form.branch_id"
                                :options="branches" label-by="name" value-by="id" :closeOnSelect="true"
                                :searchable="true" :clearOnClose="true" placeholder="--" search-placeholder="--" />
                            <small class="db-field-alert" v-if="errors.branch_id">{{
                                errors.branch_id[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="active">{{ $t('label.status') }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.ACTIVE" v-model="props.form.status" id="active"
                                            type="radio" class="custom-radio-field">
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="active" class="db-field-label">{{ $t('label.active') }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.INACTIVE" v-model="props.form.status"
                                            type="radio" id="inactive" class="custom-radio-field">
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="inactive" class="db-field-label">{{ $t('label.inactive') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>

                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t("button.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent";
import LoadingComponent from "../../components/LoadingComponent";
import statusEnum from "../../../../enums/modules/statusEnum";
import askEnum from "../../../../enums/modules/askEnum";
import roleEnum from "../../../../enums/modules/roleEnum";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";

export default {
    name: "KioskMachineCreateComponent",
    components: { SmModalCreateComponent, LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                statusEnum: statusEnum,
                roleEnum: roleEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                }
            },
            errors: {},
        };
    },
    computed: {
        addButton: function () {
            return { title: this.$t('button.add_kiosk_machine') };
        },
        users: function () {
            return this.$store.getters['user/lists'];
        },
        branches: function () {
            return this.$store.getters["branch/lists"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("user/lists", {
            order_column: "id",
            order_type: "asc",
            status: statusEnum.ACTIVE,
            excepts: roleEnum.CUSTOMER,
        });
        this.$store.dispatch("branch/lists", {
            order_column: "id",
            order_type: "asc",
            status: statusEnum.ACTIVE,
        });
        this.loading.isActive = false;
    },
    methods: {
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("kioskMachine/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                machine_id: "",
                branch_id: null,
                user_id: null,
                username: "",
                password: "",
                is_login: askEnum.NO,
                status: statusEnum.ACTIVE,
            };
        },
        save: function () {
            try {
                const tempId = this.$store.getters["kioskMachine/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("kioskMachine/save", this.props).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        this.$t("menu.kiosk_machines")
                    );
                    this.props.form = {
                        machine_id: "",
                        branch_id: null,
                        user_id: null,
                        username: "",
                        password: "",
                        is_login: askEnum.NO,
                        status: statusEnum.ACTIVE,
                    };
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
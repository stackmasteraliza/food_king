<template>
    <LoadingComponent :props="loading" />

    <div class="db-card db-tab-div active">
        <div class="db-card-header border-none">
            <h3 class="db-card-title">{{ $t("menu.kiosk_machines") }}</h3>
            <div class="db-card-filter">
                <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                <KioskMachineCreateComponent :props="props" />
            </div>
        </div>

        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                    <tr class="db-table-head-tr">
                        <th class="db-table-head-th">
                            {{ $t("label.machine_id") }}
                        </th>
                        <th class="db-table-head-th">
                            {{ $t("label.branch") }}
                        </th>
                        <th class="db-table-head-th">{{ $t("label.user") }}</th>
                        <th class="db-table-head-th">{{ $t("label.username") }}</th>
                        <th class="db-table-head-th">{{ $t('label.status') }}</th>
                        <th class="db-table-head-th">
                            {{ $t("label.action") }}
                        </th>
                    </tr>
                </thead>
                <tbody class="db-table-body" v-if="kioskMachines.length > 0">
                    <tr class="db-table-body-tr" v-for="kioskMachine in kioskMachines" :key="kioskMachine">
                        <td class="db-table-body-td">
                            {{ kioskMachine.machine_id }}
                        </td>
                        <td class="db-table-body-td">
                            {{ kioskMachine.branch_name }}
                        </td>
                        <td class="db-table-body-td">
                            {{ kioskMachine.user_name }}
                        </td>
                        <td class="db-table-body-td">
                            {{ kioskMachine.username }}
                        </td>
                        <td class="db-table-body-td">
                            <label for="switcher"
                                class="cs-custom-switcher flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                <input type="checkbox" id="switcher"
                                    :checked="kioskMachine.status === enums.statusEnum.ACTIVE ? true : false"
                                    @change="changeStatus($event, kioskMachine.id)">
                            </label>
                        </td>
                        <td class="db-table-body-td">
                            <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                <button @click="logout(kioskMachine.id)" type="button"
                                    class="db-btn-outline sm primary modal-btn m-0.5"
                                    v-if="kioskMachine.is_login === enums.askEnum.YES">
                                    <i class="lab lab-logout"></i>
                                    <span>{{ $t("button.logout") }}</span>
                                </button>
                                <SmModalEditComponent @click="edit(kioskMachine)" />
                                <SmDeleteComponent @click="destroy(kioskMachine.id)" />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6">
            <PaginationSMBox :pagination="pagination" :method="list" />
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <PaginationTextComponent :props="{ page: paginationPage }" />
                <PaginationBox :pagination="pagination" :method="list" />
            </div>
        </div>
    </div>
</template>
<script>
import LoadingComponent from "../../components/LoadingComponent";
import KioskMachineCreateComponent from "./KioskMachineCreateComponent";
import alertService from "../../../../services/alertService";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";
import appService from "../../../../services/appService";
import TableLimitComponent from "../../components/TableLimitComponent";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent";
import statusEnum from "../../../../enums/modules/statusEnum";
import askEnum from "../../../../enums/modules/askEnum";

export default {
    name: "KioskMachineListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        KioskMachineCreateComponent,
        LoadingComponent,
        SmDeleteComponent,
        SmModalEditComponent,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                statusEnum: statusEnum,
                askEnum: askEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                }
            },
            props: {
                form: {
                    machine_id: "",
                    branch_id: null,
                    user_id: null,
                    username: "",
                    password: "",
                    is_login: askEnum.NO,
                    status: statusEnum.ACTIVE,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: "id",
                    order_type: "desc",
                },
            },
        };
    },
    mounted() {
        this.list();
    },
    computed: {
        kioskMachines: function () {
            return this.$store.getters["kioskMachine/lists"];
        },
        pagination: function () {
            return this.$store.getters["kioskMachine/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["kioskMachine/page"];
        },
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store
                .dispatch("kioskMachine/lists", this.props.search)
                .then((res) => {
                    this.loading.isActive = false;
                })
                .catch((err) => {
                    this.loading.isActive = false;
                });
        },
        edit: function (kioskMachine) {
            appService.modalShow();
            this.loading.isActive = true;
            this.$store.dispatch("kioskMachine/edit", kioskMachine.id);
            this.props.form = {
                machine_id: kioskMachine.machine_id,
                branch_id: kioskMachine.branch_id,
                user_id: kioskMachine.user_id,
                username: kioskMachine.username,
                password: kioskMachine.password,
                is_login: kioskMachine.is_login,
                status: kioskMachine.status,
            };
            this.loading.isActive = false;
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("kioskMachine/destroy", {
                        id: id,
                        search: this.props.search,
                    }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(
                            null,
                            this.$t("menu.kiosk_machines")
                        );
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        changeStatus: function (e, id) {
            try {
                this.loading.isActive = true;
                const isChecked = e.target.checked;
                const status = isChecked ? statusEnum.ACTIVE : statusEnum.INACTIVE;
                this.$store.dispatch('kioskMachine/changeStatus', {
                    id: id,
                    form: { status: status },
                    search: this.props.search
                }).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip((1), this.$t('menu.kiosk_machines'));
                }).catch((err) => {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                })
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        },
        logout: function (id) {
            appService.logoutConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("kioskMachine/logout", {
                        id: id,
                        search: this.props.search,
                    }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successInfo(
                            0,
                            this.$t("message.kiosk_log_out")
                        );
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    },
};
</script>
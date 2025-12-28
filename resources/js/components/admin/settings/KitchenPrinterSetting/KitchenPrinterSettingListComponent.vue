<template>
  <LoadingComponent :props="loading" />

  <div class="db-card db-tab-div active">
    <div class="db-card-header border-none">
      <h3 class="db-card-title">{{ $t("menu.KitchenPrinterSettings") }}</h3>
      <div class="db-card-filter">
        <TableLimitComponent
          :method="list"
          :search="props.search"
          :page="paginationPage"
        />
        <KitchenPrinterSettingCreateComponent :props="props" />
      </div>
    </div>

    <div class="db-table-responsive">
      <table class="db-table stripe">
        <thead class="db-table-head">
          <tr class="db-table-head-tr">
            <th class="db-table-head-th">{{ $t("label.name") }}</th>
            <th class="db-table-head-th">
              {{ $t("label.ip_address") }}
            </th>
            <th class="db-table-head-th">
              {{ $t("label.port") }}
            </th>
            <th class="db-table-head-th">
              {{ $t("label.is_default") }}
            </th>

            <th class="db-table-head-th">
              {{ $t("label.action") }}
            </th>
          </tr>
        </thead>
        <tbody class="db-table-body" v-if="KitchenPrinterSettings.length > 0">
          <tr
            class="db-table-body-tr"
            v-for="KitchenPrinterSetting in KitchenPrinterSettings"
            :key="KitchenPrinterSetting"
          >
            <td class="db-table-body-td">
              {{ KitchenPrinterSetting.name }}({{ $t("label.default") }})
            </td>
            <td class="db-table-body-td">
              {{ KitchenPrinterSetting.ip_address }}
            </td>
            <td class="db-table-body-td">
              {{ KitchenPrinterSetting.port }}
            </td>
            <td class="db-table-body-td">
              <span :class="askClass(KitchenPrinterSetting.is_default)">
                {{ enums.askEnumArray[KitchenPrinterSetting.is_default] }}
              </span>
            </td>
            <td class="db-table-body-td">
              <div
                class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5"
              >
                <SmModalEditComponent @click="edit(KitchenPrinterSetting)" />
                <SmDeleteComponent @click="destroy(KitchenPrinterSetting.id)" />
              </div>
              <div class="modal-btns">
                <button
                  type="button"
                  class="db-btn py-2 text-white bg-primary"
                  @click="testprint(KitchenPrinterSetting)"
                >
                  <i class="lab lab-save"></i>
                  <span>{{ $t("button.testprint") }}</span>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div
      class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6"
    >
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
import KitchenPrinterSettingCreateComponent from "./KitchenPrinterSettingCreateComponent";
import alertService from "../../../../services/alertService";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";
import appService from "../../../../services/appService";
import TableLimitComponent from "../../components/TableLimitComponent";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent";
import askEnum from "../../../../enums/modules/askEnum";

export default {
  name: "KitchenPrinterSettingListComponent",
  components: {
    TableLimitComponent,
    PaginationSMBox,
    PaginationBox,
    PaginationTextComponent,
    KitchenPrinterSettingCreateComponent,
    LoadingComponent,
    SmDeleteComponent,
    SmModalEditComponent,
    askEnum,
  },
  data() {
    return {
      loading: {
        isActive: false,
      },
      enums: {
        askEnum: askEnum,
        askEnumArray: {
          [askEnum.YES]: this.$t("label.yes"),
          [askEnum.NO]: this.$t("label.no"),
        },
      },
      props: {
        form: {
          name: "",
          ip_address: "",
          is_default: askEnum.NO,
          port: 0,
          branch_id: 1,
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
    this.siteList();
    // this.$store.dispatch("defaultAccess/show");
  },
  computed: {
    // defaultAccess: function () {
    //   return this.$store.getters["defaultAccess/show"];
    // },
    KitchenPrinterSettings: function () {
      return this.$store.getters["KitchenPrinterSetting/lists"];
    },
    pagination: function () {
      return this.$store.getters["KitchenPrinterSetting/pagination"];
    },
    paginationPage: function () {
      return this.$store.getters["KitchenPrinterSetting/page"];
    },
  },
  methods: {
    askClass: function (ask) {
      return appService.askClass(ask);
    },
    textShortener: function (text, number = 30) {
      return appService.textShortener(text, number);
    },
    siteList: function () {
      this.loading.isActive = true;
      this.$store
        .dispatch("site/lists")
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    testprint: function (tax) {
      try {
        this.loading.isActive = true;

        appService.modalShow();
        this.props.form = {
          name: tax.name,
          ip_address: tax.ip_address,
          is_default: tax.is_default,
          port: tax.port,
          branch_id: 1,
        };
        this.$store
          .dispatch("KitchenPrinterSetting/testprint", this.props)
          .then((res) => {
            alertService.successFlip(null, res.data.message);
          })
          .catch((err) => {
            appService.modalHide();
            this.loading.isActive = false;
          });
        appService.modalHide();
        this.props.form = {
          name: "",
          ip_address: "",
          is_default: askEnum.NO,
          port: 0,
          branch_id: 1,
        };
      } catch (err) {
        appService.modalHide();
        this.loading.isActive = false;
        alertService.error(err.response.data.message);
      }
    },
    list: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("KitchenPrinterSetting/lists", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    edit: function (tax) {
      appService.modalShow();
      this.loading.isActive = true;
      this.$store.dispatch("KitchenPrinterSetting/edit", tax.id);
      this.props.form = {
        name: tax.name,
        ip_address: tax.ip_address,
        is_default: tax.is_default,
        port: tax.port,
        branch_id: 1,
      };
      this.loading.isActive = false;
    },
    destroy: function (id) {
      appService
        .destroyConfirmation()
        .then((res) => {
          try {
            this.loading.isActive = true;
            this.$store
              .dispatch("KitchenPrinterSetting/destroy", {
                id: id,
                search: this.props.search,
              })
              .then((res) => {
                this.loading.isActive = false;
                alertService.successFlip(
                  null,
                  this.$t("menu.KitchenPrinterSettings")
                );
              })
              .catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
              });
          } catch (err) {
            this.loading.isActive = false;
            alertService.error(err.response.data.message);
          }
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
  },
};
</script>

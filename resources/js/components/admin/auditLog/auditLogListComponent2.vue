<template>
  <LoadingComponent :props="loading" />
  <div class="col-12">
    <div class="db-card db-tab-div active">
      <div class="db-card-header border-none">
        <h3 class="db-card-title">{{ $t("menu.auditLog_report") }}</h3>
        <div class="db-card-filter">
          <TableLimitComponent
            :method="list"
            :search="props.search"
            :page="paginationPage"
          />
          <FilterComponent @click.prevent="handleSlide('item-report-filter')" />
          <div class="dropdown-group">
            <ExportComponent />
            <div
              class="dropdown-list db-card-filter-dropdown-list transition-all duration-300 scale-y-0 origin-top"
            >
              <PrintComponent :props="printObj" />
              <ExcelComponent :method="xls" />
              <PdfComponent :method="pdf" />
            </div>
          </div>
        </div>
      </div>
      <div class="table-filter-div" id="item-report-filter">
        <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
          <div class="row">
            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label for="user" class="db-field-title">{{
                $t("label.user")
              }}</label>

              <vue-select
                class="db-field-control f-b-custom-select"
                id="user"
                v-model="props.search.user"
                :options="users"
                label-by="name"
                value-by="id"
                :closeOnSelect="true"
                :searchable="true"
                :clearOnClose="true"
                placeholder="--"
                search-placeholder="--"
              />
            </div>

            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label for="action" class="db-field-title after:hidden">{{
                $t("label.type")
              }}</label>
              <vue-select
                class="db-field-control f-b-custom-select"
                id="action"
                v-model="props.search.action_type"
                :options="[
                  { id: enums.actionTypeEnum.update, name: $t('label.veg') },
                  {
                    id: enums.actionTypeEnum.delete,
                    name: $t('label.non_veg'),
                  },
                ]"
                label-by="name"
                value-by="id"
                :closeOnSelect="true"
                :searchable="true"
                :clearOnClose="true"
                placeholder="--"
                search-placeholder="--"
              />
            </div>

            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label
                for="searchStartDate"
                class="db-field-title after:hidden"
                >{{ $t("label.date") }}</label
              >
              <Datepicker
                hideInputIcon
                autoApply
                :enableTimePicker="false"
                utc="false"
                @update:modelValue="handleDate"
                v-model="props.form.date"
                range
                :preset-ranges="presetRanges"
              >
                <template #yearly="{ label, range, presetDateRange }">
                  <span @click="presetDateRange(range)">{{ label }}</span>
                </template>
              </Datepicker>
            </div>

            <div class="col-12">
              <div class="flex flex-wrap gap-3 mt-4">
                <button class="db-btn py-2 text-white bg-primary">
                  <i class="lab lab-search-line lab-font-size-16"></i>
                  <span>{{ $t("button.search") }}</span>
                </button>
                <button
                  class="db-btn py-2 text-white bg-gray-600"
                  @click="clear"
                >
                  <i class="lab lab-cross-line-2 lab-font-size-22"></i>
                  <span>{{ $t("button.clear") }}</span>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="db-table-responsive">
        <table class="db-table stripe" id="print" :dir="direction">
          <thead class="db-table-head">
            <tr class="db-table-head-tr">
              <th class="db-table-head-th">{{ $t("label.model_type") }}</th>
              <th class="db-table-head-th">{{ $t("label.description") }}</th>
              <th class="db-table-head-th">{{ $t("label.user") }}</th>
              <th class="db-table-head-th">{{ $t("label.action_type") }}</th>
              <th class="db-table-head-th">{{ $t("label.before") }}</th>
              <th class="db-table-head-th">{{ $t("label.after") }}</th>
              <th class="db-table-head-th">{{ $t("label.created_at") }}</th>
              <th class="db-table-head-th">{{ $t("label.ip_address") }}</th>
            </tr>
          </thead>
          <tbody class="db-table-body" v-if="auditLogs.length > 0">
            <tr
              class="db-table-body-tr"
              v-for="auditLog in auditLogs"
              :key="auditLog"
            >
              <td class="db-table-body-td">
                {{ modelName(auditLog.model_type) }}
              </td>
              <td class="db-table-body-td">
                {{ auditLog.description }}
              </td>
              <td class="db-table-body-td">{{ auditLog.user.name }}</td>
              <td class="db-table-body-td">
                {{ enums.actionTypeEnumArray[auditLog.action_type] }}
              </td>
              <td class="db-table-body-td">{{ format(auditLog.before) }}</td>
              <td class="db-table-body-td">{{ format(auditLog.after) }}</td>
              <td class="db-table-body-td">
                {{ formatDate(auditLog.created_at) }}
              </td>
              <td class="db-table-body-td">
                {{ auditLog.ip_address }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div
        class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6"
      >
        <PaginationSMBox :pagination="pagination" :method="list" />
        <div
          class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between"
        >
          <PaginationTextComponent :props="{ page: paginationPage }" />
          <PaginationBox :pagination="pagination" :method="list" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import paymentTypeEnum from "../../../enums/modules/paymentTypeEnum";
import TableLimitComponent from "../components/TableLimitComponent";
import FilterComponent from "../components/buttons/collapse/FilterComponent";
import ExportComponent from "../components/buttons/export/ExportComponent";
import print from "vue3-print-nb";
import PrintComponent from "../components/buttons/export/PrintComponent";
import ExcelComponent from "../components/buttons/export/ExcelComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref } from "vue";
import {
  endOfMonth,
  endOfYear,
  startOfMonth,
  startOfYear,
  subMonths,
} from "date-fns";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent";

import displayModeEnum from "../../../enums/modules/displayModeEnum";
import PdfComponent from "../components/buttons/export/PdfComponent";

export default {
  name: "auditLogListComponent2",
  components: {
    TableLimitComponent,
    PaginationSMBox,
    PaginationBox,
    PaginationTextComponent,
    LoadingComponent,
    ExportComponent,
    FilterComponent,
    PrintComponent,
    ExcelComponent,
    Datepicker,
    SmIconViewComponent,
    PdfComponent,
  },
  setup() {
    const date = ref();

    const presetRanges = ref([
      { label: "Today", range: [new Date(), new Date()] },
      {
        label: "This month",
        range: [startOfMonth(new Date()), endOfMonth(new Date())],
      },
      {
        label: "Last month",
        range: [
          startOfMonth(subMonths(new Date(), 1)),
          endOfMonth(subMonths(new Date(), 1)),
        ],
      },
      {
        label: "This year",
        range: [startOfYear(new Date()), endOfYear(new Date())],
      },
      {
        label: "This year (slot)",
        range: [startOfYear(new Date()), endOfYear(new Date())],
        slot: "yearly",
      },
    ]);

    return {
      date,
      presetRanges,
    };
  },
  data() {
    return {
      loading: {
        isActive: false,
      },
      enums: {
        actionTypeEnum: {
          update: this.$t("تعديل"),
          delete: this.$t("حذف"),
        },

        actionTypeEnumArray: {
          update: this.$t("تعديل"),
          delete: this.$t("حذف"),
        },
      },
      printLoading: true,
      printObj: {
        id: "print",
        popTitle: this.$t("menu.auditLog_report"),
      },
      props: {
        form: {
          date: null,
        },
        search: {
          paginate: 1,
          page: 1,
          per_page: 10,
          order_column: "id",
          model_type: null,
          user: null,
          action_type: null,
          from_date: "",
          to_date: "",
        },
      },
    };
  },
  mounted() {
    this.list();
    this.loading.isActive = true;
    this.props.search.page = 1;
  },
  computed: {
    auditLogs: function () {
      return this.$store.getters["auditLog/lists"];
    },

    pagination: function () {
      return this.$store.getters["auditLog/pagination"];
    },
    paginationPage: function () {
      return this.$store.getters["auditLog/page"];
    },
    users: function () {
      return this.$store.getters["user/lists"];
    },
    direction: function () {
      return this.$store.getters["frontendLanguage/show"].display_mode ===
        displayModeEnum.RTL
        ? "rtl"
        : "ltr";
    },
  },
  methods: {
    floatNumber(e) {
      return appService.floatNumber(e);
    },
    statusClass: function (status) {
      return appService.statusClass(status);
    },
    textShortener: function (text, number = 30) {
      return appService.textShortener(text, number);
    },
    handleSlide: function (id) {
      return appService.handleSlide(id);
    },
    formatDate(date) {
      return new Date(date).toLocaleString("ar-EG");
    },
    format(obj) {
      return JSON.stringify(obj, null, 2);
    },
    modelName(className) {
      return className.split("\\").pop();
    },
    search: function () {
      this.list();
    },
    handleDate: function (e) {
      if (e) {
        this.props.search.from_date = e[0];
        this.props.search.to_date = e[1];
      } else {
        this.props.form.date = null;
        this.props.search.from_date = null;
        this.props.search.to_date = null;
      }
    },

    clear: function () {
      this.props.search.paginate = 1;
      this.props.search.page = 1;
      this.props.search.model_type = null;
      this.props.search.user = null;
      this.props.search.action_type = null;
      this.props.search.from_date = "";
      this.props.search.to_date = "";
      this.list();
    },
    list: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("auditLog/lists", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },

    xls: function () {
      this.loading.isActive = true;
      this.$store
        .dispatch("auditLog/export", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
          const blob = new Blob([res.data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          });
          const link = document.createElement("a");
          link.href = URL.createObjectURL(blob);
          link.download = this.$t("menu.auditLog_report");
          link.click();
          URL.revokeObjectURL(link.href);
        })
        .catch((err) => {
          this.loading.isActive = false;
          alertService.error(err.response.data.message);
        });
    },
    pdf: function () {
      this.loading.isActive = true;
      this.$store
        .dispatch("auditLog/export", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
          const blob = new Blob([res.data]);
          const link = document.createElement("a");
          link.href = URL.createObjectURL(blob);
          link.download = this.$t("menu.auditLog_report") + ".pdf";
          link.click();
          URL.revokeObjectURL(link.href);
        })
        .catch((err) => {
          this.loading.isActive = false;
          alertService.error(err.response.data.message);
        });
    },
  },
};
</script>
<style scoped>
@media print {
  .hidden-print {
    display: none !important;
  }
}
</style>

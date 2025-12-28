<template>
  <LoadingComponent :props="loading" />
  <div class="col-12">
    <div class="db-card db-tab-div active">
      <div class="db-card-header border-none">
        <h3 class="db-card-title">{{ $t("menu.sales_report") }}</h3>
        <div class="db-card-filter">
          <TableLimitComponent
            :method="list"
            :search="props.search"
            :page="paginationPage"
          />
          <FilterComponent
            @click.prevent="handleSlide('sales-report-filter')"
          />
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
      <div class="table-filter-div" id="sales-report-filter">
        <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
          <div class="row">
            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label for="order_id" class="db-field-title after:hidden">{{
                $t("label.order_id")
              }}</label>
              <input
                id="order_id"
                v-model="props.search.order_serial_no"
                type="text"
                class="db-field-control"
              />
            </div>
            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label for="searchStatus" class="db-field-title after:hidden">{{
                $t("label.status")
              }}</label>
              <vue-select
                class="db-field-control f-b-custom-select"
                id="searchStatus"
                v-model="props.search.status"
                :options="[
                  {
                    id: enums.orderStatusEnum.PENDING,
                    name: $t('label.pending'),
                  },
                  {
                    id: enums.orderStatusEnum.ACCEPT,
                    name: $t('label.accept'),
                  },
                  {
                    id: enums.orderStatusEnum.PREPARING,
                    name: $t('label.preparing'),
                  },
                  {
                    id: enums.orderStatusEnum.PREPARED,
                    name: $t('label.prepared'),
                  },
                  {
                    id: enums.orderStatusEnum.OUT_FOR_DELIVERY,
                    name: $t('label.out_for_delivery'),
                  },
                  {
                    id: enums.orderStatusEnum.DELIVERED,
                    name: $t('label.delivered'),
                  },
                  {
                    id: enums.orderStatusEnum.CANCELED,
                    name: $t('label.canceled'),
                  },
                  {
                    id: enums.orderStatusEnum.REJECTED,
                    name: $t('label.rejected'),
                  },
                  {
                    id: enums.orderStatusEnum.RETURNED,
                    name: $t('label.returned'),
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
            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label for="searchStatus" class="db-field-title after:hidden">{{
                $t("label.paid_status")
              }}</label>
              <vue-select
                class="db-field-control f-b-custom-select"
                id="searchStatus"
                v-model="props.search.payment_status"
                :options="[
                  { id: enums.paymentStatusEnum.PAID, name: $t('label.paid') },
                  {
                    id: enums.paymentStatusEnum.UNPAID,
                    name: $t('label.unpaid'),
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
              <label for="searchStatus" class="db-field-title after:hidden">{{
                $t("label.payment_type")
              }}</label>
              <vue-select
                class="db-field-control f-b-custom-select"
                id="searchStatus"
                v-model="props.search.payment_method"
                :options="paymentGateways"
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
              <label for="delivery_boy_id" class="db-field-title">{{
                $t("label.delivery_boy")
              }}</label>

              <vue-select
                class="db-field-control f-b-custom-select"
                id="site_default_branch"
                v-model="props.search.delivery_boy_id"
                :options="deliveryBoys"
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
              <label for="user_id" class="db-field-title">{{
                $t("label.EmployeeName/CustmerName")
              }}</label>

              <vue-select
                class="db-field-control f-b-custom-select"
                id="user_id"
                v-model="props.search.user_id"
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
              <label for="creator_id" class="db-field-title">{{
                $t("label.usercreator")
              }}</label>

              <vue-select
                class="db-field-control f-b-custom-select"
                id="user_id"
                v-model="props.search.creator_id"
                :options="employees"
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
              <label for="source" class="db-field-title after:hidden">{{
                $t("label.source")
              }}</label>
              <vue-select
                class="db-field-control f-b-custom-select"
                id="source"
                v-model="props.search.source"
                :options="enums.sourceObject"
                label-by="name"
                value-by="value"
                :closeOnSelect="true"
                :searchable="true"
                :clearOnClose="true"
                placeholder="--"
                search-placeholder="--"
              />
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

      <div class="row px-5 mt-5 mb-5">
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
          <div class="border flex items-center gap-4 p-4 rounded-lg">
            <div
              class="bg-[#F7F7F7] w-12 h-12 rounded-full flex items-center justify-center"
            >
              <i
                class="lab-total-orders text-[#6E7191] text-2xl lab-font-size-24"
              ></i>
            </div>
            <div>
              <h3 class="font-medium text-sm capitalize tracking-wide mb-1">
                {{ $t("label.total_orders") }}
              </h3>
              <h4 class="font-bold text-lg text-[#6E7191]">
                {{ salesReportOverview.total_orders }}
              </h4>
            </div>
          </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
          <div class="border flex items-center gap-4 p-4 rounded-lg">
            <div
              class="bg-[#F7F7F7] w-12 h-12 rounded-full flex items-center justify-center"
            >
              <i
                class="lab-total-orders text-[#6E7191] text-2xl lab-font-size-24"
              ></i>
            </div>

            <div>
              <h3 class="font-medium text-sm capitalize tracking-wide mb-1">
                {{ $t("label.total_cash") }}
              </h3>
              <h4 class="font-bold text-lg text-[#6E7191]">
                {{ salesReportOverview.total_cash }}
              </h4>
            </div>
          </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
          <div class="border flex items-center gap-4 p-4 rounded-lg">
            <div
              class="bg-[#F7F7F7] w-12 h-12 rounded-full flex items-center justify-center"
            >
              <i
                class="lab-total-orders text-[#6E7191] text-2xl lab-font-size-24"
              ></i>
            </div>

            <div>
              <h3 class="font-medium text-sm capitalize tracking-wide mb-1">
                {{ $t("إجمالي البيع عبر الشبكة") }}
              </h3>
              <h4 class="font-bold text-lg text-[#6E7191]">
                {{ salesReportOverview.total_card }}
              </h4>
            </div>
          </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
          <div class="border flex items-center gap-4 p-4 rounded-lg">
            <div
              class="bg-[#F7F7F7] w-12 h-12 rounded-full flex items-center justify-center"
            >
              <i
                class="lab-total-orders text-[#6E7191] text-2xl lab-font-size-24"
              ></i>
            </div>

            <div>
              <h3 class="font-medium text-sm capitalize tracking-wide mb-1">
                {{ $t("label.total_mada") }}
              </h3>
              <h4 class="font-bold text-lg text-[#6E7191]">
                {{ salesReportOverview.total_mada }}
              </h4>
            </div>
          </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
          <div class="border flex items-center gap-4 p-4 rounded-lg">
            <div
              class="bg-[#F7F7F7] w-12 h-12 rounded-full flex items-center justify-center"
            >
              <i
                class="lab-total-orders text-[#6E7191] text-2xl lab-font-size-24"
              ></i>
            </div>
            <div>
              <h3 class="font-medium text-sm capitalize tracking-wide mb-1">
                {{ $t("label.total_visa") }}
              </h3>
              <h4 class="font-bold text-lg text-[#6E7191]">
                {{ salesReportOverview.total_visa }}
              </h4>
            </div>
          </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
          <div class="border flex items-center gap-4 p-4 rounded-lg">
            <div
              class="bg-[#F7F7F7] w-12 h-12 rounded-full flex items-center justify-center"
            >
              <i
                class="lab-total-orders text-[#6E7191] text-2xl lab-font-size-24"
              ></i>
            </div>
            <div>
              <h3 class="font-medium text-sm capitalize tracking-wide mb-1">
                {{ $t("label.total_MOBILE_BANKING") }}
              </h3>
              <h4 class="font-bold text-lg text-[#6E7191]">
                {{ salesReportOverview.total_MOBILE_BANKING }}
              </h4>
            </div>
          </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
          <div class="border flex items-center gap-4 p-4 rounded-lg">
            <div
              class="bg-[#F7F7F7] w-12 h-12 rounded-full flex items-center justify-center"
            >
              <i
                class="lab-total-orders text-[#6E7191] text-2xl lab-font-size-24"
              ></i>
            </div>
            <div>
              <h3 class="font-medium text-sm capitalize tracking-wide mb-1">
                {{ $t("label.total_other") }}
              </h3>
              <h4 class="font-bold text-lg text-[#6E7191]">
                {{ salesReportOverview.total_other }}
              </h4>
            </div>
          </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
          <div class="border flex items-center gap-4 p-4 rounded-lg">
            <div
              class="bg-[#F7F7F7] w-12 h-12 rounded-full flex items-center justify-center"
            >
              <i
                class="lab-total-sale text-[#6E7191] text-2xl lab-font-size-24"
              ></i>
            </div>
            <div>
              <h3 class="font-medium text-sm capitalize tracking-wide mb-1">
                {{ $t("label.total_earnings") }}
              </h3>
              <h4 class="font-bold text-lg text-[#6E7191]">
                {{ salesReportOverview.total_earnings }}
              </h4>
            </div>
          </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
          <div class="border flex items-center gap-4 p-4 rounded-lg">
            <div
              class="bg-[#F7F7F7] w-12 h-12 rounded-full flex items-center justify-center"
            >
              <i
                class="lab-fill-ticket-discount text-[#6E7191] text-2xl lab-font-size-24"
              ></i>
            </div>
            <div>
              <h3 class="font-medium text-sm capitalize tracking-wide mb-1">
                {{ $t("label.total_discounts") }}
              </h3>
              <h4 class="font-bold text-lg text-[#6E7191]">
                {{ salesReportOverview.total_discounts }}
              </h4>
            </div>
          </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
          <div class="border flex items-center gap-4 p-4 rounded-lg">
            <div
              class="bg-[#F7F7F7] w-12 h-12 rounded-full flex items-center justify-center"
            >
              <i
                class="lab-fill-moneys text-[#6E7191] text-2xl lab-font-size-24"
              ></i>
            </div>
            <div>
              <h3 class="font-medium text-sm capitalize tracking-wide mb-1">
                {{ $t("label.total_delivery_charges") }}
              </h3>
              <h4 class="font-bold text-lg text-[#6E7191]">
                {{ salesReportOverview.total_delivery_charges }}
              </h4>
            </div>
          </div>
        </div>
      </div>

      <div class="db-table-responsive">
        <table class="db-table stripe" id="print" :dir="direction">
          <thead class="db-table-head">
            <tr class="db-table-head-tr">
              <th class="db-table-head-th">{{ $t("label.order_id") }}</th>
              <th class="db-table-head-th">
                {{ $t("label.EmployeeName/CustmerName") }}
              </th>
              <th class="db-table-head-th">{{ $t("label.date") }}</th>
              <th class="db-table-head-th">{{ $t("label.total") }}</th>
              <th class="db-table-head-th">{{ $t("label.discount") }}</th>
              <th class="db-table-head-th">
                {{ $t("label.delivery_charge") }}
              </th>
              <th class="db-table-head-th">{{ $t("label.payment_type") }}</th>
              <th class="db-table-head-th">{{ $t("label.payment_status") }}</th>
            </tr>
          </thead>
          <tbody class="db-table-body" v-if="salesReports.length > 0">
            <tr
              class="db-table-body-tr"
              v-for="salesReport in salesReports"
              :key="salesReport"
            >
              <td class="db-table-body-td">
                {{ salesReport.order_serial_no }}
              </td>
              <td class="db-table-body-td">
                {{ salesReport.customer_name }}
              </td>
              <td class="db-table-body-td">{{ salesReport.order_datetime }}</td>
              <td class="db-table-body-td">
                {{ salesReport.total_amount_price }}
              </td>
              <td class="db-table-body-td">
                {{ salesReport.discount_amount_price }}
              </td>
              <td class="db-table-body-td">
                {{ salesReport.delivery_charge_amount_price }}
              </td>
              <td class="db-table-body-td">
                <span v-if="salesReport.transaction">
                  {{ salesReport.transaction }}
                </span>
                <span
                  v-else-if="salesReport.source === enums.orderTypeEnum.POS"
                >
                  {{
                    enums.posPaymentMethodEnumArray[
                      salesReport.pos_payment_method
                    ]
                      ? enums.posPaymentMethodEnumArray[
                          salesReport.pos_payment_method
                        ]
                      : enums.paymentTypeEnumArray[salesReport.payment_method]
                  }}
                </span>
                <span v-else>
                  {{ enums.paymentTypeEnumArray[salesReport.payment_method] }}
                </span>
              </td>
              <td class="db-table-body-td">
                <span :class="statusClass(salesReport.payment_status)">
                  {{ enums.paymentStatusEnumArray[salesReport.payment_status] }}
                </span>
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
import paymentStatusEnum from "../../../enums/modules/paymentStatusEnum";
import orderStatusEnum from "../../../enums/modules/orderStatusEnum";
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
import statusEnum from "../../../enums/modules/statusEnum";
import sourceEnum from "../../../enums/modules/sourceEnum";
import displayModeEnum from "../../../enums/modules/displayModeEnum";
import posPaymentMethodEnum from "../../../enums/modules/posPaymentMethodEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import PdfComponent from "../components/buttons/export/PdfComponent";

export default {
  name: "SalesReportListComponent",
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
        paymentStatusEnum: paymentStatusEnum,
        paymentTypeEnum: paymentTypeEnum,
        orderStatusEnum: orderStatusEnum,
        orderTypeEnum: orderTypeEnum,
        sourceEnum: sourceEnum,
        paymentStatusEnumArray: {
          [paymentStatusEnum.PAID]: this.$t("label.paid"),
          [paymentStatusEnum.UNPAID]: this.$t("label.unpaid"),
        },
        paymentTypeEnumArray: {
          [paymentTypeEnum.CASH_ON_DELIVERY]: this.$t("label.cash_on_delivery"),
          [paymentTypeEnum.E_WALLET]: this.$t("label.e_wallet"),
          [paymentTypeEnum.PAYPAL]: this.$t("label.paypal"),
        },
        posPaymentMethodEnumArray: {
          [posPaymentMethodEnum.CASH]: this.$t("label.cash"),
          [posPaymentMethodEnum.CARD]: this.$t("label.card"),
          [posPaymentMethodEnum.MOBILE_BANKING]: this.$t(
            "label.mobile_banking"
          ),
          [posPaymentMethodEnum.OTHER]: this.$t("label.other"),
          [posPaymentMethodEnum.Mada]: this.$t("مدى"),
          [posPaymentMethodEnum.Viza]: this.$t("فيزا"),
        },
        orderStatusEnumArray: {
          [orderStatusEnum.PENDING]: this.$t("label.pending"),
          [orderStatusEnum.ACCEPT]: this.$t("label.accept"),
          [orderStatusEnum.PREPARING]: this.$t("label.preparing"),
          [orderStatusEnum.OUT_FOR_DELIVERY]: this.$t("label.out_for_delivery"),
          [orderStatusEnum.DELIVERED]: this.$t("label.delivered"),
          [orderStatusEnum.CANCELED]: this.$t("label.canceled"),
          [orderStatusEnum.REJECTED]: this.$t("label.rejected"),
          [orderStatusEnum.RETURNED]: this.$t("label.returned"),
        },
        sourceObject: [
          {
            name: this.$t("label.web"),
            value: sourceEnum.WEB,
          },
          {
            name: this.$t("label.app"),
            value: sourceEnum.APP,
          },
          {
            name: this.$t("label.pos"),
            value: sourceEnum.POS,
          },
        ],
      },
      paymentGateways: [],
      printLoading: true,
      printObj: {
        id: "print",
        popTitle: this.$t("menu.sales_report"),
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
          payment_status: null,
          payment_method: null,
          order_serial_no: "",
          status: null,
          from_date: "",
          to_date: "",
          delivery_boy_id: null,
          source: null,
          user_id: null,
          creator_id: null,
        },
      },
    };
  },
  mounted() {
    this.list();
    this.$store.dispatch("deliveryBoy/lists", {
      order_column: "id",
      order_type: "asc",
      status: statusEnum.ACTIVE,
    });
    this.$store
      .dispatch("paymentGateway/lists", {
        order_column: "id",
        order_type: "asc",
        status: statusEnum.ACTIVE,
      })
      .then((res) => {
        this.paymentGateways = res.data.data;
        this.paymentGateways = [
          ...this.paymentGateways,
          { id: -posPaymentMethodEnum.CARD, name: this.$t("label.card") },
          { id: -posPaymentMethodEnum.CASH, name: this.$t("label.cash") },
          {
            id: -posPaymentMethodEnum.MOBILE_BANKING,
            name: this.$t("label.mobile_banking"),
          },
          { id: -posPaymentMethodEnum.OTHER, name: this.$t("label.other") },
          { id: -posPaymentMethodEnum.Mada, name: this.$t("مدى") },
          { id: -posPaymentMethodEnum.Viza, name: this.$t("فيزا") },
        ];
      });
  },
  computed: {
    salesReports: function () {
      return this.$store.getters["salesReport/lists"];
    },
    deliveryBoys: function () {
      return this.$store.getters["deliveryBoy/lists"];
    },
    users: function () {
      return this.$store.getters["user/lists"];
    },
    employees: function () {
      return this.$store.getters["user/lists"];
    },
    pagination: function () {
      return this.$store.getters["salesReport/pagination"];
    },
    paginationPage: function () {
      return this.$store.getters["salesReport/page"];
    },
    direction: function () {
      return this.$store.getters["frontendLanguage/show"].display_mode ===
        displayModeEnum.RTL
        ? "rtl"
        : "ltr";
    },
    salesReportOverview: function () {
      return this.$store.getters["salesReport/salesReportOverview"];
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
      this.props.search.order_serial_no = "";
      this.props.search.payment_status = null;
      this.props.search.payment_method = null;
      this.props.search.status = null;
      this.props.search.from_date = "";
      this.props.search.to_date = "";
      this.props.search.delivery_boy_id = null;
      this.props.search.user_id = null;
      this.props.search.creator_id = null;
      this.props.search.source = null;
      this.list();
    },
    list: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("salesReport/lists", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });

      this.$store
        .dispatch("salesReport/salesReportOverview", this.props.search)
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
        .dispatch("salesReport/export", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
          const blob = new Blob([res.data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          });
          const link = document.createElement("a");
          link.href = URL.createObjectURL(blob);
          link.download = this.$t("menu.sales_report");
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
        .dispatch("salesReport/pdf", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
          const blob = new Blob([res.data]);
          const link = document.createElement("a");
          link.href = URL.createObjectURL(blob);
          link.download = this.$t("menu.sales_report") + ".pdf";
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

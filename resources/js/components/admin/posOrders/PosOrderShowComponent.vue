<template>
  <LoadingComponent :props="loading" />
  <div class="col-12">
    <div class="db-card p-4">
      <div class="flex flex-wrap gap-y-5 items-end justify-between">
        <div>
          <div class="flex flex-wrap items-start gap-y-2 gap-x-6 mb-5">
            <p class="text-2xl font-medium">
              {{ $t("label.order_id") }}:
              <span class="text-heading"> #{{ order.order_serial_no }} </span>
            </p>
            <div class="flex items-center gap-2 mt-1.5">
              <span
                :class="
                  'text-xs capitalize h-5 leading-5 px-2 rounded-3xl text-[#FB4E4E] bg-[#FFDADA]' +
                  statusClass(order.payment_status)
                "
              >
                {{ enums.paymentStatusEnumArray[order.payment_status] }}
              </span>
              <span
                :class="
                  'text-xs capitalize px-2 rounded-3xl ' +
                  orderStatusClass(order.status)
                "
              >
                {{ enums.orderStatusEnumArray[order.status] }}
              </span>
            </div>
          </div>
          <ul class="flex flex-col gap-2">
            <li class="flex items-center gap-2">
              <i class="lab lab-calendar-line lab-font-size-16"></i>
              <span class="text-xs">{{ order.order_datetime }}</span>
            </li>
            <li class="text-xs">
              {{ $t("label.payment_type") }}:

              <span class="text-heading">
                {{ enums.posPaymentMethodEnumArray[order.pos_payment_method] }}

                <span
                  v-if="
                    order.pos_payment_method !==
                      enums.posPaymentMethodEnum.CASH && order.pos_payment_note
                  "
                >
                  ({{ order.pos_payment_note }})</span
                >
                <span
                  v-if="
                    order.pos_payment_method === enums.posPaymentMethodEnum.CARD
                  "
                >
                  {{ $t("مبلغ النقد") }}
                  {{ order.pos_received_currency_amount }}
                  {{ $t("مبلغ الشبكه") }} {{ order.NetworkAmount }}
                </span>
              </span>
            </li>
            <li class="text-xs">
              {{ $t("label.order_type") }}:
              <span class="text-heading">
                {{ enums.orderTypeEnumArray[order.order_type] }}
              </span>
            </li>
            <li class="text-xs">
              {{ $t("label.delivery_time") }}:
              <span class="text-heading">
                {{ order.delivery_date }} {{ order.delivery_time }}
              </span>
            </li>
            <li class="text-xs" v-if="order.token">
              {{ $t("label.token_no") }}:
              <span class="text-heading"> #{{ order.token }} </span>
            </li>
            <li class="text-xs" v-if="order.table_name">
              {{ $t("label.table_name") }}:
              <span class="text-heading">
                {{ order.table_name }}
              </span>
            </li>
          </ul>
        </div>

        <div class="flex flex-wrap gap-3">
          <div
            class="relative"
            v-if="order.order_type === enums.orderTypeEnum.DELIVERY"
          >
            <select
              v-model="delivery_boy"
              @change="selectDeliveryBoy($event)"
              class="text-sm capitalize appearance-none pl-4 pr-10 h-[38px] rounded border border-primary bg-white text-primary"
            >
              <option value="0" disabled selected hidden>
                {{ $t("label.select_delivery_boy") }}
              </option>
              <option
                v-for="deliveryBoy in deliveryBoys"
                :value="deliveryBoy.id"
              >
                {{ deliveryBoy.name }}
              </option>
            </select>
            <i
              class="lab lab-arrow-down-2 lab-font-size-16 absolute top-1/2 right-3.5 -translate-y-1/2 text-primary"
            ></i>
          </div>
          <div class="relative">
            <select
              v-model="payment_status"
              @change="changePaymentStatus($event)"
              class="text-sm capitalize appearance-none pl-4 pr-10 h-[38px] rounded border border-primary bg-white text-primary"
            >
              <option
                v-for="paymentStatus in enums.paymentStatusObject"
                :value="paymentStatus.value"
              >
                {{ paymentStatus.name }}
              </option>
            </select>
            <i
              class="lab lab-arrow-down-2 lab-font-size-16 absolute top-1/2 right-3.5 -translate-y-1/2 text-primary"
            ></i>
          </div>
          <div class="relative">
            <select
              v-model="order_status"
              @change.prevent="orderStatus($event)"
              class="text-sm capitalize appearance-none pl-4 pr-10 h-[38px] rounded border border-primary bg-white text-primary"
            >
              <option
                v-for="orderStatus in enums.orderStatusObject"
                :value="orderStatus.value"
              >
                {{ orderStatus.name }}
              </option>
            </select>
            <i
              class="lab lab-arrow-down-2 lab-font-size-16 absolute top-1/2 right-3.5 -translate-y-1/2 text-primary"
            ></i>
          </div>

          <button
            type="button"
            v-print="printObj"
            class="flex items-center justify-center gap-2 px-4 h-[38px] rounded shadow-db-card bg-primary"
          >
            <i class="lab lab-printer-line lab-font-size-16 text-white"></i>
            <span class="text-sm capitalize text-white">{{
              $t("button.print_invoice")
            }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 sm:col-6">
    <div class="db-card">
      <div class="db-card-header">
        <h3 class="db-card-title">{{ $t("label.order_details") }}</h3>
      </div>
      <div class="db-card-body">
        <div class="pl-3">
          <div
            class="mb-3 pb-3 border-b last:mb-0 last:pb-0 last:border-b-0 border-gray-2"
            v-if="orderItems.length > 0"
            v-for="item in orderItems"
            :key="item"
          >
            <div class="flex items-center gap-3 relative">
              <h3
                class="absolute top-5 -left-3 text-sm w-[26px] h-[26px] leading-[26px] text-center rounded-full text-white bg-heading"
              >
                {{ item.quantity }}
              </h3>
              <img
                class="w-16 h-16 rounded-lg flex-shrink-0"
                :src="item.item_image"
                alt="thumbnail"
              />
              <div class="w-full">
                <a
                  href="#"
                  class="text-sm font-medium capitalize transition text-heading hover:underline"
                >
                  {{ item.item_name }}
                </a>
                <p
                  v-if="item.item_variations.length !== 0"
                  class="capitalize text-xs mb-1.5"
                >
                  <span v-for="(variation, index) in item.item_variations">
                    {{ variation.variation_name }}: {{ variation.name
                    }}<span v-if="index + 1 < item.item_variations.length"
                      >,&nbsp;</span
                    >
                  </span>
                </p>
                <h3 class="text-xs font-semibold">
                  {{ item.total_currency_price }}
                </h3>
              </div>
            </div>
            <ul
              v-if="item.item_extras.length > 0 || item.instruction !== ''"
              class="flex flex-col gap-1.5 mt-2"
            >
              <li class="flex gap-1" v-if="item.item_extras.length > 0">
                <h3 class="capitalize text-xs w-fit whitespace-nowrap">
                  {{ $t("label.extras") }}:
                </h3>
                <p class="text-xs">
                  <span v-for="(extra, index) in item.item_extras">
                    {{ extra.name
                    }}<span v-if="index + 1 < item.item_extras.length"
                      >,&nbsp;</span
                    >
                  </span>
                </p>
              </li>
              <li class="flex gap-1" v-if="item.instruction !== ''">
                <h3 class="capitalize text-xs w-fit whitespace-nowrap">
                  {{ $t("label.instruction") }}:
                </h3>
                <p class="text-xs">{{ item.instruction }}</p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 sm:col-6">
    <div class="row">
      <div class="col-12">
        <div class="db-card p-1">
          <ul
            class="flex flex-col gap-2 p-3 border-b border-dashed border-[#EFF0F6]"
          >
            <li class="flex items-center justify-between text-heading">
              <span class="text-sm leading-6 capitalize">{{
                $t("label.subtotal")
              }}</span>
              <span class="text-sm leading-6 capitalize">{{
                order.subtotal_currency_price
              }}</span>
            </li>
            <li class="flex items-center justify-between text-heading">
              <span class="text-sm leading-6 capitalize">{{
                $t("label.discount")
              }}</span>
              <span class="text-sm leading-6 capitalize">{{
                order.discount_currency_price
              }}</span>
            </li>
            <li
              v-if="order.order_type === enums.orderTypeEnum.DELIVERY"
              class="flex items-center justify-between text-heading"
            >
              <span class="text-sm leading-6 capitalize">{{
                $t("label.delivery_charge")
              }}</span>
              <span
                class="text-sm leading-6 capitalize font-semibold text-[#1AB759]"
              >
                {{ order.delivery_charge_currency_price }}
              </span>
            </li>
          </ul>
          <div class="flex items-center justify-between p-3">
            <h4 class="text-sm leading-6 font-bold capitalize">
              {{ $t("label.total") }}
            </h4>
            <h5 class="text-sm leading-6 font-bold capitalize">
              {{ order.total_currency_price }}
            </h5>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="db-card">
          <div class="db-card-header">
            <h3 class="db-card-title">
              {{ $t("label.delivery_information") }}
            </h3>
          </div>
          <div class="db-card-body">
            <div class="flex items-center gap-3 mb-4">
              <img
                class="w-8 rounded-full"
                :src="orderUser.image"
                alt="avatar"
              />
              <h4 class="font-semibold text-sm capitalize text-[#374151]">
                {{ textShortener(orderUser.name, 20) }}
              </h4>
            </div>
            <ul
              class="flex flex-col gap-3 py-4 border-[#EFF0F6]"
              :class="
                order.order_type === enums.orderTypeEnum.DELIVERY
                  ? 'mb-4 border-y'
                  : 'border-t'
              "
            >
              <li class="flex items-center gap-2.5">
                <i class="lab lab-mail lab-font-size-14"></i>
                <span class="text-xs">{{ orderUser.email }}</span>
              </li>
              <li class="flex items-center gap-2.5" v-if="orderUser.phone">
                <i class="lab lab-call-calling-linear lab-font-size-14"></i>
                <span dir="ltr" class="text-xs">{{
                  orderUser.country_code + "" + orderUser.phone
                }}</span>
              </li>
            </ul>
            <div
              v-if="order.order_type === enums.orderTypeEnum.DELIVERY"
              class="flex items-start gap-3"
            >
              <i
                class="lab lab-location lab-font-size-20 leading-6 font-fill-black"
              ></i>
              <span
                class="text-sm w-full max-w-[200px] leading-6 text-[#374151]"
              >
                {{
                  orderAddress.apartment ? orderAddress.apartment + ", " : ""
                }}
                {{ orderAddress.address }}
              </span>
              <PosOrderMapComponent :orderAddress="orderAddress" />
            </div>
            <div
              v-if="order.order_type === enums.orderTypeEnum.Reservations"
              class="flex items-start gap-3"
            >
              <i
                class="lab lab-location lab-font-size-20 leading-6 font-fill-black"
              ></i>
              <span
                class="text-sm w-full max-w-[200px] leading-6 text-[#374151]"
              >
                {{
                  $t("label.Reservations Date") +
                  ":" +
                  order_Reservations.ReservationsDate
                }}
              </span>
            </div>
            <div
              v-if="order.order_type === enums.orderTypeEnum.Reservations"
              class="flex items-start gap-3"
            >
              <i
                class="lab lab-location lab-font-size-20 leading-6 font-fill-black"
              ></i>
              <span
                class="text-sm w-full max-w-[200px] leading-6 text-[#374151]"
              >
                {{
                  $t("label.Reservations Amount") +
                  ":" +
                  order_Reservations.ReservationsAmount
                }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <PosOrderReceiptComponent :order="order" />
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import orderStatusEnum from "../../../enums/modules/orderStatusEnum";
import TableLimitComponent from "../components/TableLimitComponent";
import paymentStatusEnum from "../../../enums/modules/paymentStatusEnum";
import print from "vue3-print-nb";
import PosOrderReceiptComponent from "./PosOrderReceiptComponent";
import posPaymentMethodEnum from "../../../enums/modules/posPaymentMethodEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import PosOrderMapComponent from "./PosOrderMapComponent";

export default {
  name: "PosOrderShowComponent",
  components: {
    TableLimitComponent,
    PaginationSMBox,
    PaginationBox,
    PaginationTextComponent,
    LoadingComponent,
    PosOrderReceiptComponent,
    PosOrderMapComponent,
  },
  directives: {
    print,
  },
  data() {
    return {
      loading: {
        isActive: false,
      },
      printLoading: true,
      printObj: {
        id: "print",
        popTitle: this.$t("menu.order_receipt"),
      },
      enums: {
        orderStatusEnum: orderStatusEnum,
        paymentStatusEnum: paymentStatusEnum,
        posPaymentMethodEnum: posPaymentMethodEnum,
        orderStatusEnumArray: {
          [orderStatusEnum.ACCEPT]: this.$t("label.accept"),
          [orderStatusEnum.PREPARING]: this.$t("label.preparing"),
          [orderStatusEnum.PREPARED]: this.$t("label.prepared"),
          [orderStatusEnum.DELIVERED]: this.$t("label.delivered"),
        },
        paymentStatusEnumArray: {
          [paymentStatusEnum.PAID]: this.$t("label.paid"),
          [paymentStatusEnum.UNPAID]: this.$t("label.unpaid"),
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
        orderTypeEnumArray: {
          [orderTypeEnum.DELIVERY]: this.$t("label.delivery"),
          [orderTypeEnum.TAKEAWAY]: this.$t("label.takeaway"),
          [orderTypeEnum.DINING_TABLE]: this.$t("label.dining_table"),
          [orderTypeEnum.Reservations]: this.$t("label.Reservations"),
        },
        paymentStatusObject: [
          {
            name: this.$t("label.paid"),
            value: paymentStatusEnum.PAID,
          },
          {
            name: this.$t("label.unpaid"),
            value: paymentStatusEnum.UNPAID,
          },
        ],
        orderStatusObject: [
          {
            name: this.$t("label.accept"),
            value: orderStatusEnum.ACCEPT,
          },
          {
            name: this.$t("label.preparing"),
            value: orderStatusEnum.PREPARING,
          },
          {
            name: this.$t("label.prepared"),
            value: orderStatusEnum.PREPARED,
          },
          {
            name: this.$t("label.delivered"),
            value: orderStatusEnum.DELIVERED,
          },
        ],
        orderTypeEnum: orderTypeEnum,
      },
      payment_status: null,
      order_status: null,
      delivery_boy: null,
    };
  },
  mounted() {
    this.loading.isActive = true;
    this.$store.dispatch("deliveryBoy/lists", {
      order_column: "id",
      order_type: "asc",
      status: statusEnum.ACTIVE,
    });
    this.$store
      .dispatch("posOrder/show", this.$route.params.id)
      .then((res) => {
        this.payment_status = res.data.data.payment_status;
        this.order_status = res.data.data.status;
        this.delivery_boy = res.data.data.delivery_boy
          ? res.data.data.delivery_boy.id
          : 0;
        this.loading.isActive = false;
      })
      .catch((error) => {
        this.loading.isActive = false;
      });
  },
  computed: {
    order: function () {
      return this.$store.getters["posOrder/show"];
    },
    orderItems: function () {
      return this.$store.getters["posOrder/orderItems"];
    },
    orderUser: function () {
      return this.$store.getters["posOrder/orderUser"];
    },
    orderAddress: function () {
      return this.$store.getters["posOrder/orderAddress"];
    },
    order_Reservations: function () {
      return this.$store.getters["posOrder/order_Reservations"];
    },
    deliveryBoys: function () {
      return this.$store.getters["deliveryBoy/lists"];
    },
  },
  methods: {
    statusClass: function (status) {
      return appService.statusClass(status);
    },
    orderStatusClass: function (status) {
      return appService.orderStatusClass(status);
    },
    textShortener: function (text, number = 30) {
      return appService.textShortener(text, number);
    },
    orderStatus: function (e) {
      try {
        this.loading.isActive = true;
        this.$store
          .dispatch("posOrder/changeStatus", {
            id: this.$route.params.id,
            status: e.target.value,
          })
          .then((res) => {
            this.loading.isActive = false;
            alertService.successFlip(1, this.$t("label.status"));
          })
          .catch((err) => {
            this.loading.isActive = false;
            alertService.error(err.response.data.message);
          });
      } catch (err) {
        this.loading.isActive = false;
        alertService.error(err.response.data.message);
      }
    },
    changePaymentStatus: function (e) {
      try {
        this.loading.isActive = true;
        this.$store
          .dispatch("posOrder/changePaymentStatus", {
            id: this.$route.params.id,
            payment_status: e.target.value,
          })
          .then((res) => {
            this.loading.isActive = false;
            alertService.successFlip(1, this.$t("label.payment_status"));
          })
          .catch((err) => {
            this.loading.isActive = false;
            alertService.error(err.response.data.message);
          });
      } catch (err) {
        this.loading.isActive = false;
        alertService.error(err.response.data.message);
      }
    },
    selectDeliveryBoy: function (e) {
      try {
        this.loading.isActive = true;
        this.$store
          .dispatch("posOrder/selectDeliveryBoy", {
            id: this.$route.params.id,
            delivery_boy_id: e.target.value,
          })
          .then((res) => {
            this.loading.isActive = false;
            alertService.successInfo(1, this.$t("message.delivery_boy_add"));
          })
          .catch((err) => {
            this.loading.isActive = false;
            alertService.error(err.response.data.message);
          });
      } catch (err) {
        this.loading.isActive = false;
        alertService.error(err.response.data.message);
      }
    },
  },
};
</script>

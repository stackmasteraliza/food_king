<template>
  <div id="receiptModal" class="modal">
    <div
      class="modal-dialog max-w-[340px] rounded-none"
      id="print"
      :dir="direction"
    >
      <div class="modal-header hidden-print">
        <button
          type="button"
          @click="reset"
          class="modal-close flex items-center justify-center gap-1.5 py-2 px-4 rounded bg-[#FB4E4E]"
        >
          <i class="lab lab-back-bold lab-font-size-16 text-white"></i>
          <span class="text-xs leading-5 capitalize text-white">{{
            $t("button.close")
          }}</span>
        </button>
        <button
          type="button"
          v-print="printObj"
          class="flex items-center justify-center gap-1.5 py-2 px-4 rounded bg-[#1AB759]"
        >
          <i class="lab lab-print-bold lab-font-size-16 text-white"></i>
          <span class="text-xs leading-5 capitalize text-white">{{
            $t("button.print_invoice")
          }}</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center pb-3.5 border-b border-dashed border-gray-400">
          <h3 class="text-2xl font-bold mb-1">فاتورة ضريبية</h3>
        </div>
        <div class="order-qr-code flex justify-center py-4">
          <img class="w-32" :src="setting.theme_logo" alt="logo" />
        </div>
        <div class="text-center pb-3.5 border-b border-dashed border-gray-400">
          <h3 class="text-2xl font-bold mb-1">{{ company.company_name }}</h3>
          <h4 class="text-sm font-normal">{{ branch.address }}</h4>
          <h5 class="text-sm font-normal">Tel: {{ branch.phone }}</h5>
        </div>
        <div
          class="py-2 capitalize text-xl font-bold text-center border-b border-dashed border-gray-400"
        >
          <h3>
            {{ $t("label.order_type") }}:
            {{ enums.orderTypeEnumArray[order.order_type] }}
          </h3>
          <h4>
            <div v-if="order.order_type === orderTypeEnum.DINING_TABLE">
              {{ $t("رقم الطاولة") }}: {{ order.table_name }}
            </div>
            <div v-else-if="order.order_type === orderTypeEnum.DELIVERY">
              {{ $t("عنوان التوصيل") }}: {{ order.order_address }}
            </div>
            <div v-else-if="order.order_type === orderTypeEnum.Reservations">
              {{ $t("تاريخ الحجز") }}:
              {{ order.order_Reservations.ReservationsDate }}
              <div>
                {{ $t("مبلغ التأمين") }}:
                {{ order.order_Reservations.ReservationsAmount }}
              </div>
            </div>
          </h4>
        </div>
        <table class="w-full my-1.5">
          <tbody>
            <tr>
              <td class="text-xs text-left py-0.5 text-heading">
                {{ $t("button.order") }} #{{ order.order_serial_no }}
              </td>
              <td class="text-xs text-left py-0.5 text-heading">
                {{ order.order_date }} : {{ order.order_time }}
              </td>
            </tr>
          </tbody>
        </table>

        <table class="w-full">
          <thead class="border-t border-b border-dashed border-gray-400">
            <tr>
              <th
                scope="col"
                class="py-1 font-normal text-xs capitalize text-left text-heading w-8"
              >
                {{ $t("label.qty") }}
              </th>
              <th
                scope="col"
                class="py-1 font-normal text-xs capitalize flex items-center justify-between text-heading"
              >
                <span>{{ $t("label.item_description") }}</span>
                <span>{{ $t("label.price") }}</span>
              </th>
            </tr>
          </thead>

          <tbody class="border-b border-dashed border-gray-400">
            <tr
              v-if="orderItems.length > 0"
              v-for="item in orderItems"
              :key="item"
            >
              <td class="text-left font-normal align-top py-1">
                <p class="text-xs leading-5 text-heading">
                  {{ item.quantity }}
                </p>
              </td>
              <td class="text-left font-normal align-top py-1">
                <div class="flex items-center justify-between">
                  <h4 class="text-sm font-normal capitalize">
                    {{ item.item_name }}
                  </h4>
                  <p
                    style="font-family: 'saudi_riyal'"
                    class="text-xs leading-5 text-heading"
                  >
                    {{ item.total_without_tax_currency_price }}
                    &#xE900;
                  </p>
                </div>
                <p
                  v-if="Object.keys(item.item_variations).length !== 0"
                  class="text-xs leading-5 font-normal text-heading max-w-[200px]"
                >
                  <span v-for="(variation, index) in item.item_variations">
                    {{ variation.variation_name }}: {{ variation.name }}
                    <span
                      v-if="
                        index + 1 < Object.keys(item.item_variations).length
                      "
                      >,
                    </span>
                  </span>
                </p>
                <p
                  v-if="item.item_extras.length > 0"
                  class="text-xs leading-5 font-normal text-heading max-w-[200px]"
                >
                  {{ $t("label.extras") }}:
                  <span v-for="(extra, index) in item.item_extras">
                    {{ extra.name }}
                    <span v-if="index + 1 < item.item_extras.length">, </span>
                  </span>
                </p>
                <p
                  v-if="item.instruction"
                  class="text-xs leading-5 font-normal text-heading max-w-[200px]"
                >
                  {{ $t("label.instruction") }}: {{ item.instruction }}
                </p>

                <div
                  class="flex items-center justify-between"
                  v-if="item.tax_rate > 0"
                >
                  <p class="text-xs leading-5 font-normal text-heading">
                    {{ item.tax_name }} ({{ item.tax_currency_rate }}
                    {{ item.tax_type }})
                  </p>
                  <p
                    style="font-family: 'saudi_riyal'"
                    class="text-xs leading-5 font-normal text-heading"
                  >
                    {{ item.tax_currency_amount }}
                    &#xE900;
                  </p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="py-2 pl-7">
          <table class="w-full">
            <tbody>
              <tr>
                <td class="text-xs text-left py-0.5 uppercase text-heading">
                  {{ $t("label.subtotal") }}:
                </td>
                <td
                  style="font-family: 'saudi_riyal'"
                  class="text-xs text-right py-0.5 text-heading"
                >
                  {{ order.subtotal_without_tax_currency_price }}
                  &#xE900;
                </td>
              </tr>
              <tr>
                <td class="text-xs text-left py-0.5 uppercase text-heading">
                  {{ $t("label.total_tax") }}:
                </td>
                <td
                  style="font-family: 'saudi_riyal'"
                  class="text-xs text-right py-0.5 text-heading"
                >
                  {{ order.total_tax_currency_price }}
                  &#xE900;
                </td>
              </tr>
              <tr>
                <td class="text-xs text-left py-0.5 uppercase text-heading">
                  {{ $t("label.discount") }}:
                </td>
                <td
                  style="font-family: 'saudi_riyal'"
                  class="text-xs text-right py-0.5 text-heading"
                >
                  {{ order.discount_currency_price }}
                  &#xE900;
                </td>
              </tr>
              <tr v-if="order.order_type === orderTypeEnum.DELIVERY">
                <td class="text-xs text-left py-0.5 uppercase text-heading">
                  {{ $t("label.delivery_charge") }}:
                </td>
                <td
                  style="font-family: 'saudi_riyal'"
                  class="text-xs text-right py-0.5 text-heading"
                >
                  {{ order.delivery_charge_currency_price }}
                  &#xE900;
                </td>
              </tr>

              <tr>
                <td
                  class="text-xs text-left py-0.5 font-bold uppercase text-heading"
                >
                  {{ $t("label.total") }}:
                </td>
                <td
                  style="font-family: 'saudi_riyal'"
                  class="text-xs text-right py-0.5 font-bold text-heading"
                >
                  {{ order.total_currency_price }}
                  &#xE900;
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div
          class="text-xs py-2 border-t border-b border-dashed border-gray-400 text-heading"
        >
          <table class="w-full">
            <tbody>
              <tr>
                <td class="pt-1 pb-1 pr-1 align-top text-start">
                  {{ $t("label.payment_type") }}:
                  {{ posPaymentMethodEnumArray[order.pos_payment_method] }}
                </td>
                <td
                  class="pt-1 pb-1 text-end"
                  v-if="order.cash_back_amount > 0"
                >
                  <div style="font-family: 'saudi_riyal'">
                    {{ $t("label.cash") }}:
                    {{ order.pos_received_currency_amount }}
                    &#xE900;
                  </div>
                  <span style="font-family: 'saudi_riyal'"
                    >{{ $t("label.change") }} :
                    {{ order.cash_back_currency_amount }}
                    &#xE900;
                  </span>
                </td>
                <td
                  class="pt-1 pb-1 text-end"
                  v-if="order.pos_payment_method === posPaymentMethodEnum.CARD"
                >
                  <div style="font-family: 'saudi_riyal'">
                    {{ $t("label.cash") }}:
                    {{ order.pos_received_currency_amount }}
                    &#xE900;
                  </div>
                  <span style="font-family: 'saudi_riyal'"
                    >{{ $t("مبلغ الشبكه") }} :
                    {{ order.NetworkAmount }}
                    &#xE900;
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <h4
          v-if="order.token"
          class="py-2 capitalize text-xl font-bold text-center border-b border-dashed border-gray-400"
        >
          {{ $t("label.token") }} #{{ order.token }}
        </h4>

        <div class="order-qr-code flex justify-center py-4">
          <img style="width: 100px" :src="order.QrCode" alt="QR Code" />
        </div>

        <div class="text-center pt-2 pb-4">
          <p class="text-[11px] leading-[14px] capitalize text-heading">
            {{ $t("message.thank_you") }}
          </p>
          <p class="text-[11px] leading-[14px] capitalize text-heading">
            {{ $t("message.please_come_again") }}
          </p>
        </div>
        <div class="flex flex-col items-end">
          <h5 class="text-[8px] font-normal text-left w-[46px] leading-[10px]">
            {{ $t("label.powered_by") }}
          </h5>
          <h6 class="text-xs font-normal leading-4">
            {{ company.company_name }}
          </h6>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import print from "vue3-print-nb";
import appService from "../../../services/appService";
import displayModeEnum from "../../../enums/modules/displayModeEnum";
import posPaymentMethodEnum from "../../../enums/modules/posPaymentMethodEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";

export default {
  name: "ReceiptComponent",
  props: {
    order: Object,
  },
  data() {
    return {
      printObj: {
        id: "print",
        popTitle: this.$t("menu.order_receipt"),
      },
      posPaymentMethodEnum: posPaymentMethodEnum,
      posPaymentMethodEnumArray: {
        [posPaymentMethodEnum.CASH]: this.$t("label.cash"),
        [posPaymentMethodEnum.CARD]: this.$t("label.card"),
        [posPaymentMethodEnum.MOBILE_BANKING]: this.$t("label.mobile_banking"),
        [posPaymentMethodEnum.OTHER]: this.$t("label.other"),
        [posPaymentMethodEnum.Mada]: this.$t("مدى"),
        [posPaymentMethodEnum.Viza]: this.$t("فيزا"),
      },
      orderTypeEnum: orderTypeEnum,
      enums: {
        orderTypeEnumArray: {
          [orderTypeEnum.DELIVERY]: this.$t("label.delivery"),
          [orderTypeEnum.TAKEAWAY]: this.$t("سفري"),
          [orderTypeEnum.DINING_TABLE]: this.$t("محلي"),
          [orderTypeEnum.Reservations]: this.$t("حجوزات"),
        },
      },
      base64String: "",
      decodedResult: null,
      isImage: false,
    };
  },
  computed: {
    company: function () {
      return this.$store.getters["company/lists"];
    },
    branch: function () {
      return this.$store.getters["backendGlobalState/branchShow"];
    },
    orderItems: function () {
      return this.$store.getters["posOrder/orderItems"];
    },
    setting: function () {
      return this.$store.getters["frontendSetting/lists"];
    },
    direction: function () {
      return this.$store.getters["frontendLanguage/show"].display_mode ===
        displayModeEnum.RTL
        ? "rtl"
        : "ltr";
    },
  },
  mounted() {
    this.$store.dispatch("company/lists").then().catch();
    this.$store
      .dispatch("frontendSetting/lists")
      .then((res) => {})
      .catch((err) => {});
  },
  methods: {
    reset: function () {
      appService.modalHide();
    },

    decodeBase64(QrCodeData) {
      try {
        if (!QrCodeData) {
          return null;
        }
        // Equivalent to your PHP function

        this.base64String = QrCodeData;
        const stringWithoutHeader = this.base64String
          .replace(/^data:image\/\w+;base64,/, "")
          .replace(/\s/g, "+");

        this.decodedResult = this.base64ToArrayBuffer(stringWithoutHeader);

        this.isImage = this.base64String.startsWith("data:image");
        return this.decodedResult;
      } catch (error) {
        console.error("Decoding failed:", error);
        return null;
      }
    },

    base64ToArrayBuffer(base64) {
      const binaryString = atob(base64);
      const bytes = new Uint8Array(binaryString.length);
      for (let i = 0; i < binaryString.length; i++) {
        bytes[i] = binaryString.charCodeAt(i);
      }
      return bytes.buffer;
    },
  },
  directives: {
    print,
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

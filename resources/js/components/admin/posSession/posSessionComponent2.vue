<template>
  <div class="row">
    <div class="col-12">
      <BreadcrumbComponent />
    </div>
    <LoadingComponent :props="loading" />
    <div
      class="fixed inset-0 bg-opacity-50 z-[9999] flex items-center justify-center p-4"
      @click.self="closeModal"
    >
      <div
        style="width: 50%"
        class="max-w-md bg-white rounded-2xl shadow-xl overflow-hidden"
      >
        <!-- Modal Header -->
        <div
          class="modal-header flex justify-between items-center p-4 border-b border-[#D9DBE9]"
        >
          <h3
            class="capitalize mb-6 text-center text-[22px] font-semibold leading-[34px] text-heading"
          >
            {{ $t("label.session Open") }}
          </h3>
          <button
            @click="closeModal"
            class="text-gray-400 hover:text-gray-600 transition"
          >
            <i class="lab lab-close-circle-line text-2xl"></i>
          </button>
        </div>
        <div class="modal-body p-4">
          <div
            class="container max-w-[360px] py-6 p-4 mb-6 sm:px-6 shadow-xs rounded-2xl bg-white"
          >
            <h2
              class="capitalize mb-6 text-center text-[22px] font-semibold leading-[34px] text-heading"
            >
              تفاصيل نقطة البيع
            </h2>

            <div
              v-if="errors.validation"
              class="bg-red-100 border border-red-400 text-red-700 px-3 py-3 mb-5 rounded relative flex items-start gap-2"
              role="alert"
            >
              <span class="block sm:inline text-sm flex-auto">{{
                errors.validation
              }}</span>
              <button type="button" @click="close" class="leading-none">
                <i class="lab lab-close-circle-line"></i>
              </button>
            </div>
            <div class="form-row">
              <div class="cform-col-12 sm:form-col-6">
                <label for="shift_id" class="db-field-title after:hidden">{{
                  $t("label.select_shift")
                }}</label>

                <select
                  v-model="Props.form.shift_id"
                  required
                  class="w-full mb-3 border rounded px-2 py-1"
                >
                  <option disabled value="">
                    {{ $t("label.select_shift") }}
                  </option>
                  <option
                    v-for="shift in shifts"
                    :key="shift.id"
                    :value="shift.id"
                  >
                    {{ shift.name }}
                  </option>
                </select>
              </div>

              <div class="form-col-12 sm:form-col-6">
                <label for="device_id" class="db-field-title after:hidden">{{
                  $t("label.search_device")
                }}</label>
                <select
                  v-model="Props.form.device_id"
                  required
                  class="w-full mb-3 border rounded px-2 py-1"
                  @change="updateSessionNumber"
                >
                  <option disabled value="">
                    {{ $t("label.select_device") }}
                  </option>
                  <option
                    v-for="device in devices"
                    :key="device.id"
                    :value="device.id"
                  >
                    {{ device.name }}
                  </option>
                </select>
              </div>

              <div class="form-col-12 sm:form-col-6">
                <div class="panel-heading bg-light text-dark-blue">
                  {{ $t("تفاصيل الجلسة") }}
                </div>
                <div class="panel-body p-0">
                  <div class="row no-margin">
                    <div class="col-xs-12 p-0">
                      <div>
                        <span class="m m-b-sm m-t-sm block"
                          ><strong>رقم الجلسة</strong></span
                        >
                      </div>
                      <div class="b-t b-b">
                        <span class="m m-b-sm m-t-sm block">{{
                          Props.form.sessionNumber
                        }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <button
                type="submit"
                @click="opensession"
                class="w-full h-12 text-center capitalize font-medium rounded-3xl mb-6 text-white bg-primary"
              >
                {{ $t("button.open session") }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import { routes } from "../../../router";
import router from "../../../router";
import "@vuepic/vue-datepicker/dist/main.css";
import BreadcrumbComponent from "../components/BreadcrumbComponent";

export default {
  name: "posSessionComponent2",
  components: {
    LoadingComponent,
    BreadcrumbComponent,
  },
  data() {
    return {
      loading: {
        isActive: false,
      },
      Props: {
        form: {
          shift_id: 0,
          device_id: 0,
          sessionNumber: 0,
        },
      },
      errors: {},
    };
  },
  mounted() {
    this.list();
    this.listDevice();
    // Close modal on ESC key
    document.addEventListener("keydown", this.handleEscape);
  },
  beforeUnmount() {
    document.removeEventListener("keydown", this.handleEscape);
  },
  computed: {
    shifts: function () {
      return this.$store.getters["shift/lists"];
    },
    devices: function () {
      return this.$store.getters["posdevice/lists"];
    },
  },
  methods: {
    listDevice: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("PosDevice/lists", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    list: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("Shift/lists", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    // Handle ESC key press
    handleEscape(e) {
      if (e.key === "Escape") this.closeModal();
    },

    // Close modal and go back in router history
    closeModal() {
      this.$router.go(-1);
    },
    updateSessionNumber() {
      this.loading.isActive = true;
      this.$store
        .dispatch("posShift/generatesession", {
          deviceid: this.Props.form.device_id,
        })
        .then((response) => {
          this.Props.form.sessionNumber = response.data.session_number;
        })
        .catch((err) => {
          this.loading.isActive = false;
          this.errors = err.response.data.errors;
        });
    },
    opensession: function () {
      try {
        this.loading.isActive = true;

        this.$store
          .dispatch("posShift/openSession", this.Props)
          .then((res) => {
            this.closeModal(); // Close modal on success
            this.loading.isActive = false;
            alertService.success(res.data.sessionnumber);
            if (res.data.sessionnumber !== null) {
              this.Props.form = {
                shift_id: 0,
                device_id: 0,
                sessionNumber: 0,
              };
              router.push({ name: "admin.pos" });

              setTimeout(() => {
                appService.recursiveRouter(routes, this.permission);
              }, 300);
            }
          })
          .catch((err) => {
            this.loading.isActive = false;
            this.errors = err.response.data.errors;
          });
      } catch (err) {
        this.loading.isActive = false;
      }
    },
    close: function () {
      this.errors = {};
    },
  },
};
</script>

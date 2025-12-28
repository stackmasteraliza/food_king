<template>
  <div class="row" style="background-color: rgb(249 249 255)">
    <div class="col-12">
      <BreadcrumbComponent />
    </div>
    <LoadingComponent :props="loading" />
    <div
      class="p-6 bg-white min-h-screen"
      style="background-color: rgb(249 249 255)"
    >
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">جميع التكاملات</h2>
        <div class="flex gap-2">
          <input
            v-model="props.search.name"
            placeholder="ابحث..."
            class="input"
          />
          <button class="border px-3 py-1 rounded text-sm" @click="search()">
            تصفية
          </button>
        </div>
      </div>

      <!-- قائمة التطبيقات -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
          v-for="app in filteredApps"
          :key="app.id"
          class="bg-white p-4 rounded-lg shadow flex flex-col justify-between"
          style="background-color: white"
        >
          <div>
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-2">
                <img :src="app.icon_url" v-if="app.icon_url" class="h-8 w-8" />
              </div>
              <a href="#" class="text-gray-400 hover:text-black">
                <i class="fas fa-external-link-alt"></i>
              </a>
            </div>
            <h3 class="text-lg font-semibold">{{ app.name }}</h3>
            <p class="text-sm text-gray-600 mb-2">{{ app.description }}</p>
          </div>

          <div class="mt-4 flex items-center justify-between">
            <div class="flex gap-2">
              <button
                class="border text-sm px-3 py-1 rounded hover:bg-gray-100"
                @click="openConfigModal(app)"
              >
                إعداد
              </button>
              <!-- <button
                class="border border-red-500 text-red-500 text-sm px-3 py-1 rounded hover:bg-red-50"
                @click="remove(app)"
              >
                إزالة
              </button> -->
            </div>
            <div class="custom-switch">
              <input
                @change="updateStatus(app)"
                :id="app.id"
                type="checkbox"
                :name="app.name"
                :checked="app.active === 5"
              />
              <label v-if="app.active === 5" :for="app.id">{{
                $t("label.active")
              }}</label>
              <label v-else :for="app.id">{{ $t("label.unactive") }}</label>
            </div>
          </div>
        </div>
      </div>

      <!-- نافذة الإعداد -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      >
        <div class="bg-white p-6 w-full max-w-md rounded-lg shadow">
          <h2 class="text-xl font-bold mb-4">إعداد {{ selectedApp?.name }}</h2>
          <form @submit.prevent="submitConfig">
            <div class="mb-2">
              <label class="block text-sm font-medium">API URL</label>
              <input v-model="config.api_url" class="input w-full" />
            </div>
            <div class="mb-2">
              <label class="block text-sm font-medium">Secret Key</label>
              <input v-model="config.secret_key" class="input w-full" />
            </div>
            <div class="mb-2">
              <label class="block text-sm font-medium">Client ID</label>
              <input v-model="config.client_id" class="input w-full" />
            </div>
            <div class="mb-2">
              <label class="block text-sm font-medium">API Key</label>
              <input v-model="config.api_key" class="input w-full" />
            </div>
            <div class="mb-2">
              <label class="block text-sm font-medium">Username</label>
              <input v-model="config.username" class="input w-full" />
            </div>
            <div class="mb-2">
              <label class="block text-sm font-medium">Password</label>
              <input
                v-model="config.password"
                type="password"
                class="input w-full"
              />
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium"
                >Authorization Method</label
              >
              <select v-model="config.auth_method" class="input w-full">
                <option value="token">Token</option>
                <option value="basic">Basic</option>
                <option value="oauth2">OAuth2</option>
              </select>
            </div>
            <div class="flex justify-between">
              <button
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded"
              >
                حفظ
              </button>
              <button
                type="button"
                class="px-3 py-2 rounded border"
                @click="closeModal"
              >
                إغلاق
              </button>
            </div>
          </form>
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
import TableLimitComponent from "../components/TableLimitComponent";
import FilterComponent from "../components/buttons/collapse/FilterComponent";
import ExportComponent from "../components/buttons/export/ExportComponent";
import PrintComponent from "../components/buttons/export/PrintComponent";
import ExcelComponent from "../components/buttons/export/ExcelComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import {
  endOfMonth,
  endOfYear,
  startOfMonth,
  startOfYear,
  subMonths,
} from "date-fns";
import BreadcrumbComponent from "../components/BreadcrumbComponent";
import statusEnum from "../../../enums/modules/statusEnum";
import displayModeEnum from "../../../enums/modules/displayModeEnum";

import { ref, onMounted } from "vue";
import axios from "axios";

export default {
  name: "AppMarketplaceComponent",
  components: {
    TableLimitComponent,
    PaginationSMBox,
    PaginationBox,
    PaginationTextComponent,
    LoadingComponent,
    FilterComponent,
    ExportComponent,
    PrintComponent,
    ExcelComponent,
    Datepicker,
    BreadcrumbComponent,
  },
  setup() {
    const apps = ref([]);

    // const apps = ref([]);

    const config = ref({
      api_url: "",
      secret_key: "",
      api_key: "",
      client_id: "",
      username: "",
      password: "",
      auth_method: "token",
    });

    // const openConfigModal = (app) => {
    //   selectedApp.value = app;

    //   const userIntegration = app.userIntegration;
    //   if (userIntegration) {
    //     config.value = {
    //       api_url: userIntegration.api_url || "",
    //       secret_key: userIntegration.secret_key || "",
    //       api_key: userIntegration.api_key || "",
    //       client_id: userIntegration.client_id || "",
    //       username: userIntegration.username || "",
    //       password: userIntegration.password || "",
    //       auth_method: userIntegration.auth_method || "token",
    //     };
    //   } else {
    //     config.value = {
    //       api_url: "",
    //       secret_key: "",
    //       api_key: "",
    //       client_id: "",
    //       username: "",
    //       password: "",
    //       auth_method: "token",
    //     };
    //   }

    //   showModal.value = true;
    // };

    // const closeModal = () => {
    //   showModal.value = false;
    //   selectedApp.value = null;
    // };

    // const submitConfig = async () => {
    //   if (loading.value) return;
    //   loading.value = true;
    //   try {
    //     await axios.post("admin/integrations/integrationAPI", {
    //       integration_id: selectedApp.value.id,
    //       ...config.value,
    //     });
    //     alert("تم حفظ الإعدادات بنجاح");
    //     selectedApp.value = null;
    //     closeModal();
    //   } catch (error) {
    //     alert("فشل حفظ الإعدادات");
    //   } finally {
    //     loading.value = false;
    //   }
    // };

    // onMounted(loadApps);

    const date = ref();
    const selectedApp = ref(null);

    const showModal = ref(false);
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
      // ...
      apps,
      config,
      date,
      presetRanges,
      selectedApp,
      showModal,
      // ...
    };
  },

  data() {
    return {
      loading: {
        isActive: false,
      },
      enums: {},
      printLoading: true,
      printObj: {
        id: "print",
        popTitle: this.$t("menu.PublicMarketplace"),
      },
      props: {
        form: {
          name: "",
          icon_url: "",
          description: "",
          price: 0,
          trial_days: 14,
          features: "",
          active: true,
        },
        search: {
          paginate: 1,
          page: 1,
          per_page: 10,
          order_column: "id",
          order_type: "desc",
          name: "",
          icon_url: "",
          description: "",
          price: "",
          trial_days: "",
          features: "",
          active: null,
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
    filteredApps: function () {
      return this.$store.getters["integrations/listsWithAPISetting"];
    },
    pagination: function () {
      return this.$store.getters["integrations/pagination"];
    },
    paginationPage: function () {
      return this.$store.getters["integrations/page"];
    },
    direction: function () {
      return this.$store.getters["frontendLanguage/show"].display_mode ===
        displayModeEnum.RTL
        ? "rtl"
        : "ltr";
    },
  },
  methods: {
    openConfigModal(app) {
      this.selectedApp.value = app;
      alert("فشل حفظ الإعدادات");
      const userIntegration = app.userIntegration;
      if (userIntegration) {
        this.config.value = {
          api_url: userIntegration.api_url || "",
          secret_key: userIntegration.secret_key || "",
          api_key: userIntegration.api_key || "",
          client_id: userIntegration.client_id || "",
          username: userIntegration.username || "",
          password: userIntegration.password || "",
          auth_method: userIntegration.auth_method || "token",
        };
      } else {
        this.config.value = {
          api_url: "",
          secret_key: "",
          api_key: "",
          client_id: "",
          username: "",
          password: "",
          auth_method: "token",
        };
      }
      alert("فشل حفظ 2");
      this.showModal = true;
    },

    closeModal() {
      this.showModal = false;
      this.selectedApp.value = null;
    },

    submitConfig() {
      this.loading.isActive = true;
      try {
        axios.post("admin/integrations/integrationAPI", {
          integration_id: selectedApp.value.id,
          ...this.config.value,
        });
        alert("تم حفظ الإعدادات بنجاح");
        this.selectedApp.value = null;
        this.closeModal();
      } catch (error) {
        alert("فشل حفظ الإعدادات");
      } finally {
        this.loading.isActive = false;
      }
    },
    async loadApps() {
      try {
        const { data } = await axios.get(
          "admin/setting/integrations/listsWithAPISetting"
        );
        this.apps.value = data;
      } catch (e) {
        alert("فشل تحميل التطبيقات");
      }
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
    updateStatus(app) {
      appService
        .updateConfirmation()
        .then((res) => {
          const newStatus = app.active === statusEnum.ACTIVE ? false : true;
          this.loading.isActive = true;
          this.$store.dispatch("integrations/edit", app.id);
          this.loading.isActive = false;
          this.props.errors = {};
          this.props.form = {
            name: app.name,
            price: app.price,
            active: newStatus,
          };
          try {
            const fd = new FormData();
            fd.append("name", app.name);
            fd.append("price", app.price);
            fd.append("active", newStatus);
            const tempId = this.$store.getters["integrations/temp"].temp_id;
            this.loading.isActive = true;
            this.$store
              .dispatch("integrations/save", {
                form: fd,
                search: this.props.search,
              })
              .then((res) => {
                this.loading.isActive = false;
                alertService.success(this.$t("message.update_success"));

                this.props.form = {
                  name: "",
                  icon_url: "",
                  description: "",
                  price: 0,
                  trial_days: 14,
                  features: "",
                  active: true,
                };

                this.errors = {};
                this.list();
              })
              .catch((err) => {
                this.loading.isActive = false;
                this.errors = {};
                if (
                  err.response &&
                  err.response.data &&
                  err.response.data.errors
                ) {
                  this.errors = err.response.data.errors;
                } else {
                  alertService.error(err.response.data.message);
                }
              });
          } catch (err) {
            this.loading.isActive = false;
            alertService.error(err);
          }
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
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
      this.props.form = {
        name: "",
        icon_url: "",
        description: "",
        price: 0,
        trial_days: 14,
        features: "",
        active: true,
      };
      this.list();
    },
    list: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("integrations/listsWithAPISetting", this.props.search)
        .then((res) => {
          this.apps.value = res.data.data;
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
  },
};
</script>

<style scoped>
.input {
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 0.375rem;
}
</style>

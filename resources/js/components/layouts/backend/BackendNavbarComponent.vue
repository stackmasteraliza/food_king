<template>
  <div class="backdrop"></div>
  <header class="db-header">
    <router-link
      class="w-32 flex-shrink-0"
      :to="{ name: 'frontend.home' }"
      @click="closeFullScreen"
    >
      <img class="w-full" :src="setting.theme_logo" alt="logo" />
    </router-link>
    <!-- <div class="w-full">
      <button
        id="closeSettion"
        @click="login_()"
        class="flex items-center gap-3 w-fit pl-3 pr-4 py-1.5 rounded-3xl transition hover:shadow-filter hover:bg-black bg-[#e22e2e]"
        style="background: black; color: white"
      >
        <div v-if="!logged">
          <span class="text-sm leading-6 capitalize">{{
            $t("button.open session")
          }}</span>
        </div>
        <div v-if="logged">
          <span class="text-sm leading-6 capitalize">{{
            $t("button.Close session")
          }}</span>
        </div>
      </button>
    </div> -->
    <div class="flex items-center justify-end w-full gap-4">
      <div
        class="sub-header flex items-center gap-4 transition xh:justify-between xh:fixed xh:left-0 xh:w-full xh:p-4 xh:border-y xh:border-[#EFF0F6] xh:bg-white"
      >
        <button
          v-if="$route.path.includes('order-status-screen')"
          type="button"
          @click="fullScreen"
          class="hidden db-header-toggle lg:flex items-center justify-center w-9 h-9 px-3 rounded-lg bg-[#E0FFED]"
        >
          <i class="lab lab-maximize lab-font-size-24 text-[#1AB759]"></i>
        </button>

        <div v-if="authBranch === 0" class="relative dropdown-group">
          <button class="flex items-center text-left gap-2 dropdown-btn">
            <i class="lab lab-shop lab-font-size-24 font-fill-primary"></i>
            <h3 class="capitalize text-xs font-medium text-heading">
              <span class="block font-normal mb-0.5">{{
                $t("label.branch")
              }}</span>
              <b class="font-semibold whitespace-nowrap">{{ branch.name }}</b>
            </h3>
            <i class="lab lab-arrow-down text-xs ml-1.5 lab-font-size-14"></i>
          </button>
          <ul
            v-if="branches.length > 0"
            class="p-2 w-fit rounded-lg shadow-xl absolute top-14 left-0 z-10 border border-gray-200 bg-white dropdown-list transition-all duration-300 scale-y-0 origin-top"
          >
            <li
              v-for="branch in branches"
              class="flex items-center gap-2 w-full px-2.5 rounded-md transition hover:bg-gray-100"
            >
              <input
                @click="changeBranch(branch.id)"
                v-model="defaultBranch"
                type="radio"
                :id="'branch_id_' + branch.id"
                :value="branch.id"
                name="branch"
                class="w-3 cursor-pointer mb-[1px] accent-primary"
              />
              <label
                :for="'branch_id_' + branch.id"
                class="capitalize leading-8 text-sm min-w-[150px] cursor-pointer text-heading"
              >
                {{ branch.name }}
              </label>
            </li>
          </ul>
        </div>
        <div class="flex items-center gap-4">
          <div
            class="relative dropdown-group"
            v-if="
              $route.path.includes('kitchen-display-system') ||
              $route.path.includes('order-status-screen')
            "
          >
            <router-link
              :to="{ path: '/admin/' + defaultMenu?.url }"
              @click="closeFullScreen"
              class="flex items-center gap-2 h-9 px-3 rounded-lg bg-[#FFEDF4]"
            >
              <i
                class="lab-font-size-17 text-primary"
                :class="defaultMenu?.icon"
              ></i>
              <span
                class="md:block hidden whitespace-nowrap text-xs font-medium capitalize text-heading"
                >{{ $t("menu." + defaultMenu?.language) }}</span
              >
            </router-link>
          </div>
          <div
            class="flex items-center justify-between md:justify-center gap-4"
          >
            <div
              v-if="setting.site_language_switch === enums.activityEnum.ENABLE"
              class="dropdown-group relative"
            >
              <button
                class="dropdown-btn flex items-center gap-2 h-9 px-3 rounded-lg bg-[#FFEDF4]"
              >
                <img
                  :src="language.image"
                  alt="flag"
                  class="w-4 h-4 rounded-full"
                />
                <span
                  class="hidden md:block whitespace-nowrap text-xs font-medium capitalize text-heading"
                >
                  {{ language.name }}
                </span>
              </button>
              <ul
                v-if="languages.length > 0"
                class="p-2 min-w-[180px] rounded-lg shadow-xl absolute top-14 ltr:right-0 rtl:left-0 z-10 border border-gray-200 bg-white transition-all duration-300 origin-top scale-y-0 dropdown-list"
              >
                <li
                  @click="changeLanguage(language.id, language.code)"
                  v-for="language in languages"
                  class="flex items-center gap-2 py-1.5 px-2.5 rounded-md cursor-pointer hover:bg-gray-100"
                >
                  <img
                    :src="language.image"
                    alt="flag"
                    class="w-4 h-4 rounded-full"
                  />
                  <span class="text-heading capitalize text-sm">{{
                    language.name
                  }}</span>
                </li>
              </ul>
            </div>

            <router-link
              v-if="
                pos.permission &&
                !$route.path.includes('kitchen-display-system') &&
                !$route.path.includes('order-status-screen')
              "
              class="w-9 h-9 rounded-lg flex items-center justify-center bg-[#FFEBD8]"
              :to="{ path: '/admin/' + pos.url }"
            >
              <i class="lab lab-pos-bold lab-font-size-16 font-fill-pos"></i>
            </router-link>
          </div>
        </div>
      </div>
      <button
        @click.prevent="handleSidebar"
        v-if="
          !$route.path.includes('kitchen-display-system') &&
          !$route.path.includes('order-status-screen')
        "
        class="fa-solid db-header-nav w-9 h-9 rounded-lg text-primary bg-primary/5"
        :class="sidebar ? 'fa-align-left' : 'fa-bars'"
      ></button>

      <div class="dropdown-group">
        <button class="dropdown-btn flex items-center gap-2">
          <img
            class="flex-shrink-0 w-9 h-9 object-cover rounded-lg"
            :src="authInfo.image"
            alt="avatar"
          />
          <h3
            class="whitespace-nowrap text-sm capitalize text-left leading-[17px]"
          >
            {{ $t("label.hello") }}
            <b class="block font-semibold">{{
              textShortener(authInfo.name, 15)
            }}</b>
          </h3>
          <i class="lab lab-arrow-down text-xs ml-1.5 lab-font-size-14"></i>
        </button>
        <div
          class="dropdown-list fixed sm:absolute top-[75px] sm:top-12 ltr:right-0 rtl:left-0 z-[60] rounded-xl w-full h-[calc(100vh_-_75px)] overflow-y-auto sm:h-auto sm:w-[360px] p-4 shadow-paper bg-white transition-all duration-300 scale-y-0 origin-top"
        >
          <div class="w-fit mx-auto text-center mb-5">
            <figure
              class="relative z-10 w-[98px] h-[98px] border-2 border-dashed rounded-full inline-flex items-center justify-center border-white bg-gradient-to-t from-[#FF7A00] to-[#FF016C] before:absolute before:top-1/2 before:left-1/2 before:-translate-x-1/2 before:-translate-y-1/2 before:w-24 before:h-24 before:rounded-full before:-z-10 before:bg-white"
            >
              <img
                class="w-[90px] h-[90px] rounded-full shadow-avatar"
                :src="authInfo.image"
                alt="avatar"
              />
            </figure>

            <label
              for="imageProperty"
              class="block w-11 h-11 mx-auto -mt-7 mb-3 relative z-10 rounded-full border-2 cursor-pointer bg-heading border-white"
            >
              <input
                @change="saveImage"
                accept="image/png, image/jpeg, image/jpg"
                ref="imageProperty"
                type="file"
                id="imageProperty"
                class="w-full h-full rounded-full opacity-0 cursor-pointer"
              />
              <i
                class="lab lab-edit-2 absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 -z-10 lab-font-size-24 lab-font-color-1"
              ></i>
            </label>

            <h3 class="font-medium text-sm leading-6 capitalize mb-0.5">
              {{ textShortener(authInfo.name, 20) }}
            </h3>
            <p class="text-xs mb-0.5">{{ authInfo.email }}</p>
            <p dir="ltr" class="text-xs">
              {{ authInfo.country_code }}{{ authInfo.phone }}
            </p>
            <h3 class="font-medium text-sm leading-6 capitalize mb-0.5">
              {{ authInfo.currency_balance }}
            </h3>
          </div>
          <nav>
            <router-link
              :to="{ name: 'admin.profile.editProfile' }"
              class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]"
            >
              <i class="lab lab-edit lab-font-size-17"></i>
              <span class="text-sm leading-6 capitalize">{{
                $t("button.edit_profile")
              }}</span>
            </router-link>

            <router-link
              :to="{ name: 'admin.profile.changePassword' }"
              class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]"
            >
              <i class="lab lab-key lab-font-size-17"></i>
              <span class="text-sm leading-6 capitalize">{{
                $t("button.change_password")
              }}</span>
            </router-link>

            <button
              @click="logout()"
              class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]"
            >
              <i class="lab lab-logout lab-font-size-17"></i>
              <span class="text-sm leading-6 capitalize">{{
                $t("button.logout")
              }}</span>
            </button>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <div
    id="order"
    v-if="orderNotificationStatus"
    ref="orderNotificationModal"
    class="modal active ff-modal"
  >
    <div class="modal-dialog max-w-[360px] p-6 text-center relative">
      <button
        @click.prevent="closeOrderNotificationModal"
        class="modal-close absolute top-4 right-4"
      >
        <i class="fa-regular fa-circle-xmark"></i>
      </button>
      <h3 class="text-[18px] font-semibold leading-8 mb-6">
        {{ orderNotificationMessage }}
        <span class="block">{{
          $t("message.please_check_your_order_list")
        }}</span>
      </h3>
      <router-link
        @click.prevent="closeOrderNotificationModal"
        :to="{ path: '/admin/' + getUrl() }"
        class="db-btn h-[38px] shadow-[0px_6px_10px_rgba(255,_0,_107,_0.24)] bg-primary text-white"
      >
        {{ $t("button.let_me_check") }}
      </router-link>
    </div>
  </div>
  <!-- sessionModal Modal -->
  <!-- <div id="sessionModal" class="modal fade show">
    <div class="modal-dialog">
      <div class="modal-header pb-3 border-b border-[#D9DBE9]">
        <h3 class="capitalize font-medium">
          {{ $t("label.session Open") }}
        </h3>
      </div>
      <div class="modal-body">
        <div
          class="container max-w-[360px] py-6 p-4 mb-6 sm:px-6 shadow-xs rounded-2xl bg-white"
        >
          <h2
            class="capitalize mb-6 text-center text-[22px] font-semibold leading-[34px] text-heading"
          >
            {{ $t("label.Session Open") }}
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
          <form @submit.prevent="login">
            <div class="mb-4">
              <label
                for="formEmail"
                class="text-sm capitalize mb-1 text-heading"
                >{{ $t("label.email") }}</label
              >
              <input
                type="text"
                :class="errors.email ? 'invalid' : ''"
                v-model="form.email"
                class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]"
                id="formEmail"
              />
              <small class="db-field-alert" v-if="errors.email">{{
                errors.email[0]
              }}</small>
            </div>
            <div class="mb-4">
              <label
                for="formPassword"
                class="text-sm capitalize mb-1 text-heading"
                >{{ $t("label.password") }}</label
              >
              <input
                autocomplete="off"
                type="password"
                :class="errors.password ? 'invalid' : ''"
                v-model="form.password"
                class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]"
                id="formPassword"
              />
              <small class="db-field-alert" v-if="errors.password">{{
                errors.password[0]
              }}</small>
            </div>
            <div class="flex items-center justify-between mb-6">
              <div class="db-field-checkbox p-0">
                <div class="custom-checkbox w-3 h-3">
                  <input
                    type="checkbox"
                    id="checkbox2"
                    class="custom-checkbox-field"
                  />
                  <i
                    class="fa-solid fa-check custom-checkbox-icon leading-[9px] text-[9px] rounded-[3px] border-[#6E7191]"
                  ></i>
                </div>
                <label
                  for="checkbox2"
                  class="db-field-label text-xs text-heading"
                >
                  {{ $t("label.remember_me") }}
                </label>
              </div>
              <router-link
                :to="{ name: 'auth.forgetPassword' }"
                class="capitalize text-xs font-medium transition text-primary hover:underline"
              >
                {{ $t("button.forget_password") }}
              </router-link>
            </div>
            <button
              type="submit"
              class="w-full h-12 text-center capitalize font-medium rounded-3xl mb-6 text-white bg-primary"
            >
              {{ $t("button.login") }}
            </button>
            <div class="flex items-center justify-center gap-2 mb-4">
              <span class="text-xs text-[#6E7191]">{{
                $t("message.have_account")
              }}</span>
              <router-link
                :to="{ name: 'auth.signupPhone' }"
                class="text-xs font-medium text-primary"
              >
                {{ $t("button.signup") }}
              </router-link>
            </div>

            <div v-if="enums.activityEnum.ENABLE == setting.site_guest_login">
              <p class="text-sm uppercase text-center mb-3 text-[#6E7191]">
                {{ $t("label.or") }}
              </p>
              <router-link
                :to="{ name: 'auth.guestLogin' }"
                class="w-full h-12 leading-[46px] text-center capitalize font-medium rounded-3xl border text-primary border-primary bg-white"
              >
                {{ $t("button.login_as_guest") }}
              </router-link>
            </div>
          </form>
        </div>

        <div
          v-if="
            demo === 'true' ||
            demo === 'TRUE' ||
            demo === 'True' ||
            demo === '1' ||
            demo === 1
          "
          class="container max-w-[360px] py-6 p-4 sm:px-6 shadow-xs rounded-2xl bg-white"
        >
          <h2 class="mb-6 text-center text-lg font-medium text-heading">
            {{ $t("message.for_quick_demo") }}
          </h2>
          <nav class="grid grid-cols-2 gap-3">
            <button
              @click.prevent="setupCredit('admin')"
              class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-orange-500"
              id="adminClick"
            >
              {{ $t("label.admin") }}
            </button>
            <button
              @click.prevent="setupCredit('customer')"
              class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-emerald-500"
              id="customerClick"
            >
              {{ $t("label.customer") }}
            </button>
            <button
              @click.prevent="setupCredit('branchManager')"
              class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-sky-600"
              id="branchManagerClick"
            >
              {{ $t("label.branch_manager") }}
            </button>
            <button
              @click.prevent="setupCredit('posOperator')"
              class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-purple-500"
              id="posOperatorClick"
            >
              {{ $t("label.pos_operator") }}
            </button>
            <button
              @click.prevent="setupCredit('chef')"
              class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-blue-500"
              id="chefClick"
            >
              {{ $t("label.chef_kitchen") }}
            </button>
          </nav>
        </div>
      </div>
    </div>
  </div> -->
</template>

<script>
// import OpenSessionComponent from "../../frontend/auth/OpenSessionComponent";
import activityEnum from "../../../enums/modules/activityEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";
import axios from "axios";
import ENV from "../../../config/env";
import { routes } from "../../../router";
import router from "../../../router";

export default {
  name: "BackendNavbarComponent",

  data() {
    return {
      loading: {
        isActive: false,
      },
      form: {
        email: "",
        password: "",
      },
      errors: {},
      permissions: {},
      demo: ENV.DEMO,
      enums: {
        activityEnum: activityEnum,
      },
      defaultBranch: null,
      sidebarOpen: true,
      pos: {
        permission: false,
        url: "",
      },
      branchProps: {
        paginate: 0,
        order_column: "id",
        order_type: "asc",
      },
      orderNotificationStatus: false,
      orderNotificationMessage: "",
      orderNotification: {
        tablePermission: false,
        tableUrl: "",
        permission: false,
        url: "",
        orderType: null,
      },
    };
  },
  computed: {
    logged: function () {
      return this.$store.getters.authStatus;
    },
    setting: function () {
      return this.$store.getters["frontendSetting/lists"];
    },
    carts: function () {
      return this.$store.getters["frontendCart/lists"];
    },
    authInfo: function () {
      return this.$store.getters.authInfo;
    },
    authBranch: function () {
      return this.$store.getters.authBranchId;
    },
    branches: function () {
      return this.$store.getters["backendGlobalState/branches"];
    },
    branch: function () {
      return this.$store.getters["backendGlobalState/branchShow"];
    },
    languages: function () {
      return this.$store.getters["frontendLanguage/lists"];
    },
    language: function () {
      return this.$store.getters["frontendLanguage/show"];
    },
    permissions: function () {
      return this.$store.getters.authPermission;
    },
    defaultPermission: function () {
      return this.$store.getters.authDefaultPermission;
    },
    defaultMenu: function () {
      return this.$store.getters.authDefaultMenu;
    },
    sidebar() {
      return this.$store.getters["globalState/lists"].topSidebar;
    },
  },
  mounted() {
    appService.responsiveLoad();
    this.$store.dispatch("globalState/set", { topSidebar: this.sidebarOpen });
    this.$store
      .dispatch("defaultAccess/show")
      .then((res) => {
        this.defaultBranch = res.data.data.branch_id;
        this.$store
          .dispatch("backendGlobalState/branchShow", res.data.data.branch_id)
          .then()
          .catch();
      })
      .catch();
    this.$store
      .dispatch("backendGlobalState/branches", this.branchProps)
      .then()
      .catch();

    this.orderPermissionCheck();
    this.posPermissionCheck();

    window.setTimeout(() => {
      if (
        this.$store.getters.authStatus &&
        this.setting.notification_fcm_api_key &&
        this.setting.notification_fcm_auth_domain &&
        this.setting.notification_fcm_project_id &&
        this.setting.notification_fcm_storage_bucket &&
        this.setting.notification_fcm_messaging_sender_id &&
        this.setting.notification_fcm_app_id &&
        this.setting.notification_fcm_measurement_id
      ) {
        initializeApp({
          apiKey: this.setting.notification_fcm_api_key,
          authDomain: this.setting.notification_fcm_auth_domain,
          projectId: this.setting.notification_fcm_project_id,
          storageBucket: this.setting.notification_fcm_storage_bucket,
          messagingSenderId: this.setting.notification_fcm_messaging_sender_id,
          appId: this.setting.notification_fcm_app_id,
          measurementId: this.setting.notification_fcm_measurement_id,
        });
        const messaging = getMessaging();

        Notification.requestPermission().then((permission) => {
          if (permission === "granted") {
            getToken(messaging, {
              vapidKey: this.setting.notification_fcm_public_vapid_key,
            })
              .then((currentToken) => {
                if (currentToken) {
                  axios
                    .post("/frontend/device-token/web", { token: currentToken })
                    .then()
                    .catch((error) => {
                      if (error.response.data.message === "Unauthenticated.") {
                        this.$store.dispatch("loginDataReset");
                      }
                    });
                }
              })
              .catch();
          }
        });

        onMessage(messaging, (payload) => {
          const notificationTitle = payload.notification.title;
          const notificationOptions = {
            body: payload.notification.body,
            icon: "/images/default/firebase-logo.png",
          };
          new Notification(notificationTitle, notificationOptions);

          if (
            payload.data.topicName === "new-order-found" &&
            this.orderNotification.permission
          ) {
            this.orderNotificationStatus = true;
            this.orderNotificationMessage = payload.notification.body;
            const audio = new Audio(this.setting.notification_audio);
            audio.play();
          }

          if (
            payload.data.topicName === "new-table-order-found" &&
            this.orderNotification.tablePermission
          ) {
            this.orderNotification.orderType = "table";
            this.orderNotificationStatus = true;
            this.orderNotificationMessage = payload.notification.body;
            const audio = new Audio(this.setting.notification_audio);
            audio.play();
          }
        });
      }
    }, 5000);
  },
  methods: {
    testCloseSessionButton() {
      // Test button existence
      const button = document.getElementById("closeSettion");
      if (!button) {
        console.error('Error: Button with ID "closeSettion" not found');
        return;
      }

      // Test button visibility
      const computedStyle = window.getComputedStyle(button);
      if (
        computedStyle.display === "none" ||
        computedStyle.visibility === "hidden"
      ) {
        console.warn("Warning: Close session button is not visible to users");
      }

      // Test click handler
      const clickHandler = button.onclick;
      if (!clickHandler) {
        console.error("Button has no click handler assigned");
      }
    },
    login: function () {
      try {
        this.loading.isActive = true;
        this.$store
          .dispatch("logout")
          .then((res) => {
            this.$store
              .dispatch("login", this.form)
              .then((res) => {
                // appService.modalHide("#sessionModal");
                this.loading.isActive = false;
                alertService.success(res.data.message);

                router.push({ name: "admin.pos" });

                setTimeout(() => {
                  appService.recursiveRouter(routes, this.permission);
                }, 300);
              })
              .catch((err) => {
                this.loading.isActive = false;
                this.errors = err.response.data.errors;
              });
          })
          .catch();
      } catch (err) {
        this.loading.isActive = false;
      }
    },
    close: function () {
      this.errors = {};
    },
    setupCredit: function (e) {
      if (e === "admin") {
        this.form.email = "admin@example.com";
        this.form.password = "123456";
      } else if (e === "customer") {
        this.form.email = "customer@example.com";
        this.form.password = "123456";
      } else if (e === "branchManager") {
        this.form.email = "branchmanager@example.com";
        this.form.password = "123456";
      } else if (e === "posOperator") {
        this.form.email = "posoperator@example.com";
        this.form.password = "123456";
      } else if (e === "chef") {
        this.form.email = "chef@example.com";
        this.form.password = "123456";
      }
    },
    textShortener: function (text, number = 30) {
      return appService.textShortener(text, number);
    },
    logout: function () {
      this.$store
        .dispatch("logout")
        .then((res) => {
          this.$router.push({ name: "frontend.home" });
        })
        .catch();
    },
    login_: function () {
      try {
        //  appService.modalShow("#sessionModal");
        this.loading.isActive = true;
        this.$store
          .dispatch("logout")
          .then((res) => {
            this.loading.isActive = false;
            alertService.success(this.$t("message.session Closed success"));
            // appService.modalShow("#sessionModal");
            this.$router.push({ name: "auth.OpenSession" });
          })
          .catch();
      } catch (err) {
        this.loading.isActive = false;
      }
    },
    handleSidebar: function () {
      this.sidebarOpen = !this.sidebar;
      this.$store.dispatch("globalState/set", { topSidebar: this.sidebarOpen });

      if (
        document?.querySelector(".db-sidebar")?.classList?.contains("active")
      ) {
        document?.querySelector(".db-main")?.classList?.remove("expand");
        document?.querySelector(".db-sidebar")?.classList?.remove("active");
      } else {
        document?.querySelector(".db-sidebar")?.classList?.add("active");
        document?.querySelector(".db-main")?.classList?.add("expand");
      }
    },
    changeBranch: function (id) {
      this.$store
        .dispatch("defaultAccess/saveOrUpdate", { branch_id: id })
        .then((res) => {
          this.$store
            .dispatch("backendGlobalState/branchShow", id)
            .then((res) => {
              location.reload();
            })
            .catch();
        });
    },
    changeLanguage: function (id, code) {
      this.defaultLanguage = id;
      this.$store
        .dispatch("globalState/set", { language_id: id, language_code: code })
        .then((res) => {
          this.$store
            .dispatch("frontendLanguage/show", id)
            .then((res) => {
              this.$i18n.locale = res.data.data.code;
            })
            .catch();
        })
        .catch();
    },
    posPermissionCheck: function () {
      const permissions = this.$store.getters.authPermission;
      if (permissions.length > 0) {
        _.forEach(permissions, (permission) => {
          if (permission.name === "pos") {
            if (permission.access === true) {
              this.pos.permission = true;
              this.pos.url = permission.url;
            }
          }
        });
      }
    },
    saveImage: function () {
      if (this.$refs.imageProperty.files[0]) {
        try {
          this.loading.isActive = true;
          const formData = new FormData();
          formData.append("image", this.$refs.imageProperty.files[0]);
          this.$store
            .dispatch("frontendEditProfile/changeImage", { form: formData })
            .then((res) => {
              this.$store
                .dispatch("updateAuthInfo", res.data.data)
                .then((res) => {
                  this.loading.isActive = false;
                  alertService.success(this.$t("message.photo_update"));
                  this.$refs.imageProperty.value = null;
                })
                .catch((err) => {
                  this.loading.isActive = false;
                  alertService.error(err);
                });
            })
            .catch((err) => {
              this.loading.isActive = false;
              this.imageErrors = err.response.data.errors;
            });
        } catch (err) {
          this.loading.isActive = false;
          alertService.error(err.response.data.message);
        }
      }
    },
    orderPermissionCheck: function () {
      const permissions = this.$store.getters.authPermission;
      if (permissions.length > 0) {
        _.forEach(permissions, (permission) => {
          if (permission.name === "online-orders") {
            if (permission.access === true) {
              this.orderNotification.permission = true;
              this.orderNotification.url = permission.url;
            }
          }

          if (permission.name === "table-orders") {
            if (permission.access === true) {
              this.orderNotification.tablePermission = true;
              this.orderNotification.tableUrl = permission.url;
            }
          }
        });
      }
    },
    getUrl: function () {
      return this.orderNotification.orderType === "table"
        ? this.orderNotification.tableUrl
        : this.orderNotification.url;
    },
    closeOrderNotificationModal: function () {
      const modalTarget = this.$refs.orderNotificationModal;
      modalTarget?.classList?.remove("active");
      document.body.style.overflowY = "auto";
      this.loading.isActive = false;
      this.orderNotificationStatus = false;
      this.orderNotification.orderType = null;
    },

    closeFullScreen: function () {
      if (document.fullscreenElement || document.webkitFullscreenElement) {
        const elementDbCustomerMain =
          document?.querySelector(".db-main-customer");
        const headerDiv = document?.querySelector(".db-header");
        elementDbCustomerMain?.classList.remove(
          "db-main-customer",
          "customer-display"
        );
        elementDbCustomerMain?.classList.add("db-main");
        elementDbCustomerMain?.classList.remove("hiddenHeader");
        headerDiv?.classList.remove("active", "hidden");
        document?.exitFullscreen();
      }
      document.removeEventListener("mousemove", handleMouseMove);
    },

    fullScreen: function (event) {
      if (this.$route.path.includes("order-status-screen")) {
        const elementDbMain = document?.querySelector(".db-main");
        const elementDbCustomerMain =
          document?.querySelector(".db-main-customer");
        const headerDiv = document.querySelector(".db-header");

        if (elementDbMain) {
          elementDbMain.classList.remove("db-main");
          elementDbMain.classList.add("db-main-customer", "customer-display");
          elementDbMain.classList.add("hiddenHeader");
          headerDiv.classList.add("active", "hidden");
        } else {
          elementDbCustomerMain.classList.remove(
            "db-main-customer",
            "customer-display"
          );
          elementDbCustomerMain.classList.add("db-main");
          elementDbCustomerMain.classList.remove("hiddenHeader");
          headerDiv.classList.remove("active", "hidden");
        }
      }

      this.toggleFullscreen();
    },
    toggleFullscreen: function () {
      let elem = document.documentElement;
      if (!document.fullscreenElement) {
        if (elem.requestFullscreen) {
          elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) {
          elem.webkitRequestFullscreen();
        } else if (elem.mozRequestFullScreen) {
          elem.mozRequestFullScreen();
        } else if (elem.msRequestFullscreen) {
          elem.msRequestFullscreen();
        }
        document.addEventListener("mousemove", handleMouseMove);
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
          document.webkitExitFullscreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.msExitFullscreen) {
          document.msExitFullscreen();
        }
        document.removeEventListener("mousemove", handleMouseMove);
      }
    },
  },
};
</script>

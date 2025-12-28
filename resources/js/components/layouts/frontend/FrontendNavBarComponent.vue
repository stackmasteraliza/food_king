<template>
  <LoadingComponent :props="loading" />
  <header class="shadow-xs bg-white ff-header" ref="ffHeader">
    <div
      class="container flex flex-col lg:flex-row items-center justify-between"
    >
      <div class="w-full lg:w-fit">
        <router-link :to="{ name: 'frontend.home' }">
          <img v-if="setting && setting.theme_logo" class="w-32" :src="setting.theme_logo" alt="logo" />
          <div v-else class="w-32 h-8 bg-gray-200 animate-pulse rounded"></div>
        </router-link>
      </div>

      <nav class="items-center justify-center gap-6 hidden lg:flex">
        <router-link
          :to="{ name: 'frontend.home' }"
          :class="checkIsPathAndRoutePathSame('/home') ? 'text-primary' : ''"
          class="capitalize text-sm font-medium text-heading"
        >
          {{ $t("menu.home") }}
        </router-link>
        <router-link
          :to="{ name: 'frontend.menu', query: { s: categoryProps.slug } }"
          :class="checkIsPathAndRoutePathSame('/menu') ? 'text-primary' : ''"
          class="capitalize text-sm font-medium text-heading"
        >
          {{ $t("label.menu") }}
        </router-link>
        <router-link
          :to="{ name: 'frontend.offers' }"
          :class="checkIsPathAndRoutePathSame('/offers') ? 'text-primary' : ''"
          class="capitalize text-sm font-medium text-heading"
        >
          {{ $t("label.offers") }}
        </router-link>
      </nav>

      <div
        class="flex flex-col items-center justify-end gap-3 w-full mt-4 lg:flex-row lg:w-fit lg:mt-0"
      >
        <form
          @submit.prevent="search"
          class="header-search-group group flex items-center justify-center border border-solid gap-2 px-2 w-full lg:w-52 h-8 rounded-3xl transition border-[#EFF0F6] bg-[#EFF0F6] focus-within:bg-white focus-within:border-primary"
        >
          <button type="submit" class="header-search-submit">
            <i class="lab lab-search-normal"></i>
          </button>
          <input
            type="search"
            v-model="searchItem"
            :placeholder="$t('button.search')"
            class="header-search-field w-full h-full text-xs appearance-none placeholder:font-normal placeholder:text-paragraph text-heading"
          />
          <button
            type="button"
            @click.prevent="searchReset"
            class="header-search-button transition invisible group-focus-within:visible"
          >
            <i
              class="lab lab-close-circle-line lab-font-size-16 lab-font-weight-600 text-red-500"
            ></i>
          </button>
        </form>
        <div
          v-if="setting.site_language_switch === enums.activityEnum.ENABLE"
          class="hidden lg:block relative dropdown-group w-full sm:w-fit"
        >
          <button
            class="flex items-center justify-center gap-1.5 w-fit rounded-3xl capitalize text-sm font-medium h-8 px-3 border transition text-heading bg-white border-gray-200 dropdown-btn"
          >
            <img
              :src="language.image"
              alt="flag"
              class="w-4 h-4 rounded-full"
            />
            <span class="whitespace-nowrap">{{ language.name }}</span>
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
        <button
          @click.prevent="openCanvas('cart')"
          type="button"
          class="webcart hidden lg:flex items-center justify-center gap-1.5 w-fit rounded-3xl capitalize text-sm font-medium h-8 px-3 transition text-white bg-heading"
        >
          <i class="lab lab-bag-2 lab-font-size-17"></i>
          <span style="font-family: 'saudi_riyal'" class="whitespace-nowrap"
            >{{
              currencyFormat(
                subtotal,
                setting.site_digit_after_decimal_point,
                setting.site_default_currency_symbol,
                setting.site_currency_position
              )
            }}
            &#xE900;
          </span>
        </button>
        <router-link
          v-if="!logged"
          class="hidden lg:flex items-center justify-center gap-1 w-fit rounded-3xl capitalize text-sm font-medium h-8 px-3 transition text-white bg-primary"
          :to="{ name: 'auth.login' }"
        >
          <i class="lab lab-profile-circle"></i>
          <span class="whitespace-nowrap">{{ $t("label.login") }}</span>
        </router-link>

        <div v-else class="dropdown-group">
          <button
            class="dropdown-btn hidden lg:flex items-center justify-center gap-1 w-fit rounded-3xl capitalize text-sm font-medium h-8 px-3 transition text-white bg-primary"
          >
            <i class="lab lab-profile-circle"></i>
            <span class="whitespace-nowrap">{{ $t("label.account") }}</span>
            <i class="lab lab-arrow-down-2 text-xs ml-1.5 lab-font-size-15"></i>
          </button>
          <div
            class="dropdown-list w-80 absolute top-12 ltr:right-0 rtl:left-0 z-[60] rounded-xl shadow-paper bg-white transition-all duration-300 scale-y-0 origin-top"
          >
            <div class="flex items-center gap-3 p-4 mb-2">
              <figure
                class="flex-shrink-0 relative z-10 w-[68px] h-[68px] rounded-full border-2 border-dashed border-white bg-gradient-to-t from-[#FF7A00] to-[#FF016C] before:absolute before:inset-0 before:-z-10 before:rounded-full before:scale-[1.03] before:bg-white"
              >
                <a
                  class="relative w-full h-full scale-[0.98] overflow-hidden shadow-avatar rounded-full"
                >
                  <img
                    class="w-full h-full rounded-full object-cover"
                    :src="profile.image"
                    alt="avatar"
                  />
                  <label
                    for="avatar"
                    class="block absolute bottom-0 w-full flex items-center justify-center py-1 cursor-pointer bg-white/90"
                  >
                    <svg
                      width="18"
                      height="18"
                      viewBox="0 0 24 24"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M20.97 1H18.03C16.76 1 16 1.76 16 3.03V5.97C16 7.24 16.76 8 18.03 8H20.97C22.24 8 23 7.24 23 5.97V3.03C23 1.76 22.24 1 20.97 1ZM21.19 4.31C21.07 4.43 20.91 4.49 20.75 4.49C20.59 4.49 20.43 4.43 20.31 4.31L20.13 4.13V6.37C20.13 6.72 19.85 7 19.5 7C19.15 7 18.87 6.72 18.87 6.37V4.13L18.69 4.31C18.45 4.55 18.05 4.55 17.81 4.31C17.57 4.07 17.57 3.67 17.81 3.43L19.06 2.18C19.11 2.13 19.18 2.09 19.25 2.06C19.27 2.05 19.29 2.05 19.31 2.04C19.36 2.02 19.41 2.01 19.47 2.01C19.49 2.01 19.51 2.01 19.53 2.01C19.6 2.01 19.66 2.02 19.73 2.05C19.74 2.05 19.74 2.05 19.75 2.05C19.82 2.08 19.88 2.12 19.93 2.17C19.94 2.18 19.94 2.18 19.95 2.18L21.2 3.43C21.44 3.67 21.44 4.07 21.19 4.31Z"
                        fill="#292D32"
                      />
                      <path
                        d="M8.99914 10.38C10.3136 10.38 11.3791 9.31443 11.3791 8C11.3791 6.68556 10.3136 5.62 8.99914 5.62C7.6847 5.62 6.61914 6.68556 6.61914 8C6.61914 9.31443 7.6847 10.38 8.99914 10.38Z"
                        fill="#292D32"
                      />
                      <path
                        d="M20.97 8H20.5V12.61L20.37 12.5C19.59 11.83 18.33 11.83 17.55 12.5L13.39 16.07C12.61 16.74 11.35 16.74 10.57 16.07L10.23 15.79C9.52 15.17 8.39 15.11 7.59 15.65L3.85 18.16C3.63 17.6 3.5 16.95 3.5 16.19V7.81C3.5 4.99 4.99 3.5 7.81 3.5H16V3.03C16 2.63 16.07 2.29 16.23 2H7.81C4.17 2 2 4.17 2 7.81V16.19C2 17.28 2.19 18.23 2.56 19.03C3.42 20.93 5.26 22 7.81 22H16.19C19.83 22 22 19.83 22 16.19V7.77C21.71 7.93 21.37 8 20.97 8Z"
                        fill="#292D32"
                      />
                    </svg>
                    <input
                      type="file"
                      id="avatar"
                      @change="saveImage"
                      ref="imageProperty"
                      accept="image/png, image/jpeg, image/jpg"
                      class="opacity-0 cursor-pointer absolute inset-0 -z-10"
                    />
                  </label>
                </a>
              </figure>
              <figure>
                <figcaption class="flex-auto">
                  <h3 class="text-sm font-medium capitalize mb-0.5">
                    {{ profile.name }}
                  </h3>
                  <h4 class="text-xs text-paragraph mb-1.5">
                    {{ profile.email }}
                  </h4>
                  <h5 class="text-sm font-medium">
                    {{ profile.currency_balance }}
                  </h5>
                </figcaption>
              </figure>
            </div>
            <nav class="px-4">
              <router-link
                v-if="
                  profile.role_id !== enums.roleEnum.CUSTOMER &&
                  Object.keys(authDefaultPermission).length > 0
                "
                :to="{ path: '/admin/' + defaultMenu?.url }"
                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]"
              >
                <i class="lab-font-size-17" :class="defaultMenu?.icon"></i>
                <span class="text-sm leading-6 capitalize">{{
                  $t("menu." + defaultMenu?.language)
                }}</span>
              </router-link>

              <router-link
                :to="{ name: 'frontend.myOrder' }"
                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]"
              >
                <i class="lab lab-reserve-line lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{
                  $t("button.my_orders")
                }}</span>
              </router-link>

              <router-link
                :to="{ name: 'frontend.editProfile' }"
                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]"
              >
                <i class="lab lab-edit lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{
                  $t("button.edit_profile")
                }}</span>
              </router-link>

              <router-link
                :to="{ name: 'frontend.chat' }"
                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]"
              >
                <i class="lab lab-messages-line lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{
                  $t("button.chat")
                }}</span>
              </router-link>

              <router-link
                :to="{ name: 'frontend.address' }"
                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]"
              >
                <i class="lab lab-map lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{
                  $t("button.address")
                }}</span>
              </router-link>

              <router-link
                :to="{ name: 'frontend.changePassword' }"
                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]"
              >
                <i class="lab lab-key lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{
                  $t("button.change_password")
                }}</span>
              </router-link>
              <button
                @click="logout()"
                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]"
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
    </div>
  </header>

  <div id="language" class="modal">
    <div class="modal-dialog max-w-xs">
      <div class="modal-header">
        <h3 class="modal-title">{{ $t("menu.languages") }}</h3>
        <button
          class="modal-close fa-regular fa-circle-xmark"
          @click="hideLanguageModal()"
        ></button>
      </div>
      <nav class="p-2">
        <button
          @click="changeLanguage(language.id, language.code)"
          v-for="language in languages"
          type="button"
          class="w-full flex items-center gap-2 py-1.5 px-2.5 rounded-md cursor-pointer hover:bg-gray-100"
        >
          <img :src="language.image" alt="flag" class="w-4 h-4 rounded-full" />
          <span class="text-heading capitalize text-sm">{{
            language.name
          }}</span>
        </button>
      </nav>
    </div>
  </div>

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
        :to="{ path: '/admin/' + orderNotification.url }"
        class="db-btn h-[38px] shadow-[0px_6px_10px_rgba(255,_0,_107,_0.24)] bg-primary text-white"
      >
        {{ $t("button.let_me_check") }}
      </router-link>
    </div>
  </div>
</template>

<script>
import statusEnum from "../../../enums/modules/statusEnum";
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";
import LoadingComponent from "../../frontend/components/LoadingComponent";
import axios from "axios";
import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";
import activityEnum from "../../../enums/modules/activityEnum";
import roleEnum from "../../../enums/modules/roleEnum";
import _ from "lodash";

export default {
  name: "FrontendNavbarComponent",
  components: { LoadingComponent },
  data() {
    return {
      loading: {
        isActive: false,
      },
      orderNotificationStatus: false,
      orderNotificationMessage: "",
      currentRoute: "",
      defaultBranch: null,
      defaultLanguage: null,
      defaultCountryCode: null,
      enums: {
        activityEnum: activityEnum,
        roleEnum: roleEnum,
      },
      branchProps: {
        paginate: 0,
        order_column: "id",
        order_type: "asc",
        status: statusEnum.ACTIVE,
      },
      languageProps: {
        paginate: 0,
        order_column: "id",
        order_type: "asc",
        status: statusEnum.ACTIVE,
      },
      categoryProps: {
        search: {
          paginate: 0,
          order_column: "sort",
          order_type: "asc",
          status: statusEnum.ACTIVE,
        },
        slug: "",
      },
      orderNotification: {
        permission: false,
        url: "",
      },
      searchItem: "",
    };
  },
  computed: {
    logged: function () {
      return this.$store.getters.authStatus;
    },
    authDefaultPermission: function () {
      return this.$store.getters.authDefaultPermission;
    },
    profile: function () {
      return this.$store.getters.authInfo;
    },
    setting: function () {
      return this.$store.getters["frontendSetting/lists"];
    },
    language: function () {
      return this.$store.getters["frontendLanguage/show"];
    },
    languages: function () {
      return this.$store.getters["frontendLanguage/lists"];
    },
    categories: function () {
      return this.$store.getters["frontendItemCategory/lists"];
    },
    subtotal: function () {
      return this.$store.getters["frontendCart/subtotal"];
    },
    defaultMenu: function () {
      return this.$store.getters.authDefaultMenu;
    },
  },
  mounted() {
    window.addEventListener("scroll", () => {
      const resetRoutes = [
        "frontend.myOrder",
        "frontend.editProfile",
        "frontend.chat",
        "frontend.address",
        "frontend.changePassword",
        "auth.login",
        "auth.signupPhone",
        "auth.signupRegister",
        "auth.forgetPassword",
        "auth.verifyEmail",
        "frontend.page",
        "frontend.offers",
      ];
      if (this.$refs.ffHeader) {
        if (!resetRoutes.includes(this.$route.name)) {
          if (window.scrollY > 0) {
            this.$refs.ffHeader.classList.add("active");
          } else {
            this.$refs.ffHeader.classList.remove("active");
          }
        } else {
          this.$refs.ffHeader.classList.remove("active");
        }
      }
    });

    this.currentRoute = this.$route.path;
    this.loading.isActive = true;
    this.orderPermissionCheck();
    this.$store
      .dispatch("frontendSetting/lists")
      .then((res) => {
        this.defaultBranch = res.data.data.site_default_branch;
        this.defaultLanguage = res.data.data.site_default_language;
        this.defaultCountryCode = res.data.data.company_country_code;

        const globalState = this.$store.getters["globalState/lists"];
        if (globalState.branch_id > 0) {
          this.defaultBranch = globalState.branch_id;
        }

        if (globalState.language_id > 0) {
          this.defaultLanguage = globalState.language_id;
        }
        this.$store
          .dispatch("frontendLanguage/lists", this.languageProps)
          .then()
          .catch();
        this.$store
          .dispatch("frontendLanguage/show", this.defaultLanguage)
          .then((res) => {
            this.$i18n.locale = res.data.data.code;
            this.$store.dispatch("globalState/init", {
              language_code: res.data.data.code,
            });
          })
          .catch();
        this.$store
          .dispatch("frontendItemCategory/lists", this.categoryProps.search)
          .then()
          .catch();
        this.loading.isActive = false;

        window.setTimeout(() => {
          this.$store
            .dispatch("frontendCountryCode/show", this.defaultCountryCode)
            .then()
            .catch();
          this.$store.dispatch("frontendCart/initOrderType", {
            order_setup_delivery: res.data.data.order_setup_delivery,
            order_setup_takeaway: res.data.data.order_setup_takeaway,
          });

          if (
            this.$store.getters.authStatus &&
            res.data.data.notification_fcm_api_key &&
            res.data.data.notification_fcm_auth_domain &&
            res.data.data.notification_fcm_project_id &&
            res.data.data.notification_fcm_storage_bucket &&
            res.data.data.notification_fcm_messaging_sender_id &&
            res.data.data.notification_fcm_app_id &&
            res.data.data.notification_fcm_measurement_id
          ) {
            initializeApp({
              apiKey: res.data.data.notification_fcm_api_key,
              authDomain: res.data.data.notification_fcm_auth_domain,
              projectId: res.data.data.notification_fcm_project_id,
              storageBucket: res.data.data.notification_fcm_storage_bucket,
              messagingSenderId:
                res.data.data.notification_fcm_messaging_sender_id,
              appId: res.data.data.notification_fcm_app_id,
              measurementId: res.data.data.notification_fcm_measurement_id,
            });
            const messaging = getMessaging();

            Notification.requestPermission().then((permission) => {
              if (permission === "granted") {
                getToken(messaging, {
                  vapidKey: res.data.data.notification_fcm_public_vapid_key,
                })
                  .then((currentToken) => {
                    if (currentToken) {
                      axios
                        .post("/frontend/device-token/web", {
                          token: currentToken,
                        })
                        .then()
                        .catch((error) => {
                          if (
                            error.response.data.message === "Unauthenticated."
                          ) {
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
                const audio = new Audio(res.data.data.notification_audio);
                audio.play();
              }
            });
          }
        }, 3000);
      })
      .catch((err) => {
        this.loading.isActive = false;
      });
  },

  methods: {
    textShortener: function (text, number = 30) {
      return appService.textShortener(text, number);
    },
    openCanvas: function (id) {
      return appService.openCanvas(id);
    },
    checkIsPathAndRoutePathSame(path) {
      if (this.currentRoute === path) {
        return true;
      }
    },
    changeBranch: function (id) {
      this.$store.dispatch("frontendBranch/show", id);
      this.$store.dispatch("globalState/set", { branch_id: id });
    },
    changeLanguage: function (id, code) {
      this.hideLanguageModal();
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
    hideLanguageModal: function () {
      appService.modalHide("#language");
    },
    logout: function () {
      this.$store
        .dispatch("logout")
        .then((res) => {
          this.$router.push({ name: "frontend.home" });
        })
        .catch();
    },
    login: function () {
      try {
        this.$store
          .dispatch("logout")
          .then((res) => {
            this.$router.push({ name: "auth.OpenSession" });
          })
          .catch();
      } catch (err) {
        this.loading.isActive = false;
      }
    },
    close: function () {
      this.errors = {};
    },
    currencyFormat(amount, decimal, currency, position) {
      return appService.currencyFormat(amount, decimal, currency, position);
    },
    search: function () {
      if (typeof this.searchItem !== "undefined" && this.searchItem !== "") {
        this.$router.push({
          name: "frontend.search",
          query: { s: this.searchItem },
        });
        this.searchItem = "";
      }
    },
    searchReset: function () {
      this.searchItem = "";
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
        });
      }
    },
    closeOrderNotificationModal: function () {
      const modalTarget = this.$refs.orderNotificationModal;
      modalTarget?.classList?.remove("active");
      document.body.style.overflowY = "auto";
      this.loading.isActive = false;
      this.orderNotificationStatus = false;
    },
  },

  watch: {
    $route(to, from) {
      this.currentRoute = to.path;
    },
    categories: {
      deep: true,
      handler(category) {
        if (category.length > 0) {
          if (category[0].slug !== "undefined") {
            this.categoryProps.slug = category[0].slug;
          }
        }
      },
    },
  },
};
</script>

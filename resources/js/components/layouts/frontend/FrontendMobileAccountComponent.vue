<template>
    <div v-if="logged" id="user-profile-dropdown-box"
        class="profile-paper fixed top-0 left-0 z-[60] overflow-y-auto w-screen h-screen w-full p-4 shadow-paper bg-white">
        <div class="flex items-center gap-3 p-4 mb-2">
            <figure
                class="flex-shrink-0 relative z-10 w-[68px] h-[68px] rounded-full border-2 border-dashed border-white bg-gradient-to-t from-[#FF7A00] to-[#FF016C] before:absolute before:inset-0 before:-z-10 before:rounded-full before:scale-[1.03] before:bg-white">
                <a class="relative w-full h-full scale-[0.98] overflow-hidden shadow-avatar rounded-full">
                    <img class="w-full h-full rounded-full object-cover" :src="profile.image" alt="avatar">
                    <label for="avatar"
                        class="block absolute bottom-0 w-full flex items-center justify-center py-1 cursor-pointer bg-white/90">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20.97 1H18.03C16.76 1 16 1.76 16 3.03V5.97C16 7.24 16.76 8 18.03 8H20.97C22.24 8 23 7.24 23 5.97V3.03C23 1.76 22.24 1 20.97 1ZM21.19 4.31C21.07 4.43 20.91 4.49 20.75 4.49C20.59 4.49 20.43 4.43 20.31 4.31L20.13 4.13V6.37C20.13 6.72 19.85 7 19.5 7C19.15 7 18.87 6.72 18.87 6.37V4.13L18.69 4.31C18.45 4.55 18.05 4.55 17.81 4.31C17.57 4.07 17.57 3.67 17.81 3.43L19.06 2.18C19.11 2.13 19.18 2.09 19.25 2.06C19.27 2.05 19.29 2.05 19.31 2.04C19.36 2.02 19.41 2.01 19.47 2.01C19.49 2.01 19.51 2.01 19.53 2.01C19.6 2.01 19.66 2.02 19.73 2.05C19.74 2.05 19.74 2.05 19.75 2.05C19.82 2.08 19.88 2.12 19.93 2.17C19.94 2.18 19.94 2.18 19.95 2.18L21.2 3.43C21.44 3.67 21.44 4.07 21.19 4.31Z"
                                fill="#292D32" />
                            <path
                                d="M8.99914 10.38C10.3136 10.38 11.3791 9.31443 11.3791 8C11.3791 6.68556 10.3136 5.62 8.99914 5.62C7.6847 5.62 6.61914 6.68556 6.61914 8C6.61914 9.31443 7.6847 10.38 8.99914 10.38Z"
                                fill="#292D32" />
                            <path
                                d="M20.97 8H20.5V12.61L20.37 12.5C19.59 11.83 18.33 11.83 17.55 12.5L13.39 16.07C12.61 16.74 11.35 16.74 10.57 16.07L10.23 15.79C9.52 15.17 8.39 15.11 7.59 15.65L3.85 18.16C3.63 17.6 3.5 16.95 3.5 16.19V7.81C3.5 4.99 4.99 3.5 7.81 3.5H16V3.03C16 2.63 16.07 2.29 16.23 2H7.81C4.17 2 2 4.17 2 7.81V16.19C2 17.28 2.19 18.23 2.56 19.03C3.42 20.93 5.26 22 7.81 22H16.19C19.83 22 22 19.83 22 16.19V7.77C21.71 7.93 21.37 8 20.97 8Z"
                                fill="#292D32" />
                        </svg>
                        <input type="file" id="avatar" @change="saveImage" ref="imageProperty"
                            accept="image/png, image/jpeg, image/jpg"
                            class="opacity-0 cursor-pointer absolute inset-0 -z-10">
                    </label>
                </a>
            </figure>
            <figure>
                <figcaption class="flex-auto">
                    <h3 class="text-sm font-medium capitalize mb-0.5">{{ profile.name }}</h3>
                    <h4 class="text-xs text-paragraph mb-1.5">{{ profile.email }}</h4>
                    <h5 class="text-sm font-medium">{{ profile.currency_balance }}</h5>
                </figcaption>
            </figure>
            <button @click.prevent="closeCanvas('user-profile-dropdown-box')"
                class="fa-solid fa-xmark absolute top-4 right-4 text-white bg-[#FB4E4E] xmark-btn"></button>
        </div>

        <nav class="px-4">
            <router-link
                v-if="profile.role_id !== enums.roleEnum.CUSTOMER && Object.keys(authDefaultPermission).length > 0"
                :to="{ path: '/admin/' + defaultMenu?.url }" v-on:click="linkClick"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab-font-size-17" :class="defaultMenu?.icon"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('menu.' + defaultMenu?.language)
                    }}</span>
            </router-link>

            <router-link :to="{ name: 'frontend.myOrder' }" v-on:click="linkClick"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-reserve-line lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.my_orders') }}</span>
            </router-link>

            <router-link :to="{ name: 'frontend.editProfile' }" v-on:click="linkClick"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-edit lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.edit_profile') }}</span>
            </router-link>

            <router-link :to="{ name: 'frontend.chat' }" v-on:click="linkClick"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-messages-line lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.chat') }}</span>
            </router-link>

            <router-link :to="{ name: 'frontend.address' }" v-on:click="linkClick"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-map lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.address') }}</span>
            </router-link>

            <router-link :to="{ name: 'frontend.changePassword' }" v-on:click="linkClick"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-key lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.change_password') }}</span>
            </router-link>
            <button @click="showLanguageModal()" data-modal="#language" type="button"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-languages lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.change_language') }}</span>
            </button>
            <button @click="logout()"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-logout lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.logout') }}</span>
            </button>
        </nav>
    </div>
</template>

<script>

import alertService from "../../../services/alertService";
import LoadingComponent from "../../frontend/components/LoadingComponent";
import appService from "../../../services/appService";
import roleEnum from "../../../enums/modules/roleEnum";
export default {
    name: "FrontendMobileAccountComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                roleEnum: roleEnum
            },
        }
    },
    computed: {
        logged: function () {
            return this.$store.getters.authStatus;
        },
        profile: function () {
            return this.$store.getters.authInfo;
        },
        authDefaultPermission: function () {
            return this.$store.getters.authDefaultPermission;
        },
        defaultMenu: function () {
            return this.$store.getters.authDefaultMenu;
        }
    },
    methods: {
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        closeCanvas: function (id) {
            return appService.closeCanvas(id);
        },
        linkClick: function () {
            const profile = document.getElementById('user-profile-dropdown-box');
            document.body.style.overflowY = "auto";
            profile?.classList?.remove('active');
        },
        modalHide: function () {
            appService.modalHide('#user-profile-dropdown-box');
        },
        showLanguageModal: function () {
            this.modalHide();
            appService.modalShow('#language');
        },
        logout: function () {
            this.$store.dispatch("logout").then(res => {
                this.modalHide();
                this.$router.push({ name: "frontend.home" });
            }).catch();
        },
        saveImage: function () {
            if (this.$refs.imageProperty.files[0]) {
                try {
                    this.loading.isActive = true;
                    const formData = new FormData();
                    formData.append("image", this.$refs.imageProperty.files[0]);
                    this.$store.dispatch("frontendEditProfile/changeImage", { form: formData }).then((res) => {
                        this.$store.dispatch('updateAuthInfo', res.data.data).then(res => {
                            this.loading.isActive = false;
                            alertService.success(this.$t("message.photo_update"));
                            this.$refs.imageProperty.value = null;
                        }).catch((err) => {
                            this.loading.isActive = false;
                            alertService.error(err);
                        });
                    }).catch((err) => {
                        this.loading.isActive = false;
                        this.imageErrors = err.response.data.errors;
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }
        },
    }
}
</script>
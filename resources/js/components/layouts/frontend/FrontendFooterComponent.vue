<template>
    <LoadingComponent :props="loading" />
    <footer class="footer-part pt-16 pb-8 mb-14 lg:mb-0">
        <div class="container">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
                <!-- Brand Section -->
                <div class="lg:col-span-1">
                    <router-link :to="{ name: 'frontend.home' }" class="inline-block group">
                        <img v-if="setting && setting.theme_footer_logo"
                            class="mb-6 w-40 transition-transform duration-300 group-hover:scale-105"
                            :src="setting.theme_footer_logo" alt="logo">
                        <div v-else class="mb-6 w-40 h-10 bg-white/20 animate-pulse rounded-lg"></div>
                    </router-link>
                    <p class="text-sm mb-6 text-white/80 leading-relaxed max-w-xs">
                        {{ $t('label.subscribe_short_text') }}
                    </p>

                    <!-- Newsletter Subscription -->
                    <form @submit.prevent="saveSubscription" class="mb-8">
                        <div class="relative group">
                            <input type="email"
                                :placeholder="$t('label.your_email_address')"
                                v-model="subscriptionProps.post.email"
                                class="w-full px-5 py-4 pr-32 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder-white/50 outline-none focus:border-white/40 focus:bg-white/15 transition-all duration-300">
                            <button type="submit"
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white text-primary px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-white/90 hover:shadow-lg hover:shadow-white/20 transition-all duration-300 active:scale-95">
                                {{ $t('button.subscribe') }}
                            </button>
                        </div>
                    </form>

                    <!-- Social Media -->
                    <div v-if="setting.social_media_facebook || setting.social_media_twitter || setting.social_media_instagram || setting.social_media_youtube">
                        <h4 class="text-sm font-semibold mb-4 text-white/90 uppercase tracking-wider">{{ $t('label.follow_us_on') }}</h4>
                        <div class="flex items-center gap-3">
                            <a v-if="setting.social_media_facebook" target="_blank" :href="setting.social_media_facebook"
                                class="w-11 h-11 bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-blue-600 hover:border-blue-600 rounded-xl flex items-center justify-center text-white hover:scale-110 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                                <i class="lab lab-facebook text-lg"></i>
                            </a>
                            <a v-if="setting.social_media_twitter" target="_blank" :href="setting.social_media_twitter"
                                class="w-11 h-11 bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-sky-500 hover:border-sky-500 rounded-xl flex items-center justify-center text-white hover:scale-110 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                                <i class="lab lab-twitter text-lg"></i>
                            </a>
                            <a v-if="setting.social_media_instagram" target="_blank" :href="setting.social_media_instagram"
                                class="w-11 h-11 bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-gradient-to-br hover:from-purple-600 hover:via-pink-500 hover:to-orange-400 hover:border-pink-500 rounded-xl flex items-center justify-center text-white hover:scale-110 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                                <i class="lab lab-instagram text-lg"></i>
                            </a>
                            <a v-if="setting.social_media_youtube" target="_blank" :href="setting.social_media_youtube"
                                class="w-11 h-11 bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-red-600 hover:border-red-600 rounded-xl flex items-center justify-center text-white hover:scale-110 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                                <i class="lab lab-youtube text-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links Section -->
                <div class="sm:pl-8">
                    <h3 class="text-lg font-bold mb-6 text-white relative inline-block">
                        {{ $t('label.useful_links') }}
                        <span class="absolute -bottom-2 left-0 w-12 h-1 bg-gradient-to-r from-white to-transparent rounded-full"></span>
                    </h3>
                    <nav v-if="pages.length > 0" class="flex flex-col items-start gap-4">
                        <router-link v-for="page in pages" :key="page.slug"
                            class="group flex items-center gap-2 text-white/80 hover:text-white transition-all duration-300"
                            :to="{ name: 'frontend.page', params: { slug: page.slug } }">
                            <span class="w-2 h-2 bg-white/40 rounded-full group-hover:bg-white group-hover:scale-125 transition-all duration-300"></span>
                            <span class="capitalize group-hover:translate-x-1 transition-transform duration-300">{{ page.title }}</span>
                        </router-link>
                    </nav>
                </div>

                <!-- Download Apps Section -->
                <div>
                    <h3 v-if="setting.site_android_app_link || setting.site_ios_app_link"
                        class="text-lg font-bold mb-6 text-white relative inline-block">
                        {{ $t('label.download_our_apps') }}
                        <span class="absolute -bottom-2 left-0 w-12 h-1 bg-gradient-to-r from-white to-transparent rounded-full"></span>
                    </h3>
                    <div class="flex flex-col gap-3 mb-8">
                        <a target="_blank" v-if="setting.site_android_app_link" :href="setting.site_android_app_link"
                            class="group block w-40 overflow-hidden rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-black/20">
                            <img class="w-full" :src="setting.image_play_store" alt="Google Play Store">
                        </a>
                        <a target="_blank" v-if="setting.site_ios_app_link" :href="setting.site_ios_app_link"
                            class="group block w-40 overflow-hidden rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-black/20">
                            <img class="w-full" :src="setting.image_app_store" alt="Apple App Store">
                        </a>
                    </div>
                </div>

                <!-- Contact Section -->
                <div>
                    <h3 class="text-lg font-bold mb-6 text-white relative inline-block">
                        {{ $t('label.get_in_touch') }}
                        <span class="absolute -bottom-2 left-0 w-12 h-1 bg-gradient-to-r from-white to-transparent rounded-full"></span>
                    </h3>
                    <ul class="flex flex-col gap-5">
                        <li class="group">
                            <a :href="'mailto:' + setting.company_email"
                                class="flex items-center gap-4 text-white/80 hover:text-white transition-all duration-300">
                                <div class="w-12 h-12 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl flex items-center justify-center group-hover:bg-white/20 group-hover:scale-110 transition-all duration-300">
                                    <i class="lab lab-sms-tracking lab-font-size-24"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-white/50 uppercase tracking-wider">Email</span>
                                    <span class="text-sm font-medium">{{ setting.company_email }}</span>
                                </div>
                            </a>
                        </li>
                        <li class="group">
                            <a :href="'tel:' + setting.company_phone"
                                class="flex items-center gap-4 text-white/80 hover:text-white transition-all duration-300">
                                <div class="w-12 h-12 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl flex items-center justify-center group-hover:bg-white/20 group-hover:scale-110 transition-all duration-300">
                                    <i class="lab lab-call-center lab-font-size-24"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-white/50 uppercase tracking-wider">Phone</span>
                                    <span class="text-sm font-medium">{{ setting.company_phone }}</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="mt-12 pt-8 border-t border-white/10">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-white/60 text-center md:text-left">
                        {{ setting.site_copyright }}
                    </p>
                    <div class="flex items-center gap-2 text-white/40 text-sm">
                        <span>Made with</span>
                        <svg class="w-4 h-4 text-red-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                        <span>for food lovers</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>


<script>
import statusEnum from "../../../enums/modules/statusEnum";
import menuSectionEnum from "../../../enums/modules/menuSectionEnum";
import axios from "axios";
import alertService from "../../../services/alertService";
import LoadingComponent from "../../frontend/components/LoadingComponent";

export default {
    name: "FrontendFooterComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            subscriptionProps: {
                post: {
                    email: ""
                }
            }
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        pages: function () {
            return this.$store.getters['frontendPage/lists'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendPage/lists", {
            paginate: 0,
            order_column: "id",
            order_type: "asc",
            menu_section_id: menuSectionEnum.FOOTER,
            status: statusEnum.ACTIVE
        }).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        saveSubscription: function () {
            const url = '/frontend/subscriber';
            this.loading.isActive = true;
            axios.post(url, this.subscriptionProps.post).then(res => {
                this.loading.isActive = false;
                this.subscriptionProps.post.email = "";
                alertService.success(this.$t("message.subscribe"));
            }).catch((err) => {
                if (typeof err.response.data.errors === 'object') {
                    _.forEach(err.response.data.errors, (error) => {
                        alertService.error(error[0]);
                    });
                }
                this.loading.isActive = false;
            });
        }
    }
}
</script>
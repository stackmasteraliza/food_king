<template>
    <LoadingComponent :props="loading" />
    <div v-if="sliders.length > 0" class="container mb-5 mt-5 sm:mt-8">
        <Swiper dir="rtl" :slides-per-view="1" :speed="1000" :loop="true" :navigation="true"
            :pagination="{ clickable: true }" :autoplay="{ delay: 2500 }" :modules="modules" class="banner-swiper">
            <SwiperSlide class="" v-for="(slider, index) in sliders" :key="index">
                <img class="w-full rounded-2xl" :src="slider.image" alt="banner">
            </SwiperSlide>
        </Swiper>
    </div>
</template>

<script>
import alertService from "../../../services/alertService";
import statusEnum from "../../../enums/modules/statusEnum";
import LoadingComponent from "../components/LoadingComponent";
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';

export default {
    name: "SliderComponent",
    components: {
        LoadingComponent,
        Swiper,
        SwiperSlide,
    },
    setup() {
        return {
            modules: [Navigation, Pagination],
        }
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            sliderProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'desc',
                    status: statusEnum.ACTIVE
                }
            },
            settings: {
                pauseAutoplayOnHover: true,
                wrapAround: true,
                transition: 1000,
                autoplay: 5000
            },
        }
    },
    computed: {
        sliders: function () {
            return this.$store.getters['frontendSlider/lists'];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendSlider/lists", this.sliderProps.search).then((res) => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    }
}
</script>

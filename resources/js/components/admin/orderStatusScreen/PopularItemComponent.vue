<template>
  <LoadingComponent :props="loading" />
  <div class="col-span-2 md:block hidden">
    <div class="customer-screen db-card rounded-[10px] h-screen md:h-[calc(100vh-117px)] overflow-hidden pb-20">
      <div class="p-3 pb-2 mb-6">
        <h3 class="text-[22px] font-semibold text-[#0084FF]">{{ $t("label.popular_menu_items") }}</h3>
      </div>
      <div class="p-3 grid grid-cols-2 lg:grid-cols-3 gap-11 overflow-auto thin-scrolling h-full">
        <div class="flex flex-col items-center" v-for="item in items">
          <div class="max-w-[148px] w-full h-[102px] rounded-full mb-4">
            <img class="w-full h-full rounded-full" :src="item.thumb" alt="">
          </div>
          <h6 class="text-base font-medium text-[#6E7191]">{{ item.name }}</h6>
          <p class="text-lg font-semibold text-primary">{{ item.currency_price }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";


export default {
  name: "PopularItemComponent",
  components: {
    LoadingComponent,
  },
  data() {
    return {
      loading: {
        isActive: false,
      },
    };
  },
  computed: {
    items: function () {
      return this.$store.getters["orderStatusScreenOrder/mostPopularItems"];
    },
  },
  mounted() {
    this.popularItems();
  },
  methods: {
    popularItems: function () {
      this.loading.isActive = true;
      this.$store
        .dispatch("orderStatusScreenOrder/mostPopularItems")
        .then((res) => {

          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
  },
};
</script>
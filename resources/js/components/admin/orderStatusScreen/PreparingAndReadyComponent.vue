<template>
  <LoadingContentComponent :props="loading" />
  <div class="col-span-1 customer-screen db-card rounded-[10px] h-screen md:h-[calc(100vh-117px)] overflow-hidden">
    <h3 class="text-lg font-semibold text-white p-3 pb-2 bg-primary mb-2 rounded-t-[10px] text-center">{{
      $t("label.preparing") }}
    </h3>
    <div class="content-wrapper p-3 overflow-auto thin-scrolling h-full">
      <ul
        class="[&_li]:mb-6 [&_li]:text-[40px] [&_li]:font-semibold [&_li]:leading-10 w-full text-center text-[#1F1F39] mb-20">
        <li v-for="preparingItem in preparingItems" :key="preparingItem">{{ preparingItem.token }}</li>
      </ul>
    </div>
  </div>
  <div class="col-span-1 customer-screen db-card rounded-[10px] h-screen md:h-[calc(100vh-117px)] overflow-hidden">
    <h3 class="text-lg font-semibold text-white p-3 pb-2 bg-[#1AB759] mb-2 rounded-t-[10px] text-center">{{
      $t("label.ready") }}</h3>
    <div class="content-wrapper p-3 overflow-auto thin-scrolling h-full">
      <ul
        class="[&_li]:mb-6 [&_li]:text-[40px] [&_li]:font-semibold [&_li]:leading-10 w-full text-center text-[#1F1F39] mb-20">
        <li v-for="preparedItem in preparedItems" :key="preparedItem">{{ preparedItem.token }}</li>
      </ul>
    </div>
  </div>
</template>
<script>
import LoadingContentComponent from "../components/LoadingContentComponent";
import orderStatusEnum from "../../../enums/modules/orderStatusEnum";


export default {
  name: "PreparingAndReadyComponent",
  components: {
    LoadingContentComponent,
  },
  data() {
    return {
      loading: {
        isActive: false,
      },
      preparedItems: [],
      preparingItems: [],
      enums: {
        orderStatusEnum: orderStatusEnum,
      },
      autoRefreshInterval: null,
    };
  },
  computed: {
    orders: function () {
      return this.$store.getters["orderStatusScreenOrder/lists"];
    },
    items: function () {
      return this.$store.getters["orderStatusScreenOrder/mostPopularItems"];
    },
  },
  mounted() {
    this.list();
    this.startAutoRefresh();
  },
  methods: {
    startAutoRefresh() {
      if (this.$route.path.includes('order-status-screen')) {
        this.autoRefreshInterval = setInterval(() => {
          this.list();
        }, 30000);
      }
    },
    stopAutoRefresh() {
      if (this.autoRefreshInterval) {
        clearInterval(this.autoRefreshInterval);
        this.autoRefreshInterval = null;
      }
    },
    list: function () {
      this.loading.isActive = true;
      this.$store
        .dispatch("orderStatusScreenOrder/lists")
        .then((res) => {
          this.preparingItems = res.data.data.filter(
            (item) => item.status === orderStatusEnum.PREPARING
          );
          this.preparedItems = res.data.data.filter(
            (item) => item.status === orderStatusEnum.PREPARED
          );

          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
  },
  beforeUnmount() {
    this.stopAutoRefresh();

  },
};
</script>
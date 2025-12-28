<template>
  <div class="grid grid-cols-2 md:grid-cols-4 md:grid-flow-row gap-4 ">
    <PopularItemComponent />
    <div class="col-span-2 grid grid-cols-2 gap-4 md:mt-0 mt-[-20px]">
      <PreparingAndReadyComponent />
    </div>
  </div>
</template>
<script>
import PopularItemComponent from "./PopularItemComponent";
import PreparingAndReadyComponent from "./PreparingAndReadyComponent";


export default {
  name: "OrderStatusScreenComponent",
  components: {
    PopularItemComponent,
    PreparingAndReadyComponent
  },
  data() {
    return {
    };
  },
  mounted() {
    this.closeSidebar();
  },
  methods: {
    openSidebar: function () {
      document?.querySelector(".db-main")?.classList?.remove("expand");
      const activeMenu = document.querySelector('.db-sidebar-nav-item.active');
      if (activeMenu) {
        activeMenu.classList.remove('active');
      }

      document?.querySelector('.router-link-exact-active')?.parentElement?.classList?.add('active');
    },
    closeSidebar: function () {
      document?.querySelector(".db-main")?.classList?.add("expand");
      document?.querySelector('.db-header').classList.remove("active", "hidden")
    },
  },
  beforeUnmount() {
    this.openSidebar();

  },
};
</script>
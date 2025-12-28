<template>
  <aside
    class="db-sidebar"
    :class="
      $route.path.includes('kitchen-display-system') ||
      $route.path.includes('order-status-screen')
        ? 'hidden'
        : ''
    "
  >
    <div class="db-sidebar-header">
      <router-link class="w-24" :to="{ name: 'frontend.home' }">
        <img :src="setting.theme_logo" alt="logo" />
      </router-link>
      <button
        @click.prevent="handleSidebar"
        class="fa-solid fa-xmark xmark-btn close-db-menu"
      ></button>
    </div>
    <!--        {{ menus }}-->
    <nav class="db-sidebar-nav">
      <ul
        class="db-sidebar-nav-list"
        v-if="menus.length > 0"
        v-for="menu in menus"
        :key="menu"
      >
        <li
          class="db-sidebar-nav-item"
          v-if="menu.url === '#'"
          @click.prevent="sidebarActive($event)"
        >
          <a href="javascript:void(0);" class="db-sidebar-nav-title">
            {{ $t("menu." + menu.language) }}
          </a>
        </li>

        <li
          class="db-sidebar-nav-item"
          v-else
          @click.prevent="sidebarActive($event)"
        >
          <router-link :to="'/admin/' + menu.url" class="db-sidebar-nav-menu">
            <i class="text-sm" :class="menu.icon"></i>
            <span class="text-base flex-auto">{{
              $t("menu." + menu.language)
            }}</span>
          </router-link>
        </li>

        <li
          class="db-sidebar-nav-item"
          v-if="menu.children"
          v-for="children in menu.children"
          @click.prevent="sidebarActive($event)"
        >
          <router-link
            :to="'/admin/' + children.url"
            class="db-sidebar-nav-menu"
          >
            <i class="text-sm" :class="children.icon"></i>
            <span class="text-base flex-auto">{{
              $t("menu." + children.language)
            }}</span>
          </router-link>
        </li>
      </ul>
      <!-- <ul class="db-sidebar-nav-list">
        <li class="db-sidebar-nav-item" @click.prevent="sidebarActive($event)">
          <a href="javascript:void(0);" class="db-sidebar-nav-title"
            >POS & Sessions</a
          >
        </li>
        <li class="db-sidebar-nav-item" @click.prevent="sidebarActive($event)">
          <router-link
            :to="{ name: 'pos.dashboard' }"
            class="db-sidebar-nav-menu"
          >
            <i class="text-sm lab lab-pos"></i>
            <span class="text-base flex-auto">POS Dashboard</span>
          </router-link>
        </li>
        <li class="db-sidebar-nav-item" @click.prevent="sidebarActive($event)">
          <router-link
            :to="{ name: 'pos.sessions' }"
            class="db-sidebar-nav-menu"
          >
            <i class="text-sm lab lab-sessions"></i>
            <span class="text-base flex-auto">All Sessions</span>
          </router-link>
        </li>
        <li class="db-sidebar-nav-item" @click.prevent="sidebarActive($event)">
          <router-link :to="{ name: 'pos.start' }" class="db-sidebar-nav-menu">
            <i class="text-sm lab lab-start-session"></i>
            <span class="text-base flex-auto">Start Session</span>
          </router-link>
        </li>
        <li class="db-sidebar-nav-item" @click.prevent="sidebarActive($event)">
          <router-link :to="{ name: 'pos.active' }" class="db-sidebar-nav-menu">
            <i class="text-sm lab lab-active-session"></i>
            <span class="text-base flex-auto">Active Session</span>
          </router-link>
        </li>
        <li class="db-sidebar-nav-item" @click.prevent="sidebarActive($event)">
          <router-link
            :to="{ name: 'pos.cashMovement' }"
            class="db-sidebar-nav-menu"
          >
            <i class="text-sm lab lab-cash-movement"></i>
            <span class="text-base flex-auto">Cash Movement</span>
          </router-link>
        </li>
        <li class="db-sidebar-nav-item" @click.prevent="sidebarActive($event)">
          <router-link
            :to="{ name: 'shift-types.index' }"
            class="db-sidebar-nav-menu"
          >
            <i class="text-sm lab lab-shift-types"></i>
            <span class="text-base flex-auto">Shift Types</span>
          </router-link>
        </li>
        <li class="db-sidebar-nav-item" @click.prevent="sidebarActive($event)">
          <router-link
            :to="{ name: 'pos.approvals' }"
            class="db-sidebar-nav-menu"
          >
            <i class="text-sm lab lab-approvals"></i>
            <span class="text-base flex-auto">Session Approvals</span>
          </router-link>
        </li>
      </ul> -->
    </nav>
  </aside>
</template>

<script>
export default {
  name: "BackendMenuComponent",
  data: function () {
    return {
      activeParentId: 1,
      activeChildId: 0,
      sidebarOpen: false,
    };
  },
  computed: {
    setting: function () {
      return this.$store.getters["frontendSetting/lists"];
    },
    menus: function () {
      return this.$store.getters.authMenu;
    },
    sidebar() {
      return this.$store.getters["globalState/lists"].topSidebar;
    },
  },
  mounted() {
    this.defaultSidebarActive();
  },
  methods: {
    sidebarActive: function (e) {
      const activeMenu = document.querySelector(".db-sidebar-nav-item.active");
      if (activeMenu) {
        activeMenu.classList.remove("active");
      }
      e?.currentTarget?.classList?.add("active");
    },
    defaultSidebarActive: function () {
      if (
        document
          ?.querySelector(".db-sidebar-nav-menu")
          ?.classList?.contains("active")
      ) {
        document
          ?.querySelector(".db-sidebar-nav-menu")
          ?.parentElement?.classList?.add("active");
      } else {
        document
          ?.querySelector(".router-link-exact-active")
          ?.parentElement?.classList?.add("active");
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
  },
};
</script>

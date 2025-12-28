<template>
  <div class="global-activity-monitor">
    <div class="monitor-icon" @click="toggleMonitor">
      <i class="fas fa-history"></i>
      <span class="badge bg-danger" v-if="unseenCount > 0">{{
        unseenCount
      }}</span>
    </div>

    <div class="monitor-panel" :class="{ 'show-panel': showPanel }">
      <div class="panel-header">
        <h5>Activity Monitor</h5>
        <button
          class="btn btn-sm btn-close"
          @click="showPanel = false"
        ></button>
      </div>

      <div class="panel-body">
        <div class="mb-2">
          <input
            type="text"
            v-model="search"
            placeholder="Search activities..."
            class="form-control"
          />
        </div>

        <div class="activity-list">
          <div
            v-for="activity in filteredActivities"
            :key="activity.id"
            class="activity-item"
            :class="{ unseen: !activity.seen }"
            @click="showDetails(activity)"
          >
            <div class="activity-icon">
              <i :class="activityIcon(activity.action)"></i>
            </div>
            <div class="activity-content">
              <div class="activity-title">{{ activity.description }}</div>
              <div class="activity-meta">
                <span>{{ formatDate(activity.created_at) }}</span>
                <span
                  >• {{ activity.user ? activity.user.name : "System" }}</span
                >
              </div>
            </div>
          </div>
        </div>

        <div v-if="filteredActivities.length === 0" class="text-center py-3">
          No activities found
        </div>
      </div>
    </div>

    <!-- Activity Detail Modal -->
    <div class="modal fade" id="activityDetailModal" tabindex="-1">
      <!-- Modal content same as previous implementation -->
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { debounce } from "lodash";

export default {
  data() {
    return {
      showPanel: false,
      activities: [],
      unseenCount: 0,
      search: "",
      pagination: {},
      pollInterval: null,
    };
  },
  computed: {
    filteredActivities() {
      const searchTerm = this.search.toLowerCase();
      return this.activities.filter(
        (activity) =>
          activity.description.toLowerCase().includes(searchTerm) ||
          activity.user?.name?.toLowerCase().includes(searchTerm) ||
          activity.action.toLowerCase().includes(searchTerm)
      );
    },
  },
  mounted() {
    this.fetchActivities();

    // Poll for new activities every 30 seconds
    this.pollInterval = setInterval(() => {
      this.fetchNewActivities();
    }, 30000);

    // Close panel when clicking outside
    document.addEventListener("click", this.handleClickOutside);
    // Listen for new activity events
    window.Echo.channel("activity").listen("NewActivity", (data) => {
      this.activities = [data.activity, ...this.activities];
      this.unseenCount++;
    });
  },
  beforeUnmount() {
    clearInterval(this.pollInterval);
    document.removeEventListener("click", this.handleClickOutside);
    window.Echo.leave("activity");
  },
  methods: {
    async fetchActivities() {
      try {
        const response = await axios.get("/api/operation-logs?recent=1");
        this.activities = response.data.data;
        this.unseenCount = this.activities.filter((a) => !a.seen).length;
      } catch (error) {
        console.error("Failed to fetch activities:", error);
      }
    },
    async fetchNewActivities() {
      try {
        const response = await axios.get("/api/operation-logs?new_only=1");
        if (response.data.data.length > 0) {
          // Add new activities to the top
          this.activities = [...response.data.data, ...this.activities];
          this.unseenCount += response.data.data.length;
        }
      } catch (error) {
        console.error("Failed to fetch new activities:", error);
      }
    },
    activityIcon(action) {
      return (
        {
          "fas fa-trash text-danger": ["delete", "force_logout"].includes(
            action
          ),
          "fas fa-edit text-warning": action === "update",
          "fas fa-plus-circle text-success": action === "create",
          "fas fa-sign-in-alt text-info": action === "login",
          "fas fa-user-clock": !action,
        }[action] || "fas fa-info-circle text-primary"
      );
    },
    formatDate(date) {
      return new Date(date).toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
      });
    },
    toggleMonitor() {
      this.showPanel = !this.showPanel;
      if (this.showPanel) {
        this.markAllAsSeen();
      }
    },
    async markAllAsSeen() {
      if (this.unseenCount > 0) {
        try {
          await axios.put("/api/operation-logs/mark-seen");
          this.activities = this.activities.map((a) => ({ ...a, seen: true }));
          this.unseenCount = 0;
        } catch (error) {
          console.error("Failed to mark activities as seen:", error);
        }
      }
    },
    showDetails(activity) {
      // Show details modal (same as previous implementation)
    },
    handleClickOutside(event) {
      const monitorEl = this.$el;
      if (!monitorEl.contains(event.target)) {
        this.showPanel = false;
      }
    },
  },
};
</script>

<style scoped>
.global-activity-monitor {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 9999;
}

.monitor-icon {
  width: 50px;
  height: 50px;
  background: #3490dc;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.5rem;
  cursor: pointer;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  position: relative;
  transition: all 0.3s ease;
}

.monitor-icon:hover {
  transform: scale(1.1);
}

.badge {
  position: absolute;
  top: -5px;
  right: -5px;
}

.monitor-panel {
  position: absolute;
  bottom: 60px;
  right: 0;
  width: 350px;
  max-height: 70vh;
  background: white;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  display: none;
  flex-direction: column;
  transform: translateY(20px);
  opacity: 0;
  transition: all 0.3s ease;
}

.show-panel {
  display: flex;
  transform: translateY(0);
  opacity: 1;
}

.panel-header {
  padding: 12px 15px;
  background: #3490dc;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.panel-body {
  padding: 10px;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.activity-list {
  overflow-y: auto;
  flex-grow: 1;
}

.activity-item {
  padding: 10px;
  border-bottom: 1px solid #eee;
  display: flex;
  cursor: pointer;
  transition: background 0.2s;
}

.activity-item:hover {
  background: #f8f9fa;
}

.activity-item.unseen {
  background: #f0f7ff;
  border-left: 3px solid #3490dc;
}

.activity-icon {
  width: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
}

.activity-content {
  flex-grow: 1;
  margin-left: 10px;
}

.activity-title {
  font-weight: 500;
  margin-bottom: 3px;
}

.activity-meta {
  font-size: 0.8rem;
  color: #6c757d;
}
</style>

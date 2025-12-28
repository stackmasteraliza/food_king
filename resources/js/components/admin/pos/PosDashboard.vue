<template>
  <div class="container py-6">
    <div class="db-card">
      <div class="db-card-header border-none">
        <h3 class="db-card-title">POS Dashboard</h3>
      </div>
      <div class="db-card-body">
        <div v-if="!hasActiveSession" class="p-6 text-center">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
            <i class="las la-exclamation-circle text-3xl"></i>
          </div>
          <h3 class="text-lg font-medium text-gray-800 mb-2">No Active Session</h3>
          <p class="text-gray-600 mb-4">You must start a new POS session before performing any operations.</p>
          <router-link to="/pos/sessions/start" class="db-btn bg-primary text-white">
            <i class="las la-plus-circle mr-2"></i>
            <span>Start New Session</span>
          </router-link>
        </div>

        <div v-else>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="db-card shadow-sm">
              <div class="db-card-body">
                <h5 class="db-card-title">Session Management</h5>
                <p class="text-gray-600 mb-3">Start or manage your POS sessions.</p>
                <div class="flex flex-wrap gap-2">
                  <router-link v-if="activeSession" :to="{ name: 'pos.active' }" class="db-btn bg-primary text-white">
                    <i class="las la-play-circle mr-2"></i> Go to Active Session
                  </router-link>
                  <router-link v-else :to="{ name: 'pos.start' }" class="db-btn bg-primary text-white">
                    <i class="las la-plus-circle mr-2"></i> Start New Session
                  </router-link>
                  <router-link :to="{ name: 'pos.sessions' }" class="db-btn db-btn-outline">
                    <i class="las la-list mr-2"></i> View All Sessions
                  </router-link>
                </div>
              </div>
            </div>
            <div class="db-card shadow-sm">
              <div class="db-card-body">
                <h5 class="db-card-title">Cash Movement</h5>
                <p class="text-gray-600 mb-3">Record and track cash inflows and outflows.</p>
                <div class="flex flex-wrap gap-2">
                  <router-link to="/pos/cash-movement" class="db-btn bg-primary text-white">
                    <i class="las la-money-bill-wave mr-2"></i> Manage Cash
                  </router-link>
                </div>
              </div>
            </div>
            <div class="db-card shadow-sm">
              <div class="db-card-body">
                <h5 class="db-card-title">Shift Types</h5>
                <p class="text-gray-600 mb-3">Configure different types of shifts for your POS system.</p>
                <div class="flex flex-wrap gap-2">
                  <router-link :to="{ name: 'shift-types.index' }" class="db-btn db-btn-outline">
                    <i class="las la-cog mr-2"></i> Manage Shift Types
                  </router-link>
                </div>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="db-card shadow-sm">
              <div class="db-card-body">
                <h5 class="db-card-title">Session Approvals</h5>
                <p class="text-gray-600 mb-3">Review and approve ended sessions (Manager access only).</p>
                <div class="flex flex-wrap gap-2">
                  <router-link :to="{ name: 'pos.approvals' }" class="db-btn bg-primary text-white">
                    <i class="las la-check-circle mr-2"></i> View Approvals
                  </router-link>
                </div>
              </div>
            </div>
            <div class="db-card shadow-sm">
              <div class="db-card-body">
                <h5 class="db-card-title">Quick Stats</h5>
                <ul class="list-none p-0 m-0 space-y-2 text-gray-700">
                  <li class="flex justify-between items-center">
                    <span>Total Sessions:</span>
                    <span class="font-medium">{{ stats.totalSessions }}</span>
                  </li>
                  <li class="flex justify-between items-center">
                    <span>Pending Approvals:</span>
                    <span class="font-medium text-yellow-600">{{ stats.pendingApprovals }}</span>
                  </li>
                  <li class="flex justify-between items-center">
                    <span>Active Sessions:</span>
                    <span class="font-medium text-green-600">{{ stats.activeSessions }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'PosDashboard',
  data() {
    return {
      activeSession: null,
      stats: {
        totalSessions: 0,
        pendingApprovals: 0,
        activeSessions: 0
      },
      hasActiveSession: false
    }
  },
  created() {
    this.fetchDashboardData();
    this.checkActiveSession();
  },
  methods: {
    async fetchDashboardData() {
      try {
        const response = await axios.get('pos/dashboard-stats');
        this.activeSession = response.data.activeSession;
        this.stats = response.data.stats;
      } catch (error) {
        console.error('Error fetching dashboard data:', error);
      }
    },
    checkActiveSession() {
      axios.get('pos/sessions/active')
        .then(response => {
          this.hasActiveSession = response.data.hasActiveSession;
          if (response.data.hasActiveSession) {
              this.activeSession = response.data.session;
          }
        })
        .catch(error => {
          console.error('Error checking active session:', error);
        });
    }
  }
}
</script>

<style scoped>
</style>

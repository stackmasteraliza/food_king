<template>
  <div class="container py-6">
    <div v-if="loading" class="text-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"></div>
      <p class="mt-2">Loading session data...</p>
    </div>
    
    <div v-else-if="activeSession">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-heading">Active POS Session</h1>
        <div class="flex items-center space-x-2">
          <span class="px-3 py-1 rounded-full text-sm font-medium" :class="{
            'bg-green-100 text-green-800': activeSession.status === 'active',
            'bg-yellow-100 text-yellow-800': activeSession.status !== 'active'
          }">
            {{ activeSession.status.toUpperCase() }}
          </span>
        </div>
      </div>

      <div class="db-card shadow-sm">
        <div class="db-card-header bg-primary-light">
          <h3 class="db-card-title">Session Details</h3>
        </div>
        <div class="db-card-body">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Shift Type</h4>
                <p class="mt-1">{{ activeSession.shift_type?.name || 'N/A' }}</p>
              </div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Start Time</h4>
                <p class="mt-1">{{ formatDate(activeSession.start_time) }}</p>
              </div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Session ID</h4>
                <p class="mt-1">{{ activeSession.id }}</p>
              </div>
            </div>
            <div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Starting Cash</h4>
                <p class="mt-1 text-lg font-semibold">{{ formatCurrency(activeSession.opening_float) }}</p>
              </div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Current Balance</h4>
                <p class="mt-1 text-lg font-semibold">{{ formatCurrency(activeSession.current_balance || activeSession.opening_float) }}</p>
              </div>
            </div>
          </div>

          <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex flex-wrap gap-3 justify-end">
              <router-link to="/pos/cash-movement" class="db-btn bg-primary text-white hover:bg-primary-dark">
                <i class="las la-money-bill-wave mr-2"></i> Cash Movement
              </router-link>
              <router-link :to="`/pos/sessions/summary/${activeSession.id}`" class="db-btn bg-warning text-black hover:bg-warning-dark">
                <i class="las la-file-invoice-dollar mr-2"></i> View Summary
              </router-link>
              <button @click="confirmEndSession" class="db-btn bg-danger text-black hover:bg-danger-dark">
                <i class="las la-stop-circle mr-2"></i> End Session
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-12">
      <div class="max-w-md mx-auto">
        <div class="p-4 bg-yellow-50 rounded-lg mb-6">
          <i class="las la-exclamation-circle text-4xl text-yellow-500 mb-3"></i>
          <h3 class="text-lg font-medium text-yellow-800 mb-2">No Active Session</h3>
          <p class="text-yellow-700 mb-4">There is no active POS session. Please start a new session to continue.</p>
          <router-link to="/pos/sessions/start" class="db-btn bg-primary text-white hover:bg-primary-dark">
            <i class="las la-plus-circle mr-2"></i> Start New Session
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'PosSessionActive',
  data() {
    return {
      activeSession: null,
      loading: true,
      error: null
    };
  },
  created() {
    this.fetchActiveSession();
  },
  methods: {
    fetchActiveSession() {
      this.loading = true;
      this.error = null;
      
      axios.get('pos/sessions/active')
        .then(response => {
          if (response.data.hasActiveSession && response.data.session) {
            this.activeSession = response.data.session;
          } else {
            this.activeSession = null;
          }
        })
        .catch(error => {
          console.error('Error fetching active session:', error);
          this.error = 'Failed to load session data. Please try again.';
          this.$toast.error(this.error);
          this.activeSession = null;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    async confirmEndSession() {
      const confirmed = await this.$swal.fire({
        title: 'End Current Session?',
        text: 'Are you sure you want to end this session? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, end session',
        cancelButtonText: 'Cancel',
        reverseButtons: true
      });

      if (confirmed.isConfirmed) {
        try {
          await axios.post(`pos/sessions/${this.activeSession.id}/end`);
          this.$toast.success('Session ended successfully');
          this.$router.push(`/pos/sessions/summary/${this.activeSession.id}`);
        } catch (error) {
          console.error('Error ending session:', error);
          this.$toast.error(error.response?.data?.message || 'Failed to end session');
        }
      }
    },
    formatDate(dateStr) {
      if (!dateStr) return 'N/A';
      const date = new Date(dateStr);
      return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
      }).format(date);
    },
    formatCurrency(amount) {
      if (amount === null || amount === undefined || isNaN(amount)) return '$0.00';
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }).format(parseFloat(amount));
    }
  }
}
</script>
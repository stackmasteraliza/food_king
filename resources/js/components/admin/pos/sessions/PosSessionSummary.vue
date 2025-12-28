<template>
  <div class="container py-6">
    <div v-if="loading" class="text-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"></div>
      <p class="mt-2">Loading session data...</p>
    </div>

    <div v-else-if="error" class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
      <div class="flex">
        <div class="flex-shrink-0">
          <i class="las la-exclamation-circle text-red-400 text-xl"></i>
        </div>
        <div class="ml-3">
          <p class="text-sm text-red-700">
            {{ error }}
          </p>
        </div>
      </div>
    </div>

    <template v-else>
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-heading">POS Session Summary</h1>
        <span class="px-3 py-1 rounded-full text-sm font-medium" 
              :class="{
                'bg-green-100 text-green-800': session.status === 'completed',
                'bg-yellow-100 text-yellow-800': session.status !== 'completed'
              }">
          {{ capitalize(session.status || '') }}
        </span>
      </div>

      <div class="db-card shadow-sm mb-6">
        <div class="db-card-header bg-primary-light">
          <h3 class="db-card-title">Session Details</h3>
        </div>
        <div class="db-card-body">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Session ID</h4>
                <p class="mt-1 font-medium">{{ session.id || 'N/A' }}</p>
              </div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Shift Type</h4>
                <p class="mt-1">{{ session.shift_type?.name || 'N/A' }}</p>
              </div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Cashier</h4>
                <p class="mt-1">{{ session.cashier?.name || 'N/A' }}</p>
              </div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Start Time</h4>
                <p class="mt-1">{{ formatDate(session.start_time) }}</p>
              </div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">End Time</h4>
                <p class="mt-1">{{ session.end_time ? formatDate(session.end_time) : 'N/A' }}</p>
              </div>
            </div>
            <div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Opening Float</h4>
                <p class="mt-1 text-lg font-semibold">{{ formatCurrency(session.opening_float) }}</p>
              </div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Sales</h4>
                <p class="mt-1 text-lg font-semibold">{{ formatCurrency(session.total_sales) }}</p>
              </div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Refunds</h4>
                <p class="mt-1 text-lg font-semibold text-red-600">-{{ formatCurrency(session.total_refunds) }}</p>
              </div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Cash Expected</h4>
                <p class="mt-1 text-lg font-semibold">{{ formatCurrency(session.cash_expected) }}</p>
              </div>
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Cash Actual</h4>
                <p class="mt-1 text-lg font-semibold">{{ formatCurrency(session.cash_actual) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="flex justify-end space-x-3">
        <router-link to="/pos/sessions" class="db-btn bg-gray-100 text-gray-800 hover:bg-gray-200">
          <i class="las la-arrow-left mr-2"></i> Back to Sessions
        </router-link>
        <router-link v-if="session.status === 'pending_approval'" 
                    :to="{ name: 'pos.approve', params: { sessionId: session.id } }" 
                    class="db-btn bg-primary text-white hover:bg-primary-dark">
          <i class="las la-check-circle mr-2"></i> Approve Session
        </router-link>
      </div>
    </template>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'PosSessionSummary',
  data() {
    return {
      loading: true,
      error: null,
      session: {}
    };
  },
  mounted() {
    this.fetchSessionSummary();
  },
  methods: {
    async fetchSessionSummary() {
      this.loading = true;
      this.error = null;
      
      try {
        console.log('Fetching session summary for ID:', this.$route.params.id);
        const response = await axios.get(`pos/sessions/summary/${this.$route.params.id}`);
        console.log('API Response:', response.data);
        this.session = response.data;
      } catch (error) {
        console.error('Error fetching session summary:', error);
        this.error = error.response?.data?.message || 'Failed to load session summary. Please try again.';
        this.$toast.error(this.error);
      } finally {
        this.loading = false;
      }
    },
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
      }).format(date);
    },
    formatCurrency(amount) {
      if (amount === null || amount === undefined) return '$0.00';
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }).format(parseFloat(amount));
    },
    capitalize(str) {
      if (!str) return '';
      return str.charAt(0).toUpperCase() + str.slice(1).replace(/_/g, ' ');
    }
  }
};
</script>

<style scoped>
.card {
  border: 1px solid rgba(0, 0, 0, 0.125);
  border-radius: 0.25rem;
}
.card-body {
  padding: 1.25rem;
}
.card-title {
  margin-bottom: 0.75rem;
}
.card-text {
  margin-bottom: 1rem;
}
.btn-primary {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
}
</style>

<template>
  <div class="container py-6">
    <div class="db-card">
      <div class="db-card-header border-none">
        <h3 class="db-card-title">Cash Movement</h3>
      </div>
      
      <div v-if="!hasActiveSession" class="p-6 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
          <i class="las la-exclamation-circle text-3xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-800 mb-2">No Active Session</h3>
        <p class="text-gray-600 mb-4">You must have an active POS session to record cash movements.</p>
        <router-link to="/pos/sessions/start" class="db-btn bg-primary text-white">
          <i class="las la-plus-circle"></i>
          <span>Start New Session</span>
        </router-link>
      </div>

      <div v-else>
        <!-- Session Info Card -->
        <div class="db-card mb-6">
          <div class="db-card-header bg-primary-light">
            <h3 class="db-card-title">Session Information</h3>
          </div>
          <div class="db-card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div class="space-y-2">
                <p class="text-sm text-gray-500">Session ID</p>
                <p class="font-medium">#{{ activeSession.id }}</p>
              </div>
              <div class="space-y-2">
                <p class="text-sm text-gray-500">Start Time</p>
                <p class="font-medium">{{ formatDate(activeSession.start_time) }}</p>
              </div>
              <div class="space-y-2">
                <p class="text-sm text-gray-500">Status</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                      :class="{
                        'bg-green-100 text-green-800': activeSession.status === 'open',
                        'bg-yellow-100 text-yellow-800': activeSession.status !== 'open'
                      }">
                  {{ activeSession.status === 'open' ? 'Active' : 'Closed' }}
                </span>
              </div>
              <div class="space-y-2">
                <p class="text-sm text-gray-500">Opening Float</p>
                <p class="font-medium text-blue-600">{{ formatCurrency(activeSession.opening_float) }}</p>
              </div>
              <div class="space-y-2">
                <p class="text-sm text-gray-500">Total Cash In</p>
                <p class="font-medium text-green-600">{{ formatCurrency(stats.totalCashIn) }}</p>
              </div>
              <div class="space-y-2">
                <p class="text-sm text-gray-500">Current Balance</p>
                <p class="font-medium" :class="{'text-green-600': stats.currentBalance >= 0, 'text-red-600': stats.currentBalance < 0}">
                  {{ formatCurrency(stats.currentBalance) }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="db-card">
          <div class="db-card-header bg-primary-light">
            <h3 class="db-card-title">Record Cash Movement</h3>
          </div>
          <div class="db-card-body">
            <form @submit.prevent="recordMovement" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                  <label for="amount" class="db-field-label">Amount</label>
                  <input 
                    type="number" 
                    id="amount" 
                    v-model="form.amount" 
                    class="db-field-control" 
                    :class="{ 'is-invalid': errors.amount }"
                    :disabled="isSubmitting" 
                    step="0.01" 
                    min="0.01" 
                    placeholder="Enter amount" 
                    required>
                  <div v-if="errors.amount" class="db-field-alert text-red-500">{{ errors.amount[0] }}</div>
                </div>
                
                <div class="form-group">
                  <label for="type" class="db-field-label">Type</label>
                  <select 
                    id="type" 
                    v-model="form.type" 
                    class="db-field-control" 
                    :class="{ 'is-invalid': errors.type }"
                    :disabled="isSubmitting" 
                    required>
                    <option value="in">Cash In</option>
                    <option value="out">Cash Out</option>
                  </select>
                  <div v-if="errors.type" class="db-field-alert text-red-500">{{ errors.type[0] }}</div>
                </div>
              </div>

              <div class="form-group">
                <label for="description" class="db-field-label">Description</label>
                <input 
                  type="text" 
                  id="description" 
                  v-model="form.description" 
                  class="db-field-control" 
                  :class="{ 'is-invalid': errors.description }"
                  :disabled="isSubmitting" 
                  placeholder="Enter description (optional)">
                <div v-if="errors.description" class="db-field-alert text-red-500">{{ errors.description[0] }}</div>
              </div>

              <div class="flex flex-wrap gap-3 justify-end mt-6">
                <button 
                  type="button" 
                  class="db-btn bg-gray-200 text-gray-700 hover:bg-gray-300" 
                  @click="resetForm" 
                  :disabled="isSubmitting">
                  <i class="las la-undo-alt"></i>
                  <span>Reset</span>
                </button>
                <button 
                  type="submit" 
                  class="db-btn bg-primary text-white hover:bg-primary-dark" 
                  :disabled="isSubmitting">
                  <i class="las" :class="{'la-spinner animate-spin': isSubmitting, 'la-save': !isSubmitting}"></i>
                  <span>{{ isSubmitting ? 'Recording...' : 'Record Movement' }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <div class="db-card mt-6">
          <div class="db-card-header bg-primary-light">
            <h3 class="db-card-title">Recent Cash Movements</h3>
          </div>
          <div class="db-card-body p-0">
            <div v-if="movements.length === 0" class="p-8 text-center">
              <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                <i class="las la-coins text-3xl"></i>
              </div>
              <h3 class="text-lg font-medium text-gray-700 mb-1">No Cash Movements</h3>
              <p class="text-gray-500">No cash movements have been recorded for this session yet.</p>
              <div class="mt-4">
                <button 
                  @click="resetForm"
                  class="db-btn bg-primary text-white hover:bg-primary-dark inline-flex items-center">
                  <i class="las la-plus-circle mr-1"></i>
                  <span>Add Your First Movement</span>
                </button>
              </div>
            </div>
            
            <div v-else class="db-table-responsive">
              <table class="db-table stripe">
                <thead class="db-table-head">
                  <tr class="db-table-head-tr">
                    <th class="db-table-head-th">Date/Time</th>
                    <th class="db-table-head-th">Type</th>
                    <th class="db-table-head-th text-right">Amount</th>
                    <th class="db-table-head-th">Description</th>
                  </tr>
                </thead>
                <tbody class="db-table-body">
                  <tr v-for="movement in movements" :key="movement.id" class="db-table-body-tr">
                    <td class="db-table-body-td">{{ formatDate(movement.created_at) }}</td>
                    <td class="db-table-body-td">
                      <span class="px-2 py-1 text-xs font-medium rounded-full" 
                        :class="{
                          'bg-green-100 text-green-800': movement.type === 'in', 
                          'bg-red-100 text-red-800': movement.type === 'out'
                        }">
                        {{ movement.type === 'in' ? 'Cash In' : 'Cash Out' }}
                      </span>
                    </td>
                    <td class="db-table-body-td text-right font-medium" 
                      :class="{'text-green-600': movement.type === 'in', 'text-red-600': movement.type === 'out'}">
                      {{ movement.type === 'in' ? '+' : '-' }}{{ formatCurrency(movement.amount) }}
                    </td>
                    <td class="db-table-body-td">{{ movement.description }}</td>
                  </tr>
                </tbody>
              </table>
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
  name: 'PosCashMovement',
  data() {
    return {
      form: {
        amount: '',
        type: 'in',
        description: ''
      },
      errors: {},
      isSubmitting: false,
      loading: false,
      movements: [],
      hasActiveSession: false,
      activeSessionId: null,
      activeSession: {
        id: null,
        start_time: null,
        status: '',
        opening_float: 0,
        total_sales: 0,
        cash_expected: 0
      },
      stats: {
        totalCashIn: 0,
        totalCashOut: 0,
        currentBalance: 0
      }
    };
  },

  created() {
    this.checkActiveSession();
  },

  methods: {
    getApiHeaders() {
      return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': window.Laravel?.csrfToken || ''
      };
    },

    async checkActiveSession() {
      this.loading = true;
      try {
        const response = await axios.get('pos/sessions/active');
        console.log('PosCashMovement: Active session API response:', response.data);
        this.hasActiveSession = !!response.data.hasActiveSession;
        if (this.hasActiveSession) {
          this.activeSession = response.data.session;
          this.activeSessionId = this.activeSession.id;
          await this.fetchMovements();
        } else {
          console.log('PosCashMovement: No active session reported by API.');
        }
      } catch (error) {
        console.error('PosCashMovement: Error checking active session:', error);
        if (error.response?.status === 401) {
          this.$toast.error('Please log in to continue');
        } else {
          this.$toast.error(error.response?.data?.message || 'Failed to check active session');
        }
      } finally {
        this.loading = false;
      }
    },

    async fetchMovements() {
      if (!this.activeSessionId) {
        console.warn('fetchMovements: No activeSessionId. Skipping fetch.');
        return;
      }
      this.loading = true;
      try {
        console.log(`fetchMovements: Fetching movements for session ID: ${this.activeSessionId}`);
        const response = await axios.get(`pos/sessions/${this.activeSessionId}/cash-movements`);
        console.log('fetchMovements: API Response:', response.data);
        this.movements = response.data.data || [];
        console.log('fetchMovements: Updated movements array:', this.movements);
        this.calculateStats();
      } catch (error) {
        console.error('Error fetching movements:', error);
        if (error.response?.status === 403) {
          this.$toast.error('Unauthorized to view movements');
        } else {
          this.$toast.error(error.response?.data?.message || 'Failed to load cash movements');
        }
      } finally {
        this.loading = false;
      }
    },

    calculateStats() {
      const cashIn = this.movements.filter(m => m.type === 'in');
      const cashOut = this.movements.filter(m => m.type === 'out');
      this.stats.totalCashIn = cashIn.reduce((s, m) => s + parseFloat(m.amount), 0);
      this.stats.totalCashOut = cashOut.reduce((s, m) => s + parseFloat(m.amount), 0);
      this.stats.currentBalance =
        parseFloat(this.activeSession.opening_float || 0) +
        this.stats.totalCashIn -
        this.stats.totalCashOut;
    },

    resetForm() {
      this.form = {
        amount: '',
        type: 'in',
        description: ''
      };
      this.errors = {};
    },

    async recordMovement() {
      this.errors = {};
      this.isSubmitting = true;

      try {
        const response = await axios.post(
          `pos/sessions/${this.activeSession.id}/cash-movements`,
          this.form
        );

        this.$toast.success(response.data?.message || 'Cash movement recorded');
        this.resetForm();
        await this.fetchMovements();
      } catch (error) {
        console.error('Error recording movement:', error);
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors || {};
          this.$toast.error('Please correct the form errors');
        } else if (error.response?.status === 403) {
          this.$toast.error('Unauthorized action');
        } else {
          this.$toast.error(error.response?.data?.message || 'Failed to record movement');
        }
      } finally {
        this.isSubmitting = false;
      }
    },

    formatDate(dateStr) {
      if (!dateStr) return 'N/A';
      const date = new Date(dateStr);
      if (isNaN(date.getTime())) return 'Invalid date';

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
      const value = parseFloat(amount) || 0;
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }).format(value);
    }
  }
};
</script>

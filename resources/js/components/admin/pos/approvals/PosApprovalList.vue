<template>
  <div class="container py-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold text-heading">Session Approvals</h1>
      <div class="flex items-center space-x-2">
        <button @click="fetchApprovals" class="db-btn bg-primary text-white hover:bg-primary-dark">
          <i class="las la-sync-alt mr-2"></i> Refresh
        </button>
      </div>
    </div>

    <div class="db-card shadow-sm">
      <div class="db-card-header bg-primary-light">
        <h3 class="db-card-title">Pending Approvals</h3>
      </div>
      <div class="db-card-body p-0">
        <div v-if="loading" class="p-8 text-center">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"></div>
          <p class="mt-2">Loading approvals...</p>
        </div>

        <div v-else-if="approvals.length === 0" class="p-8 text-center">
          <div class="text-gray-400 mb-4">
            <i class="las la-clipboard-check text-5xl"></i>
          </div>
          <h3 class="text-lg font-medium text-gray-700 mb-1">No Pending Approvals</h3>
          <p class="text-gray-500">There are no sessions waiting for approval at the moment.</p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="db-table">
            <thead class="db-table-head">
              <tr>
                <th class="w-20">ID</th>
                <th>Session</th>
                <th>Shift Type</th>
                <th>Cashier</th>
                <th>Manager</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Variance</th>
                <th class="text-center">Status</th>
                <th class="w-32">Actions</th>
              </tr>
            </thead>
            <tbody class="db-table-body">
              <tr v-for="approval in approvals" :key="approval.id">
                <td class="db-table-body-td">
                  <div class="font-medium">Session #{{ approval.pos_session_id }}</div>
                  <div class="text-xs text-gray-500">{{ formatDate(approval.created_at) }}</div>
                </td>
                <td class="db-table-body-td">{{ approval.pos_session_id }}</td>
                <td class="db-table-body-td">{{ approval.pos_session && approval.pos_session.shift_type ? approval.pos_session.shift_type.name : 'N/A' }}</td>
                <td class="db-table-body-td">{{ approval.pos_session && approval.pos_session.cashier ? approval.pos_session.cashier.name : 'N/A' }}</td>
                <td class="db-table-body-td">{{ approval.manager?.name || 'N/A' }}</td>
                <td class="db-table-body-td text-right">{{ formatCurrency(approval.delivered_amount) }}</td>
                <td class="db-table-body-td text-right">
                  <span v-if="approval.variance">{{ formatCurrency(approval.variance) }}</span>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="db-table-body-td text-center">
                  <span :class="{
                    'px-2 py-1 text-xs font-medium rounded-full': true,
                    'bg-yellow-100 text-yellow-800': approval.status === 'pending',
                    'bg-green-100 text-green-800': approval.status === 'approved',
                    'bg-red-100 text-red-800': approval.status === 'rejected'
                  }">{{ formatStatus(approval.status) }}</span>
                </td>
                <td class="db-table-body-td">
                  <button @click="handleAction(approval.id, 'approve')" class="db-btn bg-green-500 text-white hover:bg-green-700">Approve</button>
                  <button @click="handleAction(approval.id, 'reject')" class="db-btn bg-red-500 text-white hover:bg-red-700">Reject</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'PosApprovalList',
  
  data() {
    return {
      loading: true,
      approvals: [],
      currentPage: 1,
      perPage: 10,
      total: 0,
      totalPages: 1,
      visiblePageCount: 5,
      filters: {
        status: 'pending',
        date_from: '',
        date_to: ''
      }
    }
  },
  
  computed: {
    visiblePages() {
      const range = [];
      const half = Math.floor(this.visiblePageCount / 2);
      let start = Math.max(1, this.currentPage - half);
      let end = Math.min(this.totalPages, start + this.visiblePageCount - 1);
      
      if (end - start + 1 < this.visiblePageCount) {
        start = Math.max(1, end - this.visiblePageCount + 1);
      }
      
      for (let i = start; i <= end; i++) {
        range.push(i);
      }
      
      return range;
    }
  },
  
  watch: {
    currentPage() {
      this.fetchApprovals();
    },
    'filters.status'() {
      this.currentPage = 1;
      this.fetchApprovals();
    }
  },
  
  mounted() {
    this.fetchApprovals();
  },
  
  methods: {
    async fetchApprovals() {
      this.loading = true;
      try {
        const params = {
          page: this.currentPage,
          per_page: this.perPage,
          ...this.filters
        };
        
        const response = await axios.get('pos/approvals', { params });
        this.approvals = response.data.data || [];
        this.total = response.data.meta?.total || 0;
        this.totalPages = response.data.meta?.last_page || 1;
      } catch (error) {
        console.error('Error fetching approvals:', error);
        this.$toast.error(error.response?.data?.message || 'Failed to load approvals');
      } finally {
        this.loading = false;
      }
    },
    
    async handleAction(approvalId, action) {
      const confirmed = await this.$swal.fire({
        title: `${action === 'approve' ? 'Approve' : 'Reject'} Session?`,
        text: `Are you sure you want to ${action} this session?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'approve' ? '#28a745' : '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${action} session`,
        cancelButtonText: 'Cancel',
        reverseButtons: true
      });

      if (confirmed.isConfirmed) {
        try {
          await axios.post(`pos/approvals/${approvalId}/${action}`);
          this.$toast.success(`Session ${action}ed successfully`);
          this.fetchApprovals();
        } catch (error) {
          console.error(`Error ${action}ing approval:`, error);
          this.$toast.error(error.response?.data?.message || `Failed to ${action} session`);
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
    },
    
    formatStatus(status) {
      if (!status) return 'N/A';
      return status.charAt(0).toUpperCase() + status.slice(1);
    },
    
    resetFilters() {
      this.filters = {
        status: 'pending',
        date_from: '',
        date_to: ''
      };
      this.currentPage = 1;
      this.fetchApprovals();
    }
  }
}
</script>

<style scoped>

.db-btn-icon {
  @apply inline-flex items-center justify-center w-8 h-8 rounded-md bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary;
}

.db-btn-icon-sm {
  @apply w-7 h-7 text-sm;
}


</style>

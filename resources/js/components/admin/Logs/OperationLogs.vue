<template>
  <div class="container mt-5">
    <h3>User Operation Monitor</h3>

    <div class="mb-3">
      <input type="text" v-model="search" placeholder="Search actions..." class="form-control">
    </div>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>User</th>
          <th>Action</th>
          <th>Target</th>
          <th>Description</th>
          <th>Time</th>
          <th>Details</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="log in filteredLogs" :key="log.id">
          <td>{{ log.user ? log.user.name : 'System' }}</td>
          <td>
            <span :class="actionClass(log.action)">
              {{ log.action }}
            </span>
          </td>
          <td>
            <span v-if="log.model_type">
              {{ modelName(log.model_type) }} #{{ log.model_id }}
            </span>
          </td>
          <td>{{ log.description }}</td>
          <td>{{ formatDate(log.created_at) }}</td>
          <td>
            <button class="btn btn-sm btn-info" @click="showDetails(log)">
              <i class="fas fa-info-circle"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
      <button
        class="btn btn-sm btn-outline-primary me-2"
        :disabled="!pagination.prev_page_url"
        @click="fetchLogs(pagination.prev_page_url)"
      >
        Previous
      </button>
      <span>Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
      <button
        class="btn btn-sm btn-outline-primary ms-2"
        :disabled="!pagination.next_page_url"
        @click="fetchLogs(pagination.next_page_url)"
      >
        Next
      </button>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="logDetailsModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Operation Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div v-if="selectedLog">
              <h6>Action: {{ selectedLog.action }}</h6>
              <p class="mb-1">Performed by: {{ selectedLog.user.name }}</p>
              <p class="mb-1">At: {{ formatDate(selectedLog.created_at, true) }}</p>
              <p>IP: {{ selectedLog.ip_address }}</p>

              <div class="row" v-if="selectedLog.before || selectedLog.after">
                <div class="col-md-6" v-if="selectedLog.before">
                  <h6>Before:</h6>
                  <pre>{{ JSON.stringify(selectedLog.before, null, 2) }}</pre>
                </div>
                <div class="col-md-6" v-if="selectedLog.after">
                  <h6>After:</h6>
                  <pre>{{ JSON.stringify(selectedLog.after, null, 2) }}</pre>
                </div>
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
  data() {
    return {
      logs: [],
      pagination: {},
      search: '',
      selectedLog: null
    };
  },
  computed: {
    filteredLogs() {
      const searchTerm = this.search.toLowerCase();
      return this.logs.filter(log =>
        (log.user?.name?.toLowerCase().includes(searchTerm)) ||
        log.action.toLowerCase().includes(searchTerm) ||
        log.description.toLowerCase().includes(searchTerm)
      );
    }
  },
  mounted() {
    this.fetchLogs();
  },
  methods: {
    async fetchLogs(url = '/api/operation-logs') {
      const response = await axios.get(url);
      this.logs = response.data.data;
      this.pagination = {
        current_page: response.data.current_page,
        last_page: response.data.last_page,
        next_page_url: response.data.next_page_url,
        prev_page_url: response.data.prev_page_url
      };
    },
    actionClass(action) {
      return {
        'badge bg-danger': ['delete', 'force_logout'].includes(action),
        'badge bg-warning': action === 'update',
        'badge bg-success': action === 'create',
        'badge bg-info': ['login', 'logout'].includes(action)
      };
    },
    modelName(type) {
      return type.split('\\').pop();
    },
    formatDate(date, full = false) {
      const options = full
        ? { dateStyle: 'medium', timeStyle: 'medium' }
        : { timeStyle: 'short' };
      return new Date(date).toLocaleString(undefined, options);
    },
    showDetails(log) {
      this.selectedLog = log;
      new bootstrap.Modal(document.getElementById('logDetailsModal')).show();
    }
  }
};
</script>

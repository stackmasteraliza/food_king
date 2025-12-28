<template>
  <div class="p-4">
    <h2 class="text-xl font-semibold mb-4">Session Reports</h2>

    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
      <div>
        <label class="block text-sm mb-1">Cashier</label>
        <select v-model="filters.cashier_id" class="form-input w-full">
          <option value="">All</option>
          <option v-for="user in cashiers" :key="user.id" :value="user.id">{{ user.name }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm mb-1">Shift Type</label>
        <select v-model="filters.shift_type_id" class="form-input w-full">
          <option value="">All</option>
          <option v-for="st in shiftTypes" :key="st.id" :value="st.id">{{ st.name }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm mb-1">From</label>
        <input type="date" v-model="filters.from" class="form-input w-full" />
      </div>
      <div>
        <label class="block text-sm mb-1">To</label>
        <input type="date" v-model="filters.to" class="form-input w-full" />
      </div>
    </div>

    <div class="mb-4 flex gap-2">
      <button class="btn btn-primary" @click="fetchSessions">Apply</button>
      <button class="btn btn-secondary" @click="downloadCsv">Download CSV</button>
    </div>

    <!-- Table -->
    <div v-if="loading" class="text-center py-8">Loading...</div>
    <table v-else class="table-auto w-full text-sm">
      <thead>
        <tr class="bg-gray-100">
          <th class="px-2 py-1">ID</th>
          <th class="px-2 py-1">Shift</th>
          <th class="px-2 py-1">Cashier</th>
          <th class="px-2 py-1">Device</th>
          <th class="px-2 py-1">Start</th>
          <th class="px-2 py-1">End</th>
          <th class="px-2 py-1">Sales</th>
          <th class="px-2 py-1">Refunds</th>
          <th class="px-2 py-1">Expected</th>
          <th class="px-2 py-1">Actual</th>
          <th class="px-2 py-1">Status</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="s in sessions.data" :key="s.id" class="border-b">
          <td class="px-2 py-1">{{ s.id }}</td>
          <td class="px-2 py-1">{{ s.shift_type?.name }}</td>
          <td class="px-2 py-1">{{ s.cashier?.name }}</td>
          <td class="px-2 py-1">{{ s.device_id }}</td>
          <td class="px-2 py-1">{{ s.start_time }}</td>
          <td class="px-2 py-1">{{ s.end_time }}</td>
          <td class="px-2 py-1">{{ s.total_sales }}</td>
          <td class="px-2 py-1">{{ s.total_refunds }}</td>
          <td class="px-2 py-1">{{ s.cash_expected }}</td>
          <td class="px-2 py-1">{{ s.cash_actual }}</td>
          <td class="px-2 py-1">{{ s.status }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div v-if="sessions.meta" class="mt-4 flex gap-2">
      <button class="btn btn-sm" :disabled="!sessions.links.prev" @click="changePage(sessions.meta.current_page - 1)">Prev</button>
      <span>Page {{ sessions.meta.current_page }} / {{ sessions.meta.last_page }}</span>
      <button class="btn btn-sm" :disabled="!sessions.links.next" @click="changePage(sessions.meta.current_page + 1)">Next</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'PosSessionReports',
  data() {
    return {
      sessions: { data: [] },
      loading: false,
      cashiers: [],
      shiftTypes: [],
      filters: {
        cashier_id: '',
        shift_type_id: '',
        from: '',
        to: '',
        page: 1,
      },
    }
  },
  created() {
    this.loadLookups()
    this.fetchSessions()
  },
  methods: {
    async loadLookups() {
      try {
        const [cashiersRes, shiftTypeRes] = await Promise.all([
          axios.get('users?role=cashier'),
          axios.get('pos/shift-types'),
        ])
        this.cashiers = cashiersRes.data
        this.shiftTypes = shiftTypeRes.data
      } catch (e) {
        console.error(e)
      }
    },
    async fetchSessions() {
      this.loading = true
      try {
        const res = await axios.get('pos/sessions/report', { params: this.filters })
        this.sessions = res.data
      } catch (e) {
        console.error(e)
      } finally {
        this.loading = false
      }
    },
    changePage(page) {
      this.filters.page = page
      this.fetchSessions()
    },
    downloadCsv() {
      const params = new URLSearchParams(this.filters).toString()
      window.open(`/pos/sessions/export?${params}`, '_blank')
    },
  },
}
</script>

<style scoped>
.table-auto th, .table-auto td { text-align: left; }
</style>

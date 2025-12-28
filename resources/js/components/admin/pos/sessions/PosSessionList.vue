<template>
    <div class="container py-6">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">POS Sessions</h3>
                <div class="db-card-filter">
                    <router-link :to="{ name: 'pos.start' }" class="db-btn bg-primary text-white">
                        <i class="lab lab-add"></i>
                        <span>Start New Session</span>
                    </router-link>
                </div>
            </div>

            <div class="db-table-responsive">
                <table class="db-table stripe">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">ID</th>
                            <th class="db-table-head-th">Shift Type</th>
                            <th class="db-table-head-th">Cashier</th>
                            <th class="db-table-head-th">Start Time</th>
                            <th class="db-table-head-th">End Time</th>
                            <th class="db-table-head-th">Status</th>
                            <th class="db-table-head-th">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="sessions.length">
                        <tr class="db-table-body-tr" v-for="session in sessions" :key="session.id">
                            <td class="db-table-body-td">{{ session.id }}</td>
                            <td class="db-table-body-td">{{ session.shift_type ? session.shift_type.name : 'N/A' }}</td>
                            <td class="db-table-body-td">{{ session.cashier ? session.cashier.name : 'N/A' }}</td>
                            <td class="db-table-body-td">{{ session.start_time }}</td>
                            <td class="db-table-body-td">{{ session.end_time || 'N/A' }}</td>
                            <td class="db-table-body-td capitalize">{{ capitalize(session.status) }}</td>
                            <td class="db-table-body-td">
                                <router-link :to="{ name: 'pos.summary', params: { id: session.id } }" class="db-btn bg-primary-light text-primary">
                                    <i class="lab lab-eye"></i>
                                    <span>View Summary</span>
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                    <tbody class="db-table-body" v-else>
                        <tr class="db-table-body-tr">
                            <td class="db-table-body-td text-center" colspan="7">
                                <span>No sessions found.</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'PosSessionList',
  data() {
    return {
      sessions: []
    }
  },
  mounted() {
    this.fetchSessions();
  },
  methods: {
    async fetchSessions() {
      try {
        const response = await axios.get('pos/sessions');
        this.sessions = response.data;
      } catch (error) {
        console.error('Error fetching sessions:', error);
      }
    },
    capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }
  }
}
</script>

<style scoped lang="postcss">

</style>

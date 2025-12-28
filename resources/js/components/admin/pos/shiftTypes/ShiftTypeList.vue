<template>
  <div class="container py-6">
    <div class="bg-white rounded-lg shadow">
      <div class="border-b px-6 py-4 bg-primary-light">
        <h2 class="text-lg font-semibold text-heading">Shift Types</h2>
      </div>
      <div class="p-6">
    <h1 class="text-xl font-semibold mb-4">Shift Types</h1>
    <router-link :to="{ name: 'shift-types.create' }" class="db-btn bg-primary text-white mb-4">Create New Shift Type</router-link>

    <!-- Optional message when no data -->
    <div v-if="!shiftTypes.length" class="text-gray-600 mb-4">No shift types found or still loading...</div>

    <table class="db-table stripe" v-if="shiftTypes.length">
      <thead class="db-table-head">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Description</th>
          <th>Parent Shift</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="db-table-body">
        <tr v-for="shiftType in shiftTypes" :key="shiftType.id">
          <td>{{ shiftType.id }}</td>
          <td>{{ shiftType.name }}</td>
          <td>{{ shiftType.description || 'N/A' }}</td>
          <td>{{ shiftType.parent_shift ? shiftType.parent_shift.name : 'None' }}</td>
          <td>
            <router-link
              :to="{ name: 'shift-types.edit', params: { id: shiftType.id } }"
              class="db-btn bg-warning text-white btn-xs"
            >Edit</router-link>
            <button
              @click="deleteShiftType(shiftType.id)"
              class="db-btn bg-danger text-white btn-xs"
              :disabled="isDeleting"
            >Delete</button>
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
  name: 'ShiftTypeList',
  data() {
    return {
      shiftTypes: [],
      isDeleting: false
    };
  },
  mounted() {
    this.fetchShiftTypes();
  },
  beforeRouteEnter(to, from, next) {
    next(vm => vm.fetchShiftTypes());
  },
  beforeRouteUpdate(to, from, next) {
    this.fetchShiftTypes();
    next();
  },
  methods: {
    async fetchShiftTypes() {
      try {
        const response = await axios.get('pos/shift-types');
        console.log('✅ API Response:', response.data);

        this.shiftTypes = Array.isArray(response.data)
          ? response.data
          : (response.data.data || []);

        if (!this.shiftTypes.length) {
          console.warn('⚠️ No shift types returned from API');
        }
      } catch (error) {
        console.error(
          '❌ Error fetching shift types:',
          error.response?.data || error.message
        );
        alert(JSON.stringify(error.response?.data || error.message));
        this.$toast.error('Failed to load shift types.');
      }
    },

    async deleteShiftType(id) {
      if (!confirm('Are you sure?')) return;
      this.isDeleting = true;
      try {
        await axios.delete(`pos/shift-types/${id}`);
        this.shiftTypes = this.shiftTypes.filter(shift => shift.id !== id);
        this.$toast.success('Shift type deleted successfully.');
      } catch (error) {
        console.error(
          '❌ Error deleting shift type:',
          error.response?.data || error.message
        );
        this.$toast.error('Failed to delete shift type.');
      } finally {
        this.isDeleting = false;
      }
    }
  }
};
</script>

<style scoped>
/* keep minimal helpers */
/* existing local btn helpers retained */
.db-btn {
  padding: 6px 12px;
  font-size: 0.875rem;
  border-radius: 4px;
  text-decoration: none;
  display: inline-block;
  margin-right: 6px;
}
.bg-primary {
  background-color: #007bff;
}
.bg-warning {
  background-color: #ffc107;
}
.bg-danger {
  background-color: #dc3545;
}
.text-white {
  color: white;
}
.btn-xs {
  font-size: 0.75rem;
  padding: 4px 8px;
}
.db-table {
  width: 100%;
  border-collapse: collapse;
}
.db-table th,
.db-table td {
  padding: 0.75rem;
  border: 1px solid #ccc;
}
.db-table-head {
  background-color: #f1f1f1;
  font-weight: bold;
}
</style>

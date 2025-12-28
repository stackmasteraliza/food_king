<template>
  <div class="container py-6">
    <div class="bg-white rounded-lg shadow">
      <div class="border-b px-6 py-4 bg-primary-light">
        <h2 class="text-lg font-semibold text-heading">Edit Shift Type</h2>
      </div>
      <div class="p-6">
        <form @submit.prevent="updateShiftType">
      <div class="form-group">
        <label for="name">Name</label>
        <input v-model="form.name" type="text" id="name" class="db-field-control" :class="{ 'is-invalid': errors.name }" required>
        <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</div>
      </div>
      <div class="form-group">
        <label for="description">Description</label>
        <textarea v-model="form.description" id="description" class="db-field-control" :class="{ 'is-invalid': errors.description }"></textarea>
        <div v-if="errors.description" class="invalid-feedback">{{ errors.description[0] }}</div>
      </div>
      <div class="form-group">
        <label for="parent_shift_id">Parent Shift (Optional)</label>
        <select v-model="form.parent_shift_id" id="parent_shift_id" class="db-field-control" :class="{ 'is-invalid': errors.parent_shift_id }">
          <option value="">None</option>
          <option v-for="shift in parentShifts" :key="shift.id" :value="shift.id">{{ shift.name }}</option>
        </select>
        <div v-if="errors.parent_shift_id" class="invalid-feedback">{{ errors.parent_shift_id[0] }}</div>
      </div>
      <div class="form-group">
        <label for="image">Image/Icon URL (Optional)</label>
        <input v-model="form.image" type="text" id="image" class="db-field-control" :class="{ 'is-invalid': errors.image }">
        <div v-if="errors.image" class="invalid-feedback">{{ errors.image[0] }}</div>
      </div>
      <div class="flex justify-end mt-6">
        <button type="submit" class="db-btn bg-primary text-white" :disabled="isSubmitting">
          {{ isSubmitting ? 'Updating...' : 'Update Shift Type' }}
        </button>
      </div>
            </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'ShiftTypeEdit',
  data() {
    return {
      parentShifts: [],
      form: {
        name: '',
        description: '',
        parent_shift_id: '',
        image: ''
      },
      errors: {}
    }
  },
  mounted() {
    this.fetchParentShifts();
    this.fetchShiftType();
  },
  methods: {
    async fetchParentShifts() {
      try {
        const response = await axios.get('pos/shift-types');
        this.parentShifts = response.data.data ?? response.data;
      } catch (error) {
        alert(error);
        console.error('Error fetching parent shifts:', error);
      }
    },
    async fetchShiftType() {
      try {
        const response = await axios.get(`pos/shift-types/${this.$route.params.id}`);
        this.form = response.data.data ?? response.data;
      } catch (error) {
        alert(error);
        console.error('Error fetching shift type:', error);
        this.$toast.error('Failed to load shift type.');
      }
    },
    async updateShiftType() {
      this.errors = {};
      try {
        await axios.put(`pos/shift-types/${this.$route.params.id}`, this.form);
        this.$router.push({ name: 'shift-types.index' });
        this.$toast.success('Shift type updated successfully.');
      } catch (error) {
        if (error.response && error.response.data.errors) {
          this.errors = error.response.data.errors;
        }
        console.error('Error updating shift type:', error);
        this.$toast.error('Failed to update shift type.');
      }
    }
  }
}
</script>

<style scoped>
</style>

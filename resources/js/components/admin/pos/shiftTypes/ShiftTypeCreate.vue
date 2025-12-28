<template>
  <div class="container py-6">
    <div class="bg-white rounded-lg shadow">
      <div class="border-b px-6 py-4 bg-primary-light">
        <h2 class="text-lg font-semibold text-heading">Create Shift Type</h2>
      </div>
      <div class="p-6">
        <form @submit.prevent="createShiftType">
      <div class="mb-4">
        <label for="name" class="db-field-title required">Name</label>
        <input v-model="form.name" type="text" id="name" class="db-field-control" :class="{ 'is-invalid': errors.name }" required>
        <div v-if="errors.name" class="db-field-alert text-red-500">{{ errors.name[0] }}</div>
      </div>
      <div class="mb-4">
        <label for="description">Description</label>
        <textarea v-model="form.description" id="description" class="db-field-control" :class="{ 'is-invalid': errors.description }"></textarea>
        <div v-if="errors.description" class="db-field-alert text-red-500">{{ errors.description[0] }}</div>
      </div>
      <div class="mb-4">
        <label for="parent_shift_id">Parent Shift (Optional)</label>
        <select v-model="form.parent_shift_id" id="parent_shift_id" class="db-field-control" :class="{ 'is-invalid': errors.parent_shift_id }">
          <option value="">None</option>
          <option v-for="shift in parentShifts" :key="shift.id" :value="shift.id">{{ shift.name }}</option>
        </select>
        <div v-if="errors.parent_shift_id" class="db-field-alert text-red-500">{{ errors.parent_shift_id[0] }}</div>
      </div>
      <div class="mb-4">
        <label for="image">Image/Icon URL (Optional)</label>
        <input v-model="form.image" type="text" id="image" class="db-field-control" :class="{ 'is-invalid': errors.image }">
        <div v-if="errors.image" class="db-field-alert text-red-500">{{ errors.image[0] }}</div>
      </div>
      <div class="flex justify-end mt-6"><button type="submit" class="db-btn bg-primary text-white" :disabled="isSubmitting">{{ isSubmitting ? 'Creating...' : 'Create Shift Type' }}</button></div>
            </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'ShiftTypeCreate',
  data() {
    return {
      parentShifts: [],
      form: {
        name: '',
        description: '',
        parent_shift_id: '',
        image: ''
      },
      errors: {},
      isSubmitting: false
    }
  },
  mounted() {
    this.fetchParentShifts();
  },
  methods: {
    async fetchParentShifts() {
      try {
        const response = await axios.get('pos/shift-types');
        this.parentShifts = response.data.data ?? response.data;
      } catch (error) {
        console.error('Error fetching parent shifts:', error);
      }
    },
    async createShiftType() {
      this.errors = {};
      this.isSubmitting = true;
      try {
        await axios.post('pos/shift-types', this.form);
        this.$router.push({ name: 'shift-types.index' });
        this.$toast.success('Shift type created successfully.');
      } catch (error) {
        if (error.response && error.response.data.errors) {
          this.errors = error.response.data.errors;
        }
        console.error('Error creating shift type:', error);
        this.$toast.error('Failed to create shift type.');
      } finally {
        this.isSubmitting = false;
      }
    }
  }
}
</script>


/* old bootstrap-like helpers removed because we use db-field-control etc.*/
/* .form-group {
  margin-bottom: 1rem;
}
.form-control {
  display: block;
  width: 100%;
  height: calc(1.5em + 0.75rem + 2px);
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.is-invalid {
  border-color: #dc3545;
}
.invalid-feedback {
  display: none;
  width: 100%;
  margin-top: 0.25rem;
  font-size: 0.875rem;
  color: #dc3545;
}
.is-invalid ~ .invalid-feedback {
  display: block;
}
.btn-primary {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
}
.mt-3 {
  margin-top: 1rem;
}


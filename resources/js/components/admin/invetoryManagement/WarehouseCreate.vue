<template>
  <div class="p-6 bg-white rounded-xl shadow-xl space-y-6 max-w-xl mx-auto">
    <h2 class="text-2xl font-bold text-indigo-700">➕ إضافة مستودع جديد</h2>

    <form @submit.prevent="createWarehouse" class="space-y-4">
      <div>
        <label class="block font-medium">الاسم</label>
        <input v-model="form.name" required class="form-input w-full border-gray-300 rounded-md" />
      </div>

      <div>
        <label class="block font-medium">الموقع</label>
        <input v-model="form.location" class="form-input w-full border-gray-300 rounded-md" />
      </div>

      <div>
        <label class="block font-medium">الحالة</label>
        <select v-model="form.status" class="form-select w-full border-gray-300 rounded-md">
          <option value="active">نشط</option>
          <option value="inactive">غير نشط</option>
        </select>
      </div>

      <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md">
        حفظ
      </button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        name: '',
        location: '',
        status: 'active',
      }
    };
  },
  methods: {
    createWarehouse() {
      axios.post('/api/warehouses', this.form).then(() => {
        this.$router.push('/warehouses');
      });
    }
  }
};
</script>

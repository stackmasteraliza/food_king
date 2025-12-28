<template>
  <div class="p-6 bg-white rounded-xl shadow-xl space-y-6">
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-bold text-indigo-700">📦 قائمة المستودعات</h2>
      <router-link to="/warehouses/create" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
        ➕ مستودع جديد
      </router-link>
    </div>

    <div class="overflow-x-auto border rounded-lg shadow">
      <table class="min-w-full table-auto text-sm">
        <thead class="bg-indigo-100 text-indigo-800">
          <tr>
            <th class="px-4 py-2 border">الاسم</th>
            <th class="px-4 py-2 border">الموقع</th>
            <th class="px-4 py-2 border">الحالة</th>
            <th class="px-4 py-2 border">الإجراءات</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y">
          <tr v-for="w in warehouses" :key="w.id">
            <td class="px-4 py-2">{{ w.name }}</td>
            <td class="px-4 py-2">{{ w.location }}</td>
            <td class="px-4 py-2">
              <span :class="w.status === 'active' ? 'text-green-600' : 'text-gray-500'">
                {{ w.status === 'active' ? 'نشط' : 'غير نشط' }}
              </span>
            </td>
            <td class="px-4 py-2 space-x-2 space-x-reverse">
              <router-link :to="`/warehouses/${w.id}`" class="text-blue-600 hover:underline">عرض</router-link>
              <router-link :to="`/warehouses/${w.id}/edit`" class="text-indigo-600 hover:underline">تعديل</router-link>
              <button @click="deleteWarehouse(w.id)" class="text-red-600 hover:underline">حذف</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      warehouses: [],
    };
  },
  mounted() {
    this.fetchWarehouses();
  },
  methods: {
    fetchWarehouses() {
      axios.get('/api/warehouses').then(res => (this.warehouses = res.data));
    },
    deleteWarehouse(id) {
      if (confirm('هل أنت متأكد من الحذف؟')) {
        axios.delete(`/api/warehouses/${id}`).then(() => this.fetchWarehouses());
      }
    }
  }
};
</script>

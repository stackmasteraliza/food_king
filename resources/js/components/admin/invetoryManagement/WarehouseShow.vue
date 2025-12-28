<template>
  <div class="p-6 bg-white rounded-xl shadow-xl max-w-xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold text-indigo-700">📄 تفاصيل المستودع</h2>

    <div class="space-y-2">
      <div><strong>الاسم:</strong> {{ warehouse.name }}</div>
      <div><strong>الموقع:</strong> {{ warehouse.location }}</div>
      <div>
        <strong>الحالة:</strong>
        <span :class="warehouse.status === 'active' ? 'text-green-600' : 'text-gray-500'">
          {{ warehouse.status === 'active' ? 'نشط' : 'غير نشط' }}
        </span>
      </div>
    </div>

    <router-link to="/warehouses" class="text-indigo-600 hover:underline">← الرجوع إلى القائمة</router-link>
  </div>
</template>

<script>
export default {
  data() {
    return {
      warehouse: {},
    };
  },
  mounted() {
    const id = this.$route.params.id;
    axios.get(`/api/warehouses/${id}`).then(res => (this.warehouse = res.data));
  }
};
</script>

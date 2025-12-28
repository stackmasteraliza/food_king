<template>
  <div
    class="p-6 bg-gradient-to-br from-gray-50 to-white shadow-xl rounded-xl space-y-10"
  >
    <!-- Stock Balances -->
    <section>
      <h2 class="text-2xl font-bold mb-4 text-blue-700 flex items-center gap-2">
        📦 رصيد المخزون الحالي
      </h2>
      <div class="overflow-x-auto rounded-lg border border-gray-200 shadow">
        <table class="min-w-full table-auto text-sm">
          <thead class="bg-blue-100 text-blue-800">
            <tr>
              <th class="px-4 py-2 border">الصنف</th>
              <th class="px-4 py-2 border">المخزن</th>
              <th class="px-4 py-2 border">الكمية</th>
              <th class="px-4 py-2 border">التكلفة المتوسطة</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y">
            <tr
              v-for="row in balances"
              :key="`${row.item_id}-${row.warehouse_id}`"
              class="hover:bg-blue-50 transition"
            >
              <td class="px-4 py-2 text-gray-700">{{ row.item_name }}</td>
              <td class="px-4 py-2 text-gray-700">{{ row.warehouse_name }}</td>
              <td class="px-4 py-2 text-right">{{ row.quantity }}</td>
              <td class="px-4 py-2 text-right">{{ row.avg_cost }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Movement History -->
    <section>
      <h2
        class="text-2xl font-bold mb-4 text-green-700 flex items-center gap-2"
      >
        🔄 تاريخ حركات المخزون
      </h2>

      <!-- Filters -->
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 items-end">
        <select
          v-model="filter.warehouse_id"
          class="form-select border-gray-300 rounded-md"
        >
          <option value="">كل المخازن</option>
          <option v-for="w in warehouses" :key="w.id" :value="w.id">
            {{ w.name }}
          </option>
        </select>

        <select
          v-model="filter.item_id"
          class="form-select border-gray-300 rounded-md"
        >
          <option value="">كل الأصناف</option>
          <option v-for="i in items" :key="i.id" :value="i.id">
            {{ i.name_ar }}
          </option>
        </select>

        <input
          type="date"
          v-model="filter.date_from"
          class="form-input border-gray-300 rounded-md"
        />
        <input
          type="date"
          v-model="filter.date_to"
          class="form-input border-gray-300 rounded-md"
        />
        <button
          @click="fetchHistory"
          class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md transition"
        >
          تصفية
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto rounded-lg border border-gray-200 shadow">
        <table class="min-w-full table-auto text-sm">
          <thead class="bg-green-100 text-green-800">
            <tr>
              <th class="px-4 py-2 border">التاريخ</th>
              <th class="px-4 py-2 border">النوع</th>
              <th class="px-4 py-2 border">الصنف</th>
              <th class="px-4 py-2 border">المخزن</th>
              <th class="px-4 py-2 border">الكمية</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y">
            <tr
              v-for="(m, idx) in history"
              :key="idx"
              class="hover:bg-green-50 transition"
            >
              <td class="px-4 py-2">{{ m.date }}</td>
              <td class="px-4 py-2">{{ m.type }}</td>
              <td class="px-4 py-2">{{ itemMap[m.item_id] }}</td>
              <td class="px-4 py-2">{{ warehouseMap[m.warehouse_id] }}</td>
              <td class="px-4 py-2 text-right">{{ m.quantity }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>
</template>


<script>
export default {
  name: "InventoryReport",
  data() {
    return {
      balances: [],
      history: [],
      warehouses: [],
      items: [],
      warehouseMap: {},
      itemMap: {},
      filter: {
        warehouse_id: "",
        item_id: "",
        date_from: "",
        date_to: "",
      },
    };
  },
  mounted() {
    // Initialize data
    axios
      .get("/admin/InventoryReport/stock-balances")
      .then((res) => (this.balances = res.data));

    axios.get("/admin/warehouses").then((res) => {
      this.warehouses = res.data;
      res.data.forEach((w) => (this.warehouseMap[w.id] = w.name));
    });

    axios.get("/admin/items").then((res) => {
      this.items = res.data;
      res.data.forEach((i) => (this.itemMap[i.id] = i.name_ar));
    });

    // Default date range: today
    const today = new Date().toISOString().substr(0, 10);
    this.filter.date_from = today;
    this.filter.date_to = today;
    this.fetchHistory();
  },
  methods: {
    fetchHistory() {
      const params = {
        ...this.filter,
      };
      axios
        .get("/admin/InventoryReport/movement-history", { params })
        .then((res) => (this.history = res.data))
        .catch(() => alert("خطأ بجلب بيانات الحركات"));
    },
  },
};
</script>

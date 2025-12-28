<template>
  <div
    class="p-6 bg-gradient-to-br from-yellow-50 to-white shadow-xl rounded-xl space-y-6"
  >
    <h2 class="text-2xl font-bold text-yellow-700 flex items-center gap-2 mb-4">
      📊 إدخال رصيد افتتاحي لصنف
    </h2>

    <form @submit.prevent="submitForm" class="space-y-4">
      <!-- Warehouse & Item -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1 font-medium text-gray-700">المخزن</label>
          <select
            v-model="form.warehouse_id"
            class="form-select w-full border-gray-300 rounded-md"
          >
            <option value="" disabled>اختر مخزناً</option>
            <option v-for="w in warehouses" :key="w.id" :value="w.id">
              {{ w.name }}
            </option>
          </select>
        </div>

        <div>
          <label class="block mb-1 font-medium text-gray-700">الصنف</label>
          <select
            v-model="form.item_id"
            class="form-select w-full border-gray-300 rounded-md"
          >
            <option value="" disabled>اختر صنفاً</option>
            <option v-for="i in items" :key="i.id" :value="i.id">
              {{ i.name_ar }}
            </option>
          </select>
        </div>
      </div>

      <!-- Quantity and Cost -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1 font-medium text-gray-700">الكمية</label>
          <input
            type="number"
            v-model.number="form.quantity"
            min="0"
            step="0.01"
            class="form-input w-full border-gray-300 rounded-md"
          />
        </div>
        <div>
          <label class="block mb-1 font-medium text-gray-700"
            >التكلفة للوحدة</label
          >
          <input
            type="number"
            v-model.number="form.cost"
            min="0"
            step="0.01"
            class="form-input w-full border-gray-300 rounded-md"
          />
        </div>
      </div>

      <!-- Date -->
      <div>
        <label class="block mb-1 font-medium text-gray-700">التاريخ</label>
        <input
          type="date"
          v-model="form.date"
          class="form-input w-full border-gray-300 rounded-md"
        />
      </div>

      <!-- Submit -->
      <button
        type="submit"
        class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-md font-semibold"
      >
        حفظ الرصيد الافتتاحي
      </button>
    </form>
  </div>
</template>
<script>
export default {
  name: "OpeningBalanceForm",
  data() {
    return {
      warehouses: [],
      items: [],
      form: {
        warehouse_id: "",
        item_id: "",
        quantity: "",
        cost: "",
        date: "",
      },
    };
  },
  mounted() {
    this.fetchOptions();
  },
  methods: {
    fetchOptions() {
      axios
        .get("/admin/warehouses")
        .then((res) => (this.warehouses = res.data));
      axios.get("/admin/items").then((res) => (this.items = res.data));
      this.form.date = new Date().toISOString().substr(0, 10);
    },
    submitForm() {
      axios
        .post("/admin/OpeningBalanceForm", this.form)
        .then(() => {
          alert("تم الحفظ بنجاح");
          this.form = {
            warehouse_id: "",
            item_id: "",
            quantity: "",
            cost: "",
            date: new Date().toISOString().substr(0, 10),
          };
        })
        .catch((err) => alert("حدث خطأ: " + err.response.data.message));
    },
  },
};
</script>

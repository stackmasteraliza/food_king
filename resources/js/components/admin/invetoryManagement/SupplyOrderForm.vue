<template>
  <div
    class="p-6 bg-gradient-to-br from-gray-50 to-white shadow-xl rounded-xl space-y-6"
  >
    <h2 class="text-2xl font-bold text-blue-700 flex items-center gap-2 mb-4">
      🧾 إنشاء أمر توريد مخزن
    </h2>

    <form @submit.prevent="submitForm" class="space-y-4">
      <!-- Warehouse & Source -->
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
          <label class="block mb-1 font-medium text-gray-700">المصدر</label>
          <input
            type="text"
            v-model="form.source"
            class="form-input w-full border-gray-300 rounded-md"
            placeholder="اختياري"
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

      <!-- Line Items -->
      <div>
        <label class="block mb-2 font-bold text-gray-700">العناصر</label>
        <div
          v-for="(line, idx) in form.items"
          :key="idx"
          class="grid grid-cols-12 gap-2 items-center mb-2"
        >
          <select
            v-model="line.item_id"
            class="form-select col-span-4 border-gray-300 rounded-md"
          >
            <option value="" disabled>اختر صنفاً</option>
            <option v-for="i in items" :key="i.id" :value="i.id">
              {{ i.name_ar }}
            </option>
          </select>
          <input
            type="number"
            v-model.number="line.quantity"
            class="form-input col-span-2 border-gray-300 rounded-md"
            placeholder="الكمية"
          />
          <input
            type="text"
            v-model="line.note"
            class="form-input col-span-5 border-gray-300 rounded-md"
            placeholder="ملاحظة (اختياري)"
          />
          <button
            type="button"
            @click="removeLine(idx)"
            class="text-red-600 hover:text-red-800 text-lg font-bold"
          >
            ×
          </button>
        </div>
        <button
          type="button"
          @click="addLine"
          class="text-blue-600 hover:text-blue-800 font-medium mt-1"
        >
          + إضافة صف جديد
        </button>
      </div>

      <!-- Submit -->
      <button
        type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-semibold"
      >
        حفظ أمر التوريد
      </button>
    </form>
  </div>
</template>


<script>
export default {
  name: "SupplyOrderForm",
  data() {
    return {
      warehouses: [],
      items: [],
      form: {
        warehouse_id: "",
        source: "",
        date: "",
        items: [{ item_id: "", quantity: 1, note: "" }],
      },
    };
  },
  mounted() {
    axios.get("/admin/warehouses").then((res) => (this.warehouses = res.data));
    axios.get("/admin/items").then((res) => (this.items = res.data));
    this.form.date = new Date().toISOString().substr(0, 10);
  },
  methods: {
    addLine() {
      this.form.items.push({ item_id: "", quantity: 1, note: "" });
    },
    removeLine(index) {
      this.form.items.splice(index, 1);
    },
    submitForm() {
      axios
        .post("/admin/SupplyOrderForm", this.form)
        .then(() => {
          alert("تم حفظ أمر التوريد بنجاح");
          // Reset form
          this.form.warehouse_id = "";
          this.form.source = "";
          this.form.date = new Date().toISOString().substr(0, 10);
          this.form.items = [{ item_id: "", quantity: 1, note: "" }];
        })
        .catch((err) => {
          const msg = err.response?.data?.message || "حدث خطأ";
          alert(msg);
        });
    },
  },
};
</script>

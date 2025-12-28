<template>
  <div class="p-4">
    <h2 class="text-2xl font-bold mb-4 text-right">سجل التعديلات والحذف</h2>

    <!-- 🔍 الفلاتر -->
    <div class="flex flex-wrap gap-4 mb-4 justify-end">
      <select v-model="filters.event" class="border p-2 rounded">
        <option value="">الكل (عملية)</option>
        <option value="updated">تعديل</option>
        <option value="deleted">حذف</option>
      </select>

      <input
        v-model="filters.model"
        type="text"
        placeholder="اسم الموديل"
        class="border p-2 rounded text-right"
      />
      <input
        v-model="filters.user"
        type="text"
        placeholder="اسم المستخدم"
        class="border p-2 rounded text-right"
      />

      <button
        @click="fetchLogs"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        تطبيق الفلاتر
      </button>
    </div>

    <!-- 📊 الجدول -->
    <div class="overflow-auto">
      <table class="min-w-full border text-sm text-right">
        <thead class="bg-gray-200">
          <tr>
            <th class="border px-3 py-2">#</th>
            <th class="border px-3 py-2">العملية</th>
            <th class="border px-3 py-2">الموديل</th>
            <th class="border px-3 py-2">المستخدم</th>
            <th class="border px-3 py-2">القديم</th>
            <th class="border px-3 py-2">الجديد</th>
            <th class="border px-3 py-2">التاريخ</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(log, index) in logs.data"
            :key="log.id"
            class="hover:bg-gray-100"
          >
            <td class="border px-3 py-1">{{ index + 1 }}</td>
            <td class="border px-3 py-1">{{ log.event }}</td>
            <td class="border px-3 py-1">
              {{ modelName(log.auditable_type) }}
            </td>
            <td class="border px-3 py-1">{{ log.user?.name || "نظام" }}</td>
            <td class="border px-3 py-1">
              <details>
                <summary class="cursor-pointer text-blue-600">عرض</summary>
                <pre class="bg-gray-100 p-2 rounded">{{
                  format(log.old_values)
                }}</pre>
              </details>
            </td>
            <td class="border px-3 py-1">
              <details>
                <summary class="cursor-pointer text-green-600">عرض</summary>
                <pre class="bg-gray-100 p-2 rounded">{{
                  format(log.new_values)
                }}</pre>
              </details>
            </td>
            <td class="border px-3 py-1">{{ formatDate(log.created_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- 📄 Pagination -->
    <div class="mt-4 flex justify-between items-center text-sm">
      <span>صفحة {{ logs.current_page }} من {{ logs.last_page }}</span>

      <div class="space-x-2">
        <button
          @click="goToPage(logs.current_page - 1)"
          :disabled="logs.current_page === 1"
          class="px-3 py-1 border rounded"
        >
          السابق
        </button>

        <button
          @click="goToPage(logs.current_page + 1)"
          :disabled="logs.current_page === logs.last_page"
          class="px-3 py-1 border rounded"
        >
          التالي
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      logs: { data: [] },
      filters: {
        event: "",
        model: "",
        user: "",
        page: 1,
      },
    };
  },
  mounted() {
    this.fetchLogs();
  },
  methods: {
    fetchLogs() {
      const params = new URLSearchParams(this.filters).toString();
      fetch(`/api/audit-logs?${params}`)
        .then((res) => res.json())
        .then((data) => (this.logs = data));
    },
    format(obj) {
      return JSON.stringify(obj, null, 2);
    },
    formatDate(date) {
      return new Date(date).toLocaleString("ar-EG");
    },
    goToPage(page) {
      this.filters.page = page;
      this.fetchLogs();
    },
    modelName(className) {
      return className.split("\\").pop();
    },
  },
};
</script>

<style scoped>
table {
  direction: rtl;
  font-family: "Cairo", sans-serif;
}
</style>

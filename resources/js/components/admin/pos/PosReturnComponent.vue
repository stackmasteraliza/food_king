<template>
  <div class="sales-return-container">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h4>شاشة مرتجع المبيعات</h4>
      </div>

      <div class="card-body">
        <!-- بحث عن الفاتورة -->
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="form-group">
              <label>رقم فاتورة البيع</label>
              <div class="input-group">
                <input
                  type="text"
                  class="form-control"
                  v-model="invoiceNumber"
                  @keyup.enter="searchInvoice"
                  placeholder="أدخل رقم الفاتورة"
                />
                <div class="input-group-append">
                  <button class="btn btn-primary" @click="searchInvoice">
                    <i class="fas fa-search"></i> بحث
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- تفاصيل الفاتورة -->
        <div class="invoice-details" v-if="invoice">
          <div class="row mb-3">
            <div class="col-md-4">
              <p><strong>رقم الفاتورة:</strong> {{ invoice.invoice_number }}</p>
            </div>
            <div class="col-md-4">
              <p><strong>التاريخ:</strong> {{ invoice.date }}</p>
            </div>
            <div class="col-md-4">
              <p><strong>العميل:</strong> {{ invoice.customer_name }}</p>
            </div>
          </div>

          <!-- جدول الأصناف -->
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead class="bg-light">
                <tr>
                  <th width="5%">#</th>
                  <th width="10%">كود الصنف</th>
                  <th>اسم الصنف</th>
                  <th width="10%">الكمية</th>
                  <th width="10%">السعر</th>
                  <th width="10%">الإجمالي</th>
                  <th width="10%">الكمية المرتجعة</th>
                  <th width="15%">سبب الإرجاع</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in invoice.items" :key="item.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.product_code }}</td>
                  <td>{{ item.product_name }}</td>
                  <td>{{ item.quantity }}</td>
                  <td>{{ item.price }}</td>
                  <td>{{ item.total }}</td>
                  <td>
                    <input
                      type="number"
                      class="form-control"
                      v-model="item.return_quantity"
                      :max="item.quantity"
                      min="0"
                    />
                  </td>
                  <td>
                    <select class="form-control" v-model="item.return_reason">
                      <option value="">اختر السبب</option>
                      <option value="تالف">تالف</option>
                      <option value="غير مطابق">غير مطابق</option>
                      <option value="آخر">آخر</option>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- أزرار الحفظ والطباعة -->
          <div class="row mt-4">
            <div class="col-md-12 text-right">
              <button class="btn btn-success mr-2" @click="saveReturn">
                <i class="fas fa-save"></i> حفظ المرتجع
              </button>
              <button
                class="btn btn-info"
                @click="printReturn"
                :disabled="!returnId"
              >
                <i class="fas fa-print"></i> طباعة سند الإرجاع
              </button>
            </div>
          </div>
        </div>

        <!-- رسائل التنبيه -->
        <div class="alert alert-danger mt-3" v-if="errorMessage">
          {{ errorMessage }}
        </div>
        <div class="alert alert-success mt-3" v-if="successMessage">
          {{ successMessage }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      invoiceNumber: "",
      invoice: null,
      errorMessage: "",
      successMessage: "",
      returnId: null,
    };
  },
  methods: {
    async searchInvoice() {
      if (!this.invoiceNumber) {
        this.errorMessage = "الرجاء إدخال رقم الفاتورة";
        return;
      }

      try {
        const response = await axios.get(`/api/invoices/${this.invoiceNumber}`);
        this.invoice = response.data;

        // إضافة حقول للإرجاع
        this.invoice.items.forEach((item) => {
          item.return_quantity = 0;
          item.return_reason = "";
        });

        this.errorMessage = "";
      } catch (error) {
        this.invoice = null;
        this.errorMessage = "لم يتم العثور على الفاتورة";
      }
    },

    async saveReturn() {
      // التحقق من وجود أصناف مختارة للإرجاع
      const returnItems = this.invoice.items.filter(
        (item) => item.return_quantity > 0 && item.return_reason
      );

      if (returnItems.length === 0) {
        this.errorMessage = "الرجاء تحديد الأصناف المراد إرجاعها وسبب الإرجاع";
        return;
      }

      try {
        const response = await axios.post("/api/sales-returns", {
          invoice_id: this.invoice.id,
          items: returnItems,
        });

        this.returnId = response.data.return_id;
        this.successMessage = "تم حفظ مرتجع المبيعات بنجاح";
        this.errorMessage = "";
      } catch (error) {
        this.errorMessage = "حدث خطأ أثناء حفظ المرتجع";
      }
    },

    printReturn() {
      if (this.returnId) {
        window.open(`/sales-returns/${this.returnId}/print`, "_blank");
      }
    },
  },
};
</script>

<style scoped>
.sales-return-container {
  direction: rtl;
  text-align: right;
}

.card-header h4 {
  margin: 0;
}

.table th {
  text-align: center;
}

.form-control {
  text-align: right;
}
</style>

<template>
  <div class="container">
    <h1>Approve POS Session</h1>
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Session ID: {{ session.id }}</h5>
        <p class="card-text">Shift Type: {{ session.shift_type ? session.shift_type.name : 'N/A' }}</p>
        <p class="card-text">Cashier: {{ session.cashier ? session.cashier.name : 'N/A' }}</p>
        <p class="card-text">Start Time: {{ session.start_time }}</p>
        <p class="card-text">End Time: {{ session.end_time || 'N/A' }}</p>
        <p class="card-text">Opening Float: {{ session.opening_float }}</p>
        <p class="card-text">Total Sales: {{ session.total_sales }}</p>
        <p class="card-text">Total Refunds: {{ session.total_refunds }}</p>
        <p class="card-text">Cash Expected: {{ session.cash_expected }}</p>
        <p class="card-text">Cash Actual: {{ session.cash_actual }}</p>
      </div>
    </div>
    <form @submit.prevent="submitApproval">
      <div class="form-group">
        <label for="delivered_amount">Delivered Amount</label>
        <input v-model="form.delivered_amount" type="number" id="delivered_amount" class="form-control" :class="{ 'is-invalid': errors.delivered_amount }" step="0.01" min="0" required>
        <div v-if="errors.delivered_amount" class="invalid-feedback">{{ errors.delivered_amount[0] }}</div>
      </div>
      <div class="form-group">
        <label for="status">Approval Status</label>
        <select v-model="form.status" id="status" class="form-control" :class="{ 'is-invalid': errors.status }" required>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
        <div v-if="errors.status" class="invalid-feedback">{{ errors.status[0] }}</div>
      </div>
      <div class="form-group">
        <label for="comments">Comments (Optional)</label>
        <textarea v-model="form.comments" id="comments" class="form-control" :class="{ 'is-invalid': errors.comments }"></textarea>
        <div v-if="errors.comments" class="invalid-feedback">{{ errors.comments[0] }}</div>
      </div>
      <button type="submit" class="btn btn-primary mt-3">Submit Approval</button>
    </form>
  </div>
</template>

<script>
export default {
  name: 'PosApprovalCreate',
  data() {
    return {
      session: {},
      form: {
        delivered_amount: 0.00,
        status: 'approved',
        comments: ''
      },
      errors: {}
    }
  },
  mounted() {
    this.fetchSession();
  },
  methods: {
    async fetchSession() {
      try {
        const response = await axios.get(`pos/summary/${this.$route.params.sessionId}`);
        this.session = response.data;
        this.form.delivered_amount = this.session.cash_actual;
      } catch (error) {
        console.error('Error fetching session:', error);
        this.$toast.error('Failed to load session details.');
      }
    },
    async submitApproval() {
      this.errors = {};
      try {
        const response = await axios.post(`pos/approve/${this.$route.params.sessionId}`, this.form);
        this.$router.push({ name: 'pos.approvals' });
        this.$toast.success('Approval submitted successfully.');
      } catch (error) {
        if (error.response && error.response.data.errors) {
          this.errors = error.response.data.errors;
        }
        console.error('Error submitting approval:', error);
        this.$toast.error('Failed to submit approval.');
      }
    }
  }
}
</script>

<style scoped>
.card {
  border: 1px solid rgba(0, 0, 0, 0.125);
  border-radius: 0.25rem;
}
.card-body {
  padding: 1.25rem;
}
.card-title {
  margin-bottom: 0.75rem;
}
.card-text {
  margin-bottom: 1rem;
}
.form-group {
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
.mb-3 {
  margin-bottom: 1rem;
}
.mt-3 {
  margin-top: 1rem;
}
</style>

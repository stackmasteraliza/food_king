<template>
  <div class="p-4 max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Session Management</h2>

    <!-- Open New Session -->
    <div class="mb-6 border p-4 rounded shadow">
      <h3 class="font-semibold mb-2">Open New Session</h3>
      <form @submit.prevent="openSession">
        <label class="block mb-1">Select Shift</label>
        <select
          v-model="newSession.shift_id"
          required
          class="w-full mb-3 border rounded px-2 py-1"
        >
          <option disabled value="">-- Choose Shift --</option>
          <option v-for="shift in shifts" :key="shift.id" :value="shift.id">
            {{ shift.name }}
          </option>
        </select>

        <select
          v-model="newSession.device_id"
          required
          class="w-full mb-3 border rounded px-2 py-1"
        >
          <option disabled value="">-- Choose device --</option>
          <option v-for="device in devices" :key="device.id" :value="device.id">
            {{ device.name }}
          </option>
        </select>

        <label class="block mb-1">Opening Balance</label>
        <input
          type="number"
          min="0"
          step="0.01"
          v-model.number="newSession.opening_balance"
          required
          class="w-full mb-3 border rounded px-2 py-1"
          placeholder="0.00"
        />

        <button
          type="submit"
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
          :disabled="loadingOpen"
        >
          {{ loadingOpen ? "Opening..." : "Open Session" }}
        </button>
      </form>
    </div>

    <!-- Open Sessions List -->
  </div>
</template>

<script>
export default {
  name: "posSessionComponent",
  data() {
    return {
      shifts: [],
      devices: [],
      sessions: [],
      newSession: {
        shift_id: "",
        opening_balance: null,
      },
      Props: {
        form: {
          shift_id: 0,
          device_id: 0,
          sessionNumber: 0,
          opening_balance: 0,
          opened_at: "",
        },
        search: {
          paginate: 1,
          page: 1,
          per_page: 10,
          order_column: "id",
          order_type: "desc",
        },
      },
      loadingOpen: false,
      loadingClose: {},
    };
  },
  mounted() {
    this.fetchShifts();
    this.fetchDEVICEs();
    // this.fetchSessions();
  },
  computed: {

  },

  methods: {
    fetchShifts() {
      this.loading = true;
      this.Props.search.page = 1;
      this.$store
        .dispatch("Shift/lists", this.Props.search)
        .then((res) => {
       
          this.shifts = res.data.data;
          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
        });
    },
    fetchDEVICEs() {
      this.loading = true;
      this.Props.search.page = 1;
      this.$store
        .dispatch("PosDevice/lists", this.Props.search)
        .then((res) => {
          this.devices = res.data.data;
          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
        });
    },

    // fetchSessions() {
    //   this.loading = true;
    //   this.Props.search.page = 1;
    //   this.$store
    //     .dispatch("posShift/lists", this.Props.search)
    //     .then((res) => {
    //       this.loading = false;
    //     })
    //     .catch((err) => {
    //       this.loading = false;
    //     });
    // },
    openSession: function () {
      try {
        if (
          !this.newSession.shift_id ||
          this.newSession.opening_balance === null
        )
          return;
        this.loading = true;
        this.Props.form = {
          device_id: 0,
          sessionNumber: 0,
          shift_id: this.newSession.shift_id,
          opening_balance: this.newSession.opening_balance,
          opened_at: new Date().toISOString(),
        };
        this.$store
          .dispatch("posShift/openSession", this.Props)
          .then((res) => {
            this.loading = false;
            alertService.success(res.data.sessionnumber);
            if (res.data.sessionnumber !== null) {
              this.Props.form = {
                shift_id: 0,
                device_id: 0,
                sessionNumber: 0,
              };
              router.push({ name: "admin.pos" });

              setTimeout(() => {
                appService.recursiveRouter(routes, this.permission);
              }, 300);
            }
          })
          .catch((err) => {
            this.loading = false;
            this.errors = err.response.data.errors;
          });
      } catch (err) {
        this.loading = false;
      }
    },

    closeSession(session) {
      if (
        session.closing_balance_input === null ||
        session.closing_balance_input === undefined
      )
        return;

      this.$set(this.loadingClose, session.id, true);

      axios
        .put(`/api/sessions/${session.id}`, {
          closing_balance: session.closing_balance_input,
          closed_at: new Date().toISOString(),
        })
        .then(() => {
          this.fetchSessions();
        })
        .catch(() => alert("Failed to close session"))
        .finally(() => {
          this.$set(this.loadingClose, session.id, false);
        });
    },
    formatDate(dt) {
      return new Date(dt).toLocaleString();
    },
  },
};
</script>

<style scoped>
/* Optional simple styling */
</style>

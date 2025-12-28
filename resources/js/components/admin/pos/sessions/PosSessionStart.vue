<template>
  <div class="container py-6">
    <div class="bg-white rounded-lg shadow">
      <div class="border-b px-6 py-4 bg-primary-light">
        <h2 class="text-lg font-semibold text-heading">Start POS Session</h2>
      </div>
      <div class="p-6">
        <div
          v-if="hasActiveSession"
          class="p-4 mb-4 text-sm rounded bg-yellow-100 text-yellow-800"
        >
          You already have an active session. You cannot start a new session until the
          current one is ended.
          <router-link to="/pos/sessions/active" class="db-btn bg-primary text-white mt-2"
            >Go to Active Session</router-link
          >
        </div>
        <form v-else @submit.prevent="startSession">
          <div class="form-row">
            <div class="form-col-12 sm:form-col-6">
              <label for="shiftType" class="db-field-title required">Shift Type</label>
              <select
                id="shiftType"
                v-model="form.shiftTypeId"
                :disabled="isSubmitting"
                class="db-field-control f-b-custom-select"
                v-bind:class="errors.shiftTypeId ? 'invalid' : ''"
              >
                <option value="">Select Shift Type</option>
                <option v-for="shift in shiftTypes" :key="shift.id" :value="shift.id">
                  {{ shift.name }}
                </option>
              </select>
              <small v-if="errors.shiftTypeId" class="db-field-alert text-red-500">{{
                errors.shiftTypeId
              }}</small>
            </div>

            <div class="form-col-12 sm:form-col-6">
              <label for="startingCash" class="db-field-title required"
                >Starting Cash Amount</label
              >
              <input
                type="number"
                id="startingCash"
                v-model="form.startingCash"
                :disabled="isSubmitting"
                step="0.01"
                min="0"
                placeholder="Enter starting cash amount"
                class="db-field-control"
                v-bind:class="errors.startingCash ? 'invalid' : ''"
              />
              <small v-if="errors.startingCash" class="db-field-alert text-red-500">{{
                errors.startingCash
              }}</small>
            </div>
          </div>

          <div class="flex flex-wrap gap-3 justify-end mt-6">
            <router-link to="/pos/dashboard" class="modal-btn-outline"
              >Cancel</router-link
            >
            <button
              type="submit"
              class="db-btn bg-primary text-white"
              :disabled="isSubmitting"
            >
              <i class="lab lab-save"></i>
              <span>{{ isSubmitting ? "Starting..." : "Start Session" }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "PosSessionStart",
  data() {
    return {
      form: {
        shiftTypeId: "",
        startingCash: 0,
      },
      shiftTypes: [],
      errors: {},
      isSubmitting: false,
      hasActiveSession: false,
    };
  },
  created() {
    this.fetchShiftTypes();
    this.checkActiveSession();
  },
  methods: {
    async fetchShiftTypes() {
      console.log("Fetching shift types from API...");
      this.loading = true;

      try {
        const response = await axios.get("pos/shift-types");
        console.log("Shift types API response:", response.data);

        const shiftTypes = Array.isArray(response.data)
          ? response.data
          : response.data?.data || [];

        this.shiftTypes = shiftTypes.map((shift) => ({
          id: shift.id,
          name: shift.name,
          description: shift.description || `${shift.name} shift`,
        }));

        console.log("Processed shift types:", this.shiftTypes);

        if (!this.shiftTypes.length) {
          console.warn("No shift types returned from API");
        }
      } catch (error) {
        console.error(
          "Error fetching shift types:",
          error.response?.data || error.message
        );
        this.$toast.error("Failed to load shift types. Please try again.");
      } finally {
        this.loading = false;
      }
    },

    checkActiveSession() {
      axios
        .get("pos/sessions/active")
        .then((response) => {
          this.hasActiveSession = response.data.hasActiveSession;
        })
        .catch((error) => {
          console.error("Error checking active session:", error);
          this.$toast.error("Failed to check active session");
        });
    },
    startSession() {
      this.isSubmitting = true;
      this.errors = {};

      axios
        .post("pos/sessions", {
          shift_type_id: this.form.shiftTypeId,
          device_id: 1,
          starting_cash: this.form.startingCash,
        })
        .then((response) => {
          this.$toast.success("POS Session started successfully");
          this.$router.push("pos/sessions/active");
        })
        .catch((error) => {
          if (error.response && error.response.data.errors) {
            this.errors = error.response.data.errors;
          } else {
            this.$toast.error(error.response?.data?.error || "Failed to start session");
          }
          console.error("Error starting session:", error);
        })
        .finally(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>

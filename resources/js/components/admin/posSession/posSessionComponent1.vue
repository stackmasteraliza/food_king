<template>
  <LoadingComponent :props="loading" />
  <SmModalCreateComponent :props="addButton" />
  <div id="modal" class="modal">
    <div class="modal-dialog">
      <div class="modal-header">
        <h3 class="modal-title">{{ $t("menu.opensession") }}</h3>
        <button
          class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
          @click="reset"
        ></button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="save">
          <div class="form-row">
            <div class="form-col-12 sm:form-col-6">
              <label for="shift_id" class="db-field-title required">
                {{ $t("label.shift") }}
              </label>
              <vue-select
                class="db-field-control f-b-custom-select"
                id="shift_id"
                v-bind:class="errors.shift_id ? 'invalid' : ''"
                v-model="props.form.shift_id"
                :options="shifts"
                label-by="name"
                value-by="id"
                :closeOnSelect="true"
                :searchable="true"
                :clearOnClose="true"
                placeholder="--"
                search-placeholder="--"
              />
              <small class="db-field-alert" v-if="errors.shift_id">{{
                errors.shift_id[0]
              }}</small>
            </div>
            <div class="form-col-12 sm:form-col-6">
              <label for="device_id" class="db-field-title required">
                {{ $t("label.device_id") }}
              </label>
              <vue-select
                class="db-field-control f-b-custom-select"
                id="device_id"
                v-bind:class="errors.device_id ? 'invalid' : ''"
                v-model="props.form.device_id"
                :options="devices"
                label-by="name"
                value-by="id"
                @change="updateSessionNumber"
                :closeOnSelect="true"
                :searchable="true"
                :clearOnClose="true"
                placeholder="--"
                search-placeholder="--"
              />
              <small class="db-field-alert" v-if="errors.device_id">{{
                errors.device_id[0]
              }}</small>
            </div>

            <div class="form-col-12">
              <div class="modal-btns">
                <button
                  type="button"
                  class="modal-btn-outline modal-close"
                  @click="reset"
                >
                  <i class="lab lab-close"></i>
                  <span>{{ $t("button.close") }}</span>
                </button>

                <button
                  type="submit"
                  @click="opensession"
                  class="db-btn py-2 text-white bg-primary"
                >
                  <i class="lab lab-save"></i>
                  <span>{{ $t("button.save") }}</span>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
import SmModalCreateComponent from "../components/buttons/SmModalCreateComponent";
import LoadingComponent from "../components/LoadingComponent";
import statusEnum from "../../../enums/modules/statusEnum";
import askEnum from "../../../enums/modules/askEnum";
import roleEnum from "../../../enums/modules/roleEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
  name: "posSessionComponent1",
  components: { SmModalCreateComponent, LoadingComponent },
  props: ["props"],
  data() {
    return {
      loading: {
        isActive: false,
      },
      enums: {
        statusEnum: statusEnum,
        roleEnum: roleEnum,
        statusEnumArray: {
          [statusEnum.ACTIVE]: this.$t("label.active"),
          [statusEnum.INACTIVE]: this.$t("label.inactive"),
        },
      },
      errors: {},
    };
  },
  computed: {
    addButton: function () {
      return { title: this.$t("button.add_session") };
    },
    shifts: function () {
      return this.$store.getters["shift/lists"];
    },
    devices: function () {
      return this.$store.getters["posdevice/lists"];
    },
  },
  mounted() {
    this.loading.isActive = true;
    this.$store.dispatch("shift/lists", {
      order_column: "id",
      order_type: "asc",
    });
    this.$store.dispatch("posdevice/lists", {
      order_column: "id",
      order_type: "asc",
    });
    this.loading.isActive = false;
  },
  methods: {
    updateSessionNumber() {
      this.loading.isActive = true;
      this.$store
        .dispatch("posShift/generatesession", {
          deviceid: this.Props.form.device_id,
        })
        .then((response) => {
          this.Props.form.sessionNumber = response.data.session_number;
        })
        .catch((err) => {
          this.loading.isActive = false;
          this.errors = err.response.data.errors;
        });
    },
    opensession: function () {
      try {
        this.loading.isActive = true;

        this.$store
          .dispatch("posShift/openSession", this.Props)
          .then((res) => {
            this.closeModal(); // Close modal on success
            this.loading.isActive = false;
            alertService.success(res.data.session_number);
            if (res.data.session_number !== null) {
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
            this.loading.isActive = false;
            this.errors = err.response.data.errors;
          });
      } catch (err) {
        this.loading.isActive = false;
      }
    },
  },
};
</script>

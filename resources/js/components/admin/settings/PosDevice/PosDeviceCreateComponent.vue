<template>
  <LoadingComponent :props="loading" />
  <SmModalCreateComponent :props="addButton" />

  <div id="modal" class="modal">
    <div class="modal-dialog">
      <div class="modal-header">
        <h3 class="modal-title">{{ $t("menu.PosDevices") }}</h3>
        <button
          class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
          @click="reset"
        ></button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="save">
          <div class="form-row">
            <div class="form-col-12 sm:form-col-6">
              <label for="name" class="db-field-title required">{{
                $t("label.name")
              }}</label>
              <input
                v-model="props.form.name"
                v-bind:class="errors.name ? 'invalid' : ''"
                type="text"
                id="name"
                class="db-field-control"
              />
              <small class="db-field-alert" v-if="errors.name">{{
                errors.name[0]
              }}</small>
            </div>
            <div class="form-col-12 sm:form-col-6">
              <label for="identifier" class="db-field-title required">{{
                $t("label.identifier")
              }}</label>
              <input
                v-model="props.form.identifier"
                v-bind:class="errors.identifier ? 'invalid' : ''"
                type="text"
                id="identifier"
                class="db-field-control"
              />
              <small class="db-field-alert" v-if="errors.identifier">{{
                errors.identifier[0]
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

                <button type="submit" class="db-btn py-2 text-white bg-primary">
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
import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent";
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import MapComponent from "../../../admin/components/MapComponent";

export default {
  name: "PosDeviceCreateComponent",
  components: { SmModalCreateComponent, LoadingComponent, MapComponent },
  props: ["props"],
  data() {
    return {
      loading: {
        isActive: false,
      },
      isMap: false,
      address: "",
      errors: {},
    };
  },
  computed: {
    addButton: function () {
      return { title: this.$t("button.add_PosDevice") };
    },
  },
  methods: {
    reset: function () {
      appService.modalHide();
      this.$store.dispatch("PosDevice/reset").then().catch();
      this.errors = {};
      this.$props.props.form = {
        name: "",
        identifier: "",
      };
    },

    save: function () {
      try {
        const tempId = this.$store.getters["PosDevice/temp"].temp_id;
        this.loading.isActive = true;

        this.$store
          .dispatch("PosDevice/save", this.props)
          .then((res) => {
            appService.modalHide();
            this.loading.isActive = false;
            alertService.successFlip(
              tempId === null ? 0 : 1,
              this.$t("menu.PosDevices")
            );
            this.props.form = {
              name: "",
              identifier: "",
            };
            this.errors = {};
          })
          .catch((err) => {
            this.loading.isActive = false;
            this.errors = err.response.data.errors;
          });
      } catch (err) {
        this.loading.isActive = false;
        alertService.error(err);
      }
    },
  },
};
</script>

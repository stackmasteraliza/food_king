<template>
  <LoadingComponent :props="loading" />
  <SmModalCreateComponent :props="addButton" />

  <div id="modal" class="modal">
    <div class="modal-dialog">
      <div class="modal-header">
        <h3 class="modal-title">{{ $t("menu.KitchenPrinterSettings") }}</h3>
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
              <label for="ip_address" class="db-field-title required">{{
                $t("label.ip_address")
              }}</label>
              <input
                v-model="props.form.ip_address"
                v-bind:class="errors.ip_address ? 'invalid' : ''"
                type="text"
                id="ip_address"
                class="db-field-control"
              />
              <small class="db-field-alert" v-if="errors.ip_address">{{
                errors.ip_address[0]
              }}</small>
            </div>
            <div class="form-col-12 sm:form-col-6">
              <label for="port" class="db-field-title required">{{
                $t("label.port")
              }}</label>
              <input
                v-model="props.form.port"
                v-bind:class="errors.port ? 'invalid' : ''"
                type="number"
                id="port"
                class="db-field-control"
              />
              <small class="db-field-alert" v-if="errors.port">{{
                errors.port[0]
              }}</small>
            </div>
            <div class="form-col-12 sm:form-col-6">
              <label class="db-field-title required" for="yes">{{
                $t("label.is_default")
              }}</label>
              <div class="db-field-radio-group">
                <div class="db-field-radio">
                  <div class="custom-radio">
                    <input
                      :value="enums.askEnum.YES"
                      v-model="props.form.is_default"
                      id="yes"
                      type="radio"
                      class="custom-radio-field"
                    />
                    <span class="custom-radio-span"></span>
                  </div>
                  <label for="yes" class="db-field-label">{{
                    $t("label.yes")
                  }}</label>
                </div>
                <div class="db-field-radio">
                  <div class="custom-radio">
                    <input
                      :value="enums.askEnum.NO"
                      v-model="props.form.is_default"
                      type="radio"
                      id="no"
                      class="custom-radio-field"
                    />
                    <span class="custom-radio-span"></span>
                  </div>
                  <label for="no" class="db-field-label">{{
                    $t("label.no")
                  }}</label>
                </div>
              </div>
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
import askEnum from "../../../../enums/modules/askEnum";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";

export default {
  name: "KitchenPrinterSettingCreateComponent",
  components: { SmModalCreateComponent, LoadingComponent },
  props: ["props"],
  data() {
    return {
      loading: {
        isActive: false,
      },
      enums: {
        askEnum: askEnum,
        askEnumArray: {
          [askEnum.YES]: this.$t("label.yes"),
          [askEnum.NO]: this.$t("label.no"),
        },
      },
      errors: {},
    };
  },
  computed: {
    addButton: function () {
      return { title: this.$t("button.add_KitchenPrinterSetting") };
    },
  },
  methods: {
    reset: function () {
      appService.modalHide();
      this.$store.dispatch("KitchenPrinterSetting/reset").then().catch();
      this.errors = {};
      this.$props.props.form = {
        name: "",
        ip_address: "",
        is_default: askEnum.NO,
        port: 0,
        branch_id: 1,
      };
    },

    save: function () {
      try {
        const tempId =
          this.$store.getters["KitchenPrinterSetting/temp"].temp_id;
        this.loading.isActive = true;
        this.$store
          .dispatch("KitchenPrinterSetting/save", this.props)
          .then((res) => {
            appService.modalHide();
            this.loading.isActive = false;
            alertService.successFlip(
              tempId === null ? 0 : 1,
              this.$t("menu.KitchenPrinterSettings")
            );
            this.props.form = {
              name: "",
              ip_address: "",
              is_default: askEnum.NO,
              port: 0,
              branch_id: 1,
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

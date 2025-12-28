<template>
  <LoadingComponent :props="loading" />
  <SmModalCreateComponent :props="addButton" />

  <div id="modal" class="modal">
    <div class="modal-dialog">
      <div class="modal-header">
        <h3 class="modal-title">{{ $t("menu.AdminIntegrations") }}</h3>
        <button
          class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
          @click="reset"
        ></button>
      </div>
      <div class="p-4">
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
                <label for="price" class="db-field-title required">{{
                  $t("label.price")
                }}</label>
                <input
                  v-model.number="props.form.price"
                  v-bind:class="errors.price ? 'invalid' : ''"
                  type="number"
                  id="price"
                  class="db-field-control"
                />
                <small class="db-field-alert" v-if="errors.price">{{
                  errors.price[0]
                }}</small>
              </div>

              <div class="form-col-12 sm:form-col-6">
                <label for="icon_url" class="db-field-title required">{{
                  $t("label.icon_url")
                }}</label>
                <input
                  v-model="props.form.icon_url"
                  v-bind:class="errors.icon_url ? 'invalid' : ''"
                  type="text"
                  id="icon_url"
                  class="db-field-control"
                />
                <small class="db-field-alert" v-if="errors.icon_url">{{
                  errors.icon_url[0]
                }}</small>
              </div>
              <div class="form-col-12 sm:form-col-6">
                <label for="description" class="db-field-title">{{
                  $t("label.description")
                }}</label>
                <input
                  v-model="props.form.description"
                  v-bind:class="errors.description ? 'invalid' : ''"
                  type="text"
                  id="description"
                  class="db-field-control"
                />
                <small class="db-field-alert" v-if="errors.description">{{
                  errors.description[0]
                }}</small>
              </div>
              <div class="form-col-12 sm:form-col-6">
                <label for="trial_days" class="db-field-title">{{
                  $t("label.trial_days")
                }}</label>
                <input
                  v-model="props.form.trial_days"
                  v-bind:class="errors.trial_days ? 'invalid' : ''"
                  type="number"
                  placeholder="فترة تجريبية (أيام)"
                  id="trial_days"
                  class="db-field-control"
                />
                <small class="db-field-alert" v-if="errors.trial_days">{{
                  errors.trial_days[0]
                }}</small>
              </div>
              <div class="form-col-12 sm:form-col-6">
                <label for="features" class="db-field-title">{{
                  $t("label.features")
                }}</label>
                <input
                  v-model="props.form.features"
                  v-bind:class="errors.features ? 'invalid' : ''"
                  placeholder="المميزات (مفصولة بفاصلة)"
                  type="text"
                  id="features"
                  class="db-field-control"
                />
                <small class="db-field-alert" v-if="errors.features">{{
                  errors.features[0]
                }}</small>
              </div>

              <div class="form-col-12 sm:form-col-6">
                <label class="db-field-title required" for="yes">{{
                  $t("label.active")
                }}</label>
                <div class="db-field-radio-group">
                  <div class="db-field-radio">
                    <div class="custom-radio">
                      <input
                        :value="enums.askEnum.YES"
                        v-model="props.form.active"
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
                        v-model="props.form.active"
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

                  <button
                    type="submit"
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
  </div>
</template>
<script>
import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent";
import LoadingComponent from "../../components/LoadingComponent";
import askEnum from "../../../../enums/modules/askEnum";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import statusEnum from "../../../../enums/modules/statusEnum";

export default {
  name: "AdminIntegrationsCreateComponent",
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
        statusEnum: statusEnum,
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
      return { title: this.$t("button.add_Integrations") };
    },
  },
  methods: {
    reset: function () {
      appService.modalHide();
      this.$store.dispatch("integrations/reset").then().catch();
      this.errors = {};
      this.$props.props.form = {
        id: null,
        name: "",
        icon_url: "",
        description: "",
        price: 0,
        trial_days: 14,
        features: "",
        active: true,
      };
    },

    save: function () {
      try {
        const tempId = this.$store.getters["integrations/temp"].temp_id;
        this.loading.isActive = true;
        this.props.form = {
          id: this.props.form.id,
          name: this.props.form.name,
          icon_url: this.props.form.icon_url,
          description: this.props.form.description,
          price: this.props.form.price,
          trial_days: this.props.form.trial_days,
          features: this.props.form.features,
          active: this.props.form.active == 5 ? 1 : 0,
        };
        this.$store
          .dispatch("integrations/save", this.props)
          .then((res) => {
            appService.modalHide();
            this.loading.isActive = false;
            alertService.successFlip(
              tempId === null ? 0 : 1,
              this.$t("menu.integrations")
            );
            this.props.form = {
              id: null,
              name: "",
              icon_url: "",
              description: "",
              price: 0,
              trial_days: 14,
              features: "",
              active: true,
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

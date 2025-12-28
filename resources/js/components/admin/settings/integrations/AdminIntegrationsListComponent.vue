<template>
  <LoadingComponent :props="loading" />
  <div class="db-card db-tab-div active">
    <div class="db-card-header border-none">
      <h3 class="db-card-title">{{ $t("menu.AdminIntegrations") }}</h3>
      <div class="db-card-filter">
        <TableLimitComponent
          :method="list"
          :search="props.search"
          :page="paginationPage"
        />
        <AdminIntegrationsCreateComponent :props="props" />
      </div>
    </div>

    <div class="db-table-responsive">
      <table class="db-table stripe">
        <thead class="db-table-head">
          <tr class="db-table-head-tr">
            <th class="db-table-head-th">{{ $t("label.name") }}</th>
            <th class="db-table-head-th">السعر</th>
            <th class="db-table-head-th">تجربة</th>
            <th class="db-table-head-th">مفعل</th>
            <th class="db-table-head-th">
              {{ $t("label.action") }}
            </th>
          </tr>
        </thead>
        <tbody class="db-table-body" v-if="apps.length > 0">
          <tr class="db-table-body-tr" v-for="app in apps" :key="app">
            <td class="db-table-body-td">
              {{ app.name }}
            </td>
            <td class="db-table-body-td">
              {{ app.price }}
            </td>
            <td class="db-table-body-td">
              {{ app.trial_days }}
            </td>
            <td class="db-table-body-td">
              <span :class="askClass(app.active ? 5 : 10)">
                {{ enums.askEnumArray[app.active ? 5 : 10] }}
              </span>
            </td>

            <td class="db-table-body-td">
              <div
                class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5"
              >
                <SmModalEditComponent @click="edit(app)" />
                <SmDeleteComponent @click="destroy(app.id)" />
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div
      class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6"
    >
      <PaginationSMBox :pagination="pagination" :method="list" />
      <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <PaginationTextComponent :props="{ page: paginationPage }" />
        <PaginationBox :pagination="pagination" :method="list" />
      </div>
    </div>
  </div>
</template>

<script >
import LoadingComponent from "../../components/LoadingComponent";
import AdminIntegrationsCreateComponent from "./AdminIntegrationsCreateComponent";
import alertService from "../../../../services/alertService";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";
import appService from "../../../../services/appService";
import TableLimitComponent from "../../components/TableLimitComponent";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent";
import askEnum from "../../../../enums/modules/askEnum";

export default {
  name: "AdminIntegrationsListComponent",
  components: {
    TableLimitComponent,
    PaginationSMBox,
    PaginationBox,
    PaginationTextComponent,
    LoadingComponent,
    SmDeleteComponent,
    SmModalEditComponent,
    askEnum,
    AdminIntegrationsCreateComponent,
  },

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

      props: {
        form: {
          name: "",
          icon_url: "",
          description: "",
          price: 0,
          trial_days: 14,
          features: "",
          active: true,
        },
        search: {
          paginate: 1,
          page: 1,
          per_page: 10,
          order_column: "id",
          order_type: "desc",
        },
      },
    };
  },
  mounted() {
    this.list();
  },
  computed: {
    apps: function () {
      return this.$store.getters["integrations/lists"];
    },
    pagination: function () {
      return this.$store.getters["integrations/pagination"];
    },
    paginationPage: function () {
      return this.$store.getters["integrations/page"];
    },
  },
  methods: {
    askClass: function (ask) {
      return appService.askClass(ask);
    },
    list: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("integrations/lists", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    edit: function (tax) {
      appService.modalShow();
      this.loading.isActive = true;
      this.$store.dispatch("integrations/edit", tax.id);
      this.props.form = {
        name: tax.name,
        icon_url: tax.icon_url,
        description: tax.description,
        price: tax.price,
        trial_days: tax.trial_days,
        features: tax.features,
        active: tax.active ? 5 : 10,
      };
      this.loading.isActive = false;
    },
    destroy: function (id) {
      appService
        .destroyConfirmation()
        .then((res) => {
          try {
            this.loading.isActive = true;
            this.$store
              .dispatch("integrations/destroy", {
                id: id,
                search: this.props.search,
              })
              .then((res) => {
                this.loading.isActive = false;
                alertService.successFlip(
                  null,
                  this.$t("menu.AdminIntegrations")
                );
              })
              .catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
              });
          } catch (err) {
            this.loading.isActive = false;
            alertService.error(err.response.data.message);
          }
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
  },
};
</script>

<style scoped>
.input {
  display: block;
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 0.25rem;
}
</style>

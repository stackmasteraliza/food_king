<template>
  <LoadingComponent :props="loading" />

  <div class="db-card db-tab-div active">
    <div class="db-card-header border-none">
      <h3 class="db-card-title">{{ $t("menu.PosDevices") }}</h3>
      <div class="db-card-filter">
        <TableLimitComponent
          :method="list"
          :search="props.search"
          :page="paginationPage"
        />
        <PosDeviceCreateComponent :props="props" />
      </div>
    </div>

    <div class="db-table-responsive">
      <table class="db-table stripe">
        <thead class="db-table-head">
          <tr class="db-table-head-tr">
            <th class="db-table-head-th">{{ $t("label.name") }}</th>
            <th class="db-table-head-th">
              {{ $t("label.identifier") }}
            </th>
            <th class="db-table-head-th">
              {{ $t("label.action") }}
            </th>
          </tr>
        </thead>
        <tbody class="db-table-body" v-if="PosDevices.length > 0">
          <tr
            class="db-table-body-tr"
            v-for="PosDevice in PosDevices"
            :key="PosDevice"
          >
            <td class="db-table-body-td">
              {{ PosDevice.name }}
            </td>
            <td class="db-table-body-td">
              {{ PosDevice.identifier }}
            </td>

            <td class="db-table-body-td">
              <div
                class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5"
              >
                <SmViewComponent
                  :link="'admin.settings.PosDevice.show'"
                  :id="PosDevice.id"
                />
                <SmModalEditComponent @click="edit(PosDevice)" />
                <SmDeleteComponent @click="destroy(PosDevice.id)" />
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
<script>
import LoadingComponent from "../../components/LoadingComponent";
import PosDeviceCreateComponent from "./PosDeviceCreateComponent";
import alertService from "../../../../services/alertService";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";
import appService from "../../../../services/appService";
import TableLimitComponent from "../../components/TableLimitComponent";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent";
import SmViewComponent from "../../components/buttons/SmViewComponent";

export default {
  name: "PosDeviceListComponent",
  components: {
    TableLimitComponent,
    PaginationSMBox,
    PaginationBox,
    PaginationTextComponent,
    PosDeviceCreateComponent,
    LoadingComponent,
    SmDeleteComponent,
    SmModalEditComponent,
    SmViewComponent,
  },
  data() {
    return {
      loading: {
        isActive: false,
      },

      props: {
        form: {
          name: "",
          identifier: "",
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
    PosDevices: function () {
      return this.$store.getters["PosDevice/lists"];
    },
    pagination: function () {
      return this.$store.getters["PosDevice/pagination"];
    },
    paginationPage: function () {
      return this.$store.getters["PosDevice/page"];
    },
  },
  methods: {
    textShortener: function (text, number = 30) {
      return appService.textShortener(text, number);
    },

    list: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("PosDevice/lists", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    edit: function (PosDevice) {
      appService.modalShow();
      this.loading.isActive = true;
      this.$store.dispatch("PosDevice/edit", PosDevice.id);
      this.props.form = {
        name: PosDevice.name,
        identifier: PosDevice.identifier,
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
              .dispatch("PosDevice/destroy", {
                id: id,
                search: this.props.search,
              })
              .then((res) => {
                this.loading.isActive = false;
                alertService.successFlip(null, this.$t("menu.PosDevices"));
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

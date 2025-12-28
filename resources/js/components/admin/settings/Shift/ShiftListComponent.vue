<template>
  <LoadingComponent :props="loading" />

  <div class="db-card db-tab-div active">
    <div class="db-card-header border-none">
      <h3 class="db-card-title">{{ $t("menu.shifts") }}</h3>
      <div class="db-card-filter">
        <TableLimitComponent
          :method="list"
          :search="props.search"
          :page="paginationPage"
        />
        <ShiftCreateComponent :props="props" />
      </div>
    </div>

    <div class="db-table-responsive">
      <table class="db-table stripe">
        <thead class="db-table-head">
          <tr class="db-table-head-tr">
            <th class="db-table-head-th">{{ $t("label.name") }}</th>
            <th class="db-table-head-th">
              {{ $t("label.status") }}
            </th>
            <th class="db-table-head-th">
              {{ $t("label.action") }}
            </th>
          </tr>
        </thead>
        <tbody class="db-table-body" v-if="shifts.length > 0">
          <tr class="db-table-body-tr" v-for="Shift in shifts" :key="Shift">
            <td class="db-table-body-td">
              {{ Shift.name }}
            </td>
            <td class="db-table-body-td">
              <span :class="statusClass(Shift.status)">
                {{ enums.statusEnumArray[Shift.status] }}
              </span>
            </td>
            <td class="db-table-body-td">
              <div
                class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5"
              >
                <SmViewComponent
                  :link="'admin.settings.Shift.show'"
                  :id="Shift.id"
                />
                <SmModalEditComponent @click="edit(Shift)" />
                <SmDeleteComponent @click="destroy(Shift.id)" />
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
import ShiftCreateComponent from "./ShiftCreateComponent";
import alertService from "../../../../services/alertService";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";
import appService from "../../../../services/appService";
import statusEnum from "../../../../enums/modules/statusEnum";
import TableLimitComponent from "../../components/TableLimitComponent";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent";
import SmViewComponent from "../../components/buttons/SmViewComponent";

export default {
  name: "ShiftListComponent",
  components: {
    TableLimitComponent,
    PaginationSMBox,
    PaginationBox,
    PaginationTextComponent,
    ShiftCreateComponent,
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
      enums: {
        statusEnum: statusEnum,
        statusEnumArray: {
          [statusEnum.ACTIVE]: this.$t("label.active"),
          [statusEnum.INACTIVE]: this.$t("label.inactive"),
        },
      },
      props: {
        form: {
          name: "",
          status: statusEnum.ACTIVE,
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
    shifts: function () {
      return this.$store.getters["Shift/lists"];
    },
    pagination: function () {
      return this.$store.getters["Shift/pagination"];
    },
    paginationPage: function () {
      return this.$store.getters["Shift/page"];
    },
  },
  methods: {
    statusClass: function (status) {
      return appService.statusClass(status);
    },
    textShortener: function (text, number = 30) {
      return appService.textShortener(text, number);
    },

    list: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("Shift/lists", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    edit: function (Shift) {
      appService.modalShow();
      this.loading.isActive = true;
      this.$store.dispatch("Shift/edit", Shift.id);
      this.props.form = {
        name: Shift.name,
        status: Shift.status,
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
              .dispatch("Shift/destroy", {
                id: id,
                search: this.props.search,
              })
              .then((res) => {
                this.loading.isActive = false;
                alertService.successFlip(null, this.$t("menu.shifts"));
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

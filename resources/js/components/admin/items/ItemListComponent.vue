<template>
  <LoadingComponent :props="loading" />
  <div class="col-12">
    <div class="db-card">
      <div class="db-card-header border-none">
        <h3 class="db-card-title">{{ $t("menu.items") }}</h3>
        <div class="db-card-filter">
          <TableLimitComponent
            :method="list"
            :search="props.search"
            :page="paginationPage"
          />
          <FilterComponent @click.prevent="handleSlide('item-filter')" />
          <div class="dropdown-group">
            <ExportComponent />
            <div
              class="dropdown-list db-card-filter-dropdown-list transition-all duration-300 scale-y-0 origin-top"
            >
              <PrintComponent :props="printObj" />
              <ExcelComponent :method="xls" />
            </div>
          </div>
          <div v-if="permissionChecker('items_create')" class="dropdown-group">
            <ImportComponent />
            <div
              class="dropdown-list db-card-filter-dropdown-list transition-all duration-300 scale-y-0 origin-top"
            >
              <SampleFileComponent @click="downloadSample" />
              <UploadFileComponent
                :dataModal="'itemUpload'"
                @click="uploadModal('#itemUpload')"
              />
            </div>
          </div>
          <ItemUploadComponent v-on:list="list" />
          <ItemCreateComponent
            :props="props"
            v-if="permissionChecker('items_create')"
          />
        </div>
      </div>

      <div class="table-filter-div" id="item-filter">
        <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
          <div class="row">
            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label for="name" class="db-field-title after:hidden">{{
                $t("label.name")
              }}</label>
              <input
                id="name"
                v-model="props.search.name"
                type="text"
                class="db-field-control"
              />
            </div>
            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label for="price" class="db-field-title after:hidden">{{
                $t("label.price")
              }}</label>
              <input
                id="price"
                v-on:keypress="numberOnly($event)"
                v-model="props.search.price"
                type="text"
                class="db-field-control"
              />
            </div>
            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label for="item_category_id" class="db-field-title">{{
                $t("label.category")
              }}</label>

              <vue-select
                class="db-field-control f-b-custom-select"
                id="item_category_id"
                v-model="props.search.item_category_id"
                :options="itemCategories"
                label-by="name"
                value-by="id"
                :closeOnSelect="true"
                :searchable="true"
                :clearOnClose="true"
                placeholder="--"
                search-placeholder="--"
              />
            </div>

            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label for="tax_id" class="db-field-title">{{
                $t("label.tax")
              }}</label>

              <vue-select
                class="db-field-control f-b-custom-select"
                id="tax_id"
                v-model="props.search.tax_id"
                :options="taxes"
                label-by="name"
                value-by="id"
                :closeOnSelect="true"
                :searchable="true"
                :clearOnClose="true"
                placeholder="--"
                search-placeholder="--"
              />
            </div>
            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label for="searchItemType" class="db-field-title after:hidden">{{
                $t("label.item_type")
              }}</label>
              <vue-select
                class="db-field-control f-b-custom-select"
                id="searchItemType"
                v-model="props.search.item_type"
                :options="[
                  { id: enums.itemTypeEnum.VEG, name: $t('label.veg') },
                  { id: enums.itemTypeEnum.NON_VEG, name: $t('label.non_veg') },
                ]"
                label-by="name"
                value-by="id"
                :closeOnSelect="true"
                :searchable="true"
                :clearOnClose="true"
                placeholder="--"
                search-placeholder="--"
              />
            </div>

            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label
                for="searchIsFeatured"
                class="db-field-title after:hidden"
                >{{ $t("label.is_featured") }}</label
              >
              <vue-select
                class="db-field-control f-b-custom-select"
                id="searchIsFeatured"
                v-model="props.search.is_featured"
                :options="[
                  { id: enums.askEnum.YES, name: $t('label.yes') },
                  { id: enums.askEnum.NO, name: $t('label.no') },
                ]"
                label-by="name"
                value-by="id"
                :closeOnSelect="true"
                :searchable="true"
                :clearOnClose="true"
                placeholder="--"
                search-placeholder="--"
              />
            </div>

            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
              <label for="searchStatus" class="db-field-title after:hidden">{{
                $t("label.status")
              }}</label>
              <vue-select
                class="db-field-control f-b-custom-select"
                id="searchStatus"
                v-model="props.search.status"
                :options="[
                  { id: enums.statusEnum.ACTIVE, name: $t('label.active') },
                  { id: enums.statusEnum.INACTIVE, name: $t('label.inactive') },
                ]"
                label-by="name"
                value-by="id"
                :closeOnSelect="true"
                :searchable="true"
                :clearOnClose="true"
                placeholder="--"
                search-placeholder="--"
              />
            </div>

            <div class="col-12">
              <div class="flex flex-wrap gap-3 mt-4">
                <button class="db-btn py-2 text-white bg-primary">
                  <i class="lab lab-search-line lab-font-size-16"></i>
                  <span>{{ $t("button.search") }}</span>
                </button>
                <button
                  class="db-btn py-2 text-white bg-gray-600"
                  @click="clear"
                >
                  <i class="lab lab-cross-line-2 lab-font-size-22"></i>
                  <span>{{ $t("button.clear") }}</span>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="db-table-responsive">
        <table class="db-table stripe" id="print" :dir="direction">
          <thead class="db-table-head">
            <tr class="db-table-head-tr">
              <th class="db-table-head-th">
                {{ $t("label.name") }}
              </th>
              <th class="db-table-head-th">
                {{ $t("label.category") }}
              </th>
              <th class="db-table-head-th">
                {{ $t("label.price") }}
              </th>
              <th class="db-table-head-th">
                {{ $t("label.status") }}
              </th>

              <th
                class="db-table-head-th hidden-print"
                v-if="
                  permissionChecker('items_show') ||
                  permissionChecker('items_edit') ||
                  permissionChecker('items_delete')
                "
              >
                {{ $t("label.action") }}
              </th>
              <th
                v-if="permissionChecker('items_edit')"
                class="db-table-head-th hidden-print"
              >
                {{ $t("label.state update") }}
              </th>
              <th
                v-if="permissionChecker('items_edit')"
                class="db-table-head-th hidden-print"
              >
                {{ $t("label.quantity update") }}
              </th>
            </tr>
          </thead>
          <tbody class="db-table-body" v-if="items.length > 0">
            <tr class="db-table-body-tr" v-for="item in items" :key="item">
              <td class="db-table-body-td">
                {{ textShortener(item.name, 40) }}
              </td>
              <td class="db-table-body-td">{{ item.category_name }}</td>
              <td class="db-table-body-td">{{ item.flat_price }}</td>
              <td class="db-table-body-td">
                <span :class="statusClass(item.status)">
                  {{ enums.statusEnumArray[item.status] }}
                </span>
              </td>

              <td
                class="db-table-body-td hidden-print"
                v-if="
                  permissionChecker('items_show') ||
                  permissionChecker('items_edit') ||
                  permissionChecker('items_delete')
                "
              >
                <div
                  class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5"
                >
                  <SmIconViewComponent
                    :link="'admin.item.show'"
                    :id="item.id"
                    v-if="permissionChecker('items_show')"
                  />
                  <SmIconSidebarModalEditComponent
                    @click="edit(item)"
                    v-if="permissionChecker('items_edit')"
                  />
                  <SmIconDeleteComponent
                    @click="destroy(item.id)"
                    v-if="permissionChecker('items_delete')"
                  />
                </div>
              </td>

              <td
                v-if="permissionChecker('items_edit')"
                class="db-table-body-td hidden-print"
              >
                <div class="custom-switch">
                  <input
                    @change="updateStatus(item)"
                    :id="item.id"
                    type="checkbox"
                    :name="item.name"
                    :checked="item.status === enums.statusEnum.ACTIVE"
                  />
                  <label
                    v-if="item.status === enums.statusEnum.ACTIVE"
                    :for="item.id"
                    >{{ $t("label.active") }}</label
                  >
                  <label v-else :for="item.id">{{
                    $t("label.unactive")
                  }}</label>
                </div>
              </td>
              <td
                v-if="permissionChecker('items_edit')"
                class="db-table-body-td hidden-print"
              >
                <div class="flex justify-center">
                  <button
                    @click="openQuantityModal(item)"
                    class="db-btn py-1 px-2 text-white bg-primary"
                  >
                    {{ $t("button.update quantity") }}
                  </button>
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
        <div
          class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between"
        >
          <PaginationTextComponent :props="{ page: paginationPage }" />
          <PaginationBox :pagination="pagination" :method="list" />
        </div>
      </div>
    </div>
  </div>
  <!-- Quantity Update Modal -->
  <div
    id="showQuantityModal"
    v-if="showQuantityModal"
    class="modal fade show"
    style="display: block; background: rgba(0, 0, 0, 0.5)"
    tabindex="-1"
    role="dialog"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ $t("label.update_quantity") }}</h5>
          <button type="button" class="close" @click="closeQuantityModal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="quantity" class="db-field-title">{{
              $t("label.quantity")
            }}</label>
            <input
              type="number"
              id="quantity"
              v-model="newQuantity"
              class="db-field-control"
              min="0"
              step="1"
            />
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="db-btn py-2 text-white bg-gray-600"
            @click="closeQuantityModal"
          >
            {{ $t("button.close") }}
          </button>
          <button
            type="button"
            class="db-btn py-2 text-white bg-primary"
            @click="updateQuantity"
          >
            {{ $t("button.save") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import ItemCreateComponent from "./ItemCreateComponent";
import alertService from "../../../services/alertService";
import statusEnum from "../../../enums/modules/statusEnum";
import askEnum from "../../../enums/modules/askEnum";
import itemTypeEnum from "../../../enums/modules/itemTypeEnum";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import TableLimitComponent from "../components/TableLimitComponent";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent";
import FilterComponent from "../components/buttons/collapse/FilterComponent";
import ExportComponent from "../components/buttons/export/ExportComponent";
import PrintComponent from "../components/buttons/export/PrintComponent";
import ExcelComponent from "../components/buttons/export/ExcelComponent";
import displayModeEnum from "../../../enums/modules/displayModeEnum";
import SampleFileComponent from "../components/buttons/import/SampleFileComponent.vue";
import UploadFileComponent from "../components/buttons/import/UploadFileComponent.vue";
import ImportComponent from "../components/buttons/import/ImportComponent.vue";
import ItemUploadComponent from "./ItemUploadComponent.vue";

export default {
  name: "ItemListComponent",
  components: {
    TableLimitComponent,
    PaginationSMBox,
    PaginationBox,
    PaginationTextComponent,
    ItemCreateComponent,
    LoadingComponent,
    SmIconSidebarModalEditComponent,
    SmIconDeleteComponent,
    SmIconViewComponent,
    FilterComponent,
    ExportComponent,
    PrintComponent,
    ExcelComponent,
    SampleFileComponent,
    UploadFileComponent,
    ImportComponent,
    ItemUploadComponent,
  },
  data() {
    return {
      showQuantityModal: false,
      currentItem: null,
      newQuantity: 0,
      loading: {
        isActive: false,
      },
      enums: {
        statusEnum: statusEnum,
        itemTypeEnum: itemTypeEnum,
        askEnum: askEnum,
        statusEnumArray: {
          [statusEnum.ACTIVE]: this.$t("label.active"),
          [statusEnum.INACTIVE]: this.$t("label.inactive"),
        },
      },
      printLoading: true,
      printObj: {
        id: "print",
        popTitle: this.$t("menu.items"),
      },
      taxProps: {
        search: {
          paginate: 0,
          order_column: "id",
          order_type: "asc",
        },
      },
      categoryProps: {
        search: {
          paginate: 0,
          order_column: "id",
          order_type: "asc",
        },
      },
      props: {
        form: {
          name: "",
          price: "",
          description: "",
          caution: "",
          is_featured: askEnum.YES,
          item_type: itemTypeEnum.VEG,
          item_category_id: null,
          tax_id: null,
          status: statusEnum.ACTIVE,
          order: 1,
        },
        search: {
          paginate: 1,
          page: 1,
          per_page: 10,
          order_column: "id",
          order_type: "desc",
          name: "",
          price: "",
          item_category_id: null,
          status: null,
          order: 1,
          tax_id: null,
          item_type: null,
          is_featured: null,
        },
      },
    };
  },
  mounted() {
    this.list();
    this.loading.isActive = true;
    this.props.search.page = 1;
    this.$store
      .dispatch("itemCategory/lists", this.categoryProps.search)
      .then((res) => {
        this.loading.isActive = false;
      })
      .catch((err) => {
        this.loading.isActive = false;
      });
    this.$store
      .dispatch("tax/lists", this.taxProps.search)
      .then((res) => {
        this.loading.isActive = false;
      })
      .catch((err) => {
        this.loading.isActive = false;
      });
  },
  computed: {
    items: function () {
      return this.$store.getters["item/lists"];
    },
    pagination: function () {
      return this.$store.getters["item/pagination"];
    },
    paginationPage: function () {
      return this.$store.getters["item/page"];
    },
    itemCategories: function () {
      return this.$store.getters["itemCategory/lists"];
    },
    taxes: function () {
      return this.$store.getters["tax/lists"];
    },
    direction: function () {
      return this.$store.getters["frontendLanguage/show"].display_mode ===
        displayModeEnum.RTL
        ? "rtl"
        : "ltr";
    },
  },
  methods: {
    permissionChecker(e) {
      return appService.permissionChecker(e);
    },
    statusClass: function (status) {
      return appService.statusClass(status);
    },
    textShortener: function (text, number = 30) {
      return appService.textShortener(text, number);
    },
    numberOnly: function (e) {
      return appService.floatNumber(e);
    },
    handleSlide: function (id) {
      return appService.handleSlide(id);
    },
    search: function () {
      this.list();
    },
    clear: function () {
      this.props.search.paginate = 1;
      this.props.search.page = 1;
      this.props.search.name = "";
      this.props.search.price = "";
      this.props.search.order = 0;
      this.props.search.item_category_id = null;
      this.props.search.status = null;
      this.props.search.tax_id = null;
      this.props.search.item_type = null;
      this.props.search.is_featured = null;
      this.list();
    },
    list: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("item/lists", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    edit: function (item) {
      appService.sideDrawerShow();
      this.loading.isActive = true;
      this.$store.dispatch("item/edit", item.id);
      this.loading.isActive = false;
      this.props.errors = {};
      this.props.form = {
        name: item.name,
        price: item.flat_price,
        description: item.description,
        caution: item.caution,
        is_featured: item.is_featured,
        item_type: item.item_type,
        tax_id: item.tax_id,
        item_category_id: item.item_category_id,
        status: item.status,
        order: item.order,
      };
    },
    destroy: function (id) {
      appService
        .destroyConfirmation()
        .then((res) => {
          try {
            this.loading.isActive = true;
            this.$store
              .dispatch("item/destroy", { id: id, search: this.props.search })
              .then((res) => {
                this.loading.isActive = false;
                alertService.successFlip(null, this.$t("menu.items"));
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
    xls: function () {
      this.loading.isActive = true;
      this.$store
        .dispatch("item/export", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
          const blob = new Blob([res.data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          });
          const link = document.createElement("a");
          link.href = URL.createObjectURL(blob);
          link.download = this.$t("menu.items");
          link.click();
          URL.revokeObjectURL(link.href);
        })
        .catch((err) => {
          this.loading.isActive = false;
          alertService.error(err.response.data.message);
        });
    },
    uploadModal: function (id) {
      appService.modalShow(id);
    },
    downloadSample: function () {
      this.loading.isActive = true;
      this.$store
        .dispatch("item/downloadSample")
        .then((res) => {
          this.loading.isActive = false;
          const url = window.URL.createObjectURL(new Blob([res.data]));
          const link = document.createElement("a");
          link.href = url;
          link.download = "" + "Items Import Sample." + "xlsx";
          link.click();
          URL.revokeObjectURL(link.href);
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    updateStatus(item) {
      appService
        .updateConfirmation()
        .then((res) => {
          const newStatus =
            item.status === statusEnum.ACTIVE
              ? statusEnum.INACTIVE
              : statusEnum.ACTIVE;
          this.loading.isActive = true;
          this.$store.dispatch("item/edit", item.id);
          this.loading.isActive = false;
          this.props.errors = {};
          this.props.form = {
            name: item.name,
            price: item.flat_price,
            description: item.description,
            caution: item.caution,
            is_featured: item.is_featured,
            item_type: item.item_type,
            tax_id: item.tax_id,
            item_category_id: item.item_category_id,
            status: newStatus,
            order: 1,
          };
          this.save();
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    save: function () {
      try {
        const fd = new FormData();
        fd.append("name", this.props.form.name);
        fd.append("price", this.props.form.price);
        fd.append(
          "item_category_id",
          this.props.form.item_category_id == null
            ? ""
            : this.props.form.item_category_id
        );
        fd.append(
          "tax_id",
          this.props.form.tax_id == null ? "" : this.props.form.tax_id
        );
        fd.append("item_type", this.props.form.item_type);
        fd.append("is_featured", this.props.form.is_featured);
        fd.append("description", this.props.form.description);
        fd.append("caution", this.props.form.caution);
        fd.append("order", 1);
        fd.append("status", this.props.form.status);
        const tempId = this.$store.getters["item/temp"].temp_id;
        this.loading.isActive = true;
        this.$store
          .dispatch("item/save", {
            form: fd,
            search: this.props.search,
          })
          .then((res) => {
            this.loading.isActive = false;
            alertService.success(this.$t("message.update_success"));

            this.props.form = {
              name: "",
              status: statusEnum.ACTIVE,
              order: 1,
            };
            this.list(this.paginationPage);
            this.errors = {};
          })
          .catch((err) => {
            this.loading.isActive = false;
            this.errors = {};
            if (err.response && err.response.data && err.response.data.errors) {
              this.errors = err.response.data.errors;
            } else {
              alertService.error(err.response.data.message);
            }
          });
      } catch (err) {
        this.loading.isActive = false;
        alertService.error(err);
      }
    },
    openQuantityModal(item) {
      this.currentItem = item;
      this.loading.isActive = true;
      this.$store.dispatch("item/edit", item.id);
      this.loading.isActive = false;
      this.props.errors = {};
      this.props.form = {
        name: item.name,
        price: item.flat_price,
        description: item.description,
        caution: item.caution,
        is_featured: item.is_featured,
        item_type: item.item_type,
        tax_id: item.tax_id,
        item_category_id: item.item_category_id,
        status: item.status,
        order: 1,
      };
      this.showQuantityModal = true;
      appService.modalShow("#showQuantityModal");
    },

    closeQuantityModal() {
      this.showQuantityModal = false;
      appService.modalHide("#showQuantityModal");
    },

    updateQuantity() {
      try {
        const fd = new FormData();
        fd.append("name", this.props.form.name);
        fd.append("price", this.props.form.price);
        fd.append(
          "item_category_id",
          this.props.form.item_category_id == null
            ? ""
            : this.props.form.item_category_id
        );
        fd.append(
          "tax_id",
          this.props.form.tax_id == null ? "" : this.props.form.tax_id
        );
        fd.append("item_type", this.props.form.item_type);
        fd.append("is_featured", this.props.form.is_featured);
        fd.append("description", this.props.form.description);
        fd.append("caution", this.props.form.caution);
        fd.append("status", this.props.form.status);
        fd.append("order", this.newQuantity);

        const tempId = this.$store.getters["item/temp"].temp_id;
        this.loading.isActive = true;

        this.$store
          .dispatch("item/save", {
            form: fd,
            search: this.props.search,
          })
          .then((res) => {
            this.loading.isActive = false;
            alertService.success(this.$t("message.update_success"));
            this.closeQuantityModal();
            this.props.form = {
              name: "",
              status: statusEnum.ACTIVE,
              order: 1,
            };
            this.list(this.paginationPage);
            this.errors = {};
          })
          .catch((err) => {
            this.loading.isActive = false;
            this.errors = {};
            if (err.response && err.response.data && err.response.data.errors) {
              this.errors = err.response.data.errors;
            } else {
              alertService.error(err.response.data.message);
            }
          });
      } catch (err) {
        this.loading.isActive = false;
        alertService.error(err);
      }
    },
  },
};
</script>

<style scoped>
@media print {
  .hidden-print {
    display: none !important;
  }
}
</style>

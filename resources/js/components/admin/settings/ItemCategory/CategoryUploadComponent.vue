<template>
    <LoadingComponent :props="loading" />
    <div id="categoryUpload" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.item_categories") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12">
                            <label class="db-field-title required">{{ $t("label.upload_file") }} ({{
                                $t("label.xlsx") }})</label>
                            <input @change="changeFile" v-bind:class="errors.file ? 'invalid' : ''" id="file"
                                type="file" class="db-field-control" ref="fileProperty" accept=".xlsx, .xls" />
                            <small class="db-field-alert" v-if="errors.file">{{ errors.file[0] }}</small>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
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

import LoadingComponent from "../../components/LoadingComponent.vue";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";

export default {
    name: "CategoryUploadComponent",
    components: { LoadingComponent },
    emits:['list'],
    data() {
        return {
            loading: {
                isActive: false,
            },
            file: "",
            search: {
                paginate: 1,
                page: 1,
                per_page: 10,
                order_column: "id",
                order_type: "desc",
            },
            errors: {},
        };
    },
    methods: {
        reset: function () {
            appService.modalHide();
            this.file = "";
            this.errors = {};
            this.$refs.fileProperty.value = null;
        },
        changeFile: function (e) {
            this.file = e.target.files[0];

        },
        save: function () {
            try {
                const fd = new FormData();
                if (this.file) {
                    fd.append('file', this.file);
                }
                this.loading.isActive = true;
                this.$store.dispatch('itemCategory/import', {
                    form: fd,
                    search: this.search
                }).then((res) => {
                    this.loading.isActive = false;
                    appService.modalHide();
                    alertService.successFlip(0,
                        this.$t("menu.item_categories")
                    );
                    this.file = "";
                    this.errors = {};
                    this.$refs.fileProperty.value = null;
                    this.$emit('list');
                }).catch((err) => {
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
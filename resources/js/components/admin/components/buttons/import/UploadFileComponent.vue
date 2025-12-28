<template>
    <button :data-modal="dataModal" type="button" class="db-card-filter-dropdown-menu">
        <i class="lab lab-line-upload-file lab-font-size-17"></i>
        {{ $t('button.upload_file') }}
    </button>
</template>

<script>
export default {
    name: "UploadFileComponent",
    props: ['dataModal']
}
</script>
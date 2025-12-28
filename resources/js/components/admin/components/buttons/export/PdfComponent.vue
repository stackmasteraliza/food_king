<template>
    <a href="#" @click.prevent="pdfDownload" class="db-card-filter-dropdown-menu">
        <i class="lab lab-line-pdf lab-font-size-15"></i>
        {{ $t('button.pdf') }}
    </a>
</template>

<script>

export default {
    name: "PdfComponent",
    props: {
        method: { type: Function }
    },
    methods: {
        pdfDownload: function () {
            this.method();
        }
    }
}
</script>

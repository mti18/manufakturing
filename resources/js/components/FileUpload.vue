<template>
    <file-pond v-bind="$props"></file-pond>
</template>

<script>
import { defineComponent } from "vue";
import vueFilePond from 'vue-filepond';
import "filepond/dist/filepond.min.css";

import FilePondPluginImageExifOrientation from "filepond-plugin-image-exif-orientation";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFilePoster from "filepond-plugin-file-poster";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";

import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css";
import "filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css";

const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview, FilePondPluginImageExifOrientation, FilePondPluginFilePoster);

export default defineComponent({
    name: 'file-upload',
    components: {
        FilePond
    },
});
</script>
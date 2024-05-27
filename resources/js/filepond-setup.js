import * as FilePond from 'filepond';
import 'filepond-plugin-file-encode';
import 'filepond-plugin-file-validate-size';
import 'filepond-plugin-file-validate-type';
import 'filepond-plugin-image-preview';
import 'filepond-plugin-image-resize';

FilePond.registerPlugin(
    FilePondPluginFileEncode,
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    FilePondPluginImageResize
);

window.initFilePond = (selector, options = {}) => {
    const elements = document.querySelectorAll(selector);
    elements.forEach(element => {
        FilePond.create(element, options);
    });
};

/* Import TinyMCE */

import tinymce from 'tinymce';

/* Default icons are required for TinyMCE 5.3 or above */
import 'tinymce/icons/default';

/* A theme is also required */
import 'tinymce/themes/silver';
import 'tinymce/models/dom';

/* Import the skin */
import 'tinymce/skins/ui/oxide/skin.css';

/* Import plugins */
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/code';
import 'tinymce/plugins/emoticons';
import 'tinymce/plugins/emoticons/js/emojis';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/table';


export default class Editor {
    static render (target: HTMLElement) {
        /* Initialize TinyMCE */
        return tinymce.init({
            target: target,
            plugins: 'advlist code emoticons link lists table',
            toolbar: 'bold italic forecolor backcolor | bullist numlist | link emoticons',
            skin: false,
            content_css: false,
            content_style: '',
            promotion: false,
            branding: false,
            setup: function (editor) {
                editor.on('change', function (event) {
                    editor.targetElm.innerHTML = editor.getContent();
                });
            }
        });
    }
}


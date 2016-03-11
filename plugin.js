(function ($) {
    tinymce.create('tinymce.plugins.TinyMCEHatebuButton', {
        init: function (ed, url) {

            ed.addButton('tinymce_hatebu_button', {
                tooltip: "はてなブックマーク",
                cmd: 'dialog',
                image: url + '/images/icon.png'
            });

            ed.addCommand('dialog', function () {
                ed.windowManager.open({
                    title: 'はてなブックマーク',
                    file: url + '/dialog.php',
                    width: 508,
                    height: 308,
                    inline: 1,
                    buttons: [{
                            text: "Cancel",
                            id: "cancel",
                            class: "cancel",
                            onclick: "close"
                        }]
                }, {
                    plugin_url: url
                });
            });

            ed.onNodeChange.add(function (ed, cm, n) {
                cm.setActive('tinymce_hatebu_button', n.nodeName == 'button');
            });
        }
    });

    // Register plugin
    tinymce.PluginManager.add('tinymce_hatebu_button', tinymce.plugins.TinyMCEHatebuButton);

})(jQuery);
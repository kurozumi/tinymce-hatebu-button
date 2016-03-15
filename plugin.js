(function ($) {

    // Register plugin
    tinymce.PluginManager.add('tinymce_hatebu_button', function (editor, url) {
        
        function showDialog() {
            var win = editor.windowManager.open({
                title: "はてなブックマーク",
                file: url + '/dialog.php',
                width: 780,
                height: 300,
                inline: 1,
                buttons: [{
                        text: "Close",
                        id: "close",
                        class: "close",
                        onclick: "close"
                }]
            }, {
                plugin_url: url,
                jquery: $
            });
        }

        editor.addButton('tinymce_hatebu_button', {
            image: url + '/images/icon.png',
            tooltip: 'はてなブックマーク',
            onclick: showDialog
        });

    });

})(jQuery);

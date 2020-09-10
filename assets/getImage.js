
App.$(document).on('init-wysiwyg-editor', function(e, editor) {

    tinymce.PluginManager.add('mpgetimage', function(ed) {

         var openDialog = function() {

            var node = ed.selection.getNode(),
                width = '800',
                height = '',
                method = 'bestFit'
                quality = '80'
                options = [],
                asset = {},
                asset_id = null,
                src = node.getAttribute('src');

            if (src) {

                // for some reason, URLSearchParams doesn't detect the first param after "?"
                var query = src.split('?')[1];

                var params = new URLSearchParams(query);

                if (params.has('w')) width = params.get('w');
                if (params.has('h')) height = params.get('h');
                if (params.has('m')) method = params.get('m');
                if (params.has('q')) quality = params.get('q');
                if (params.has('src')) asset_id = params.get('src');

            }

            var dialog = UIkit.modal.dialog([
'<div>',
    '<div class="uk-modal-header uk-text-large">'+App.i18n.get('Select asset')+'</div>',
    '<cp-assets path="'+(options.path || '')+'" typefilter="'+(options.typefilter || '')+'" modal="true"></cp-assets>',
    '<div class="uk-grid">',
        '<div class="uk-panel uk-width-1-3 uk-width-medium-1-6">',
            '<label class="uk-display-block uk-margin-small">'+App.i18n.get('Width')+'</label>',
            '<input class="uk-width-1-1" type="number" value="'+width+'" steps="10" data-width />',
        '</div>',
        '<div class="uk-panel uk-width-1-3 uk-width-medium-1-6">',
            '<label class="uk-display-block uk-margin-small">'+App.i18n.get('Height')+'</label>',
            '<input class="uk-width-1-1" type="number" value="'+height+'" steps="10" data-height />',
        '</div>',
        '<div class="uk-panel uk-width-1-3 uk-width-medium-1-6">',
            '<label class="uk-display-block uk-margin-small">'+App.i18n.get('Method')+'</label>',
            '<select class="uk-width-1-1" data-method>',
                '<option value="bestFit"'+ (method == 'bestFit' && ' selected') +'>bestFit</option>',
                '<option value="thumbnail"'+ (method == 'thumbnail' && ' selected') +'>thumbnail</option>',
            '</select>',
        '</div>',
        '<div class="uk-panel uk-width-1-3 uk-width-medium-1-6">',
            '<label class="uk-display-block uk-margin-small">'+App.i18n.get('Quality')+'</label>',
            '<input class="uk-width-1-1" type="number" value="'+quality+'" steps="5" min="0" max="100" data-quality />',
        '</div>',
    '</div>',
    '<div class="uk-modal-footer uk-text-right">',
        '<button class="uk-button uk-button-primary uk-margin-right uk-button-large js-select-button">'+App.i18n.get('Select')+'</button>',
        '<a class="uk-button uk-button-large uk-button-link uk-modal-close">'+App.i18n.get('Close')+'</a>',
    '</div>',
'</div>'
            ].join(''), {modal:false});

            // large dialog
            dialog.dialog.addClass('uk-modal-dialog-large');

            // mount content of dialog
            riot.mount(dialog.element[0], '*', options);

            var selectButton    = dialog.dialog.find('.js-select-button'),
                assetsComponent = dialog.dialog.find('cp-assets')[0]._tag;

            selectButton.on('click', function() {

                if (!asset || !asset.mime) {
                    App.ui.notify('No image selected', 'danger');
                    return;
                }

                var width   = dialog.dialog.find('[data-width]')[0].value,
                    height  = dialog.dialog.find('[data-height]')[0].value,
                    method  = dialog.dialog.find('[data-method]')[0].value,
                    quality = dialog.dialog.find('[data-quality]')[0].value,
                    content = '';

                if (asset.mime.match(/^image\//)) {

                    // might break with sub folders... needs some more tests
                    // var src = SITE_URL + '/getImage?src=' + asset._id;
                    var src = MP_SITE_URL + '/getImage?src=' + asset._id;

                    // skip default and empty values
                    if (!!width.trim() && width != '800')       src += '&w=' + width;
                    if (!!height.trim())                        src += '&h=' + height;
                    if (!!method.trim() && method != 'bestFit') src += '&m=' + method;
                    if (!!quality.trim() && quality != '80')    src += '&q=' + quality;

                    content = '<img src="'+ src +'" alt="'+(asset.title || 'image')+'">';
                } else {
                    // to do...
                    content = '<a href="' + ASSETS_URL+asset.path + '">'+asset.title+'<a>';
                }

                ed.insertContent(content);

                dialog.hide();

            });

            dialog.on('selectionchange', function(e, s) {

                if (Array.isArray(s) && s.length) {

                    // make multiple select to single select
                    s = s.slice(-1);
                    assetsComponent.selected = s;

                    asset = s[0];

                }

            });

            dialog.on('show.uk.modal', function(e) {

                if (asset_id) {

                    setTimeout(function() {

                        var selectedAsset = assetsComponent.assets.find(obj => {
                            return obj._id === asset_id;
                        });

                        asset = selectedAsset;

                        assetsComponent.selected.push(selectedAsset);
                        assetsComponent.update();

                    }, 500);

                }

            });

            dialog.show();

        }

        ed.addMenuItem('mpgetimage', {
            icon: 'image',
            text: App.i18n.get('Edit/Insert image'),
            onclick: function(){
                openDialog();
            },
            context: 'insert',
            prependToContext: true
        });

        ed.addButton('mpgetimage', {
            icon: 'image',
            tooltip: App.i18n.get('Edit/Insert image'),
            onclick: function(){
                openDialog();
            },
            stateSelector: 'img',
        });

    });

    // don't enable automatically, if EditorFormats addon is installed
    if (editor.settings.modified === undefined) {

        // enable plugin
        if (!editor.settings.plugins.match(/mpgetimage/)) {
            editor.settings.plugins = editor.settings.plugins + ' mpgetimage';
        }

        // add toolbar button
        if (typeof editor.settings.toolbar == 'undefined') {
            // add default toolbar buttons
            editor.settings.toolbar = 'undo redo | styleselect | bold italic | alignleft'
                                    + 'aligncenter alignright alignjustify | '
                                    + 'bullist numlist outdent indent | link image';
        }
        if (!editor.settings.toolbar.match(/mpgetimage/)) {
            editor.settings.toolbar += ' | mpgetimage';
        }

    }

});

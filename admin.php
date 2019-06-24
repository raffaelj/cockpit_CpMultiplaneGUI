<?php

$this->on('admin.init', function() {
    
    // add custom assets
    $this('admin')->addAssets('cpmultiplanegui:assets/field-simple-gallery.tag');
    $this('admin')->addAssets('cpmultiplanegui:assets/getImage.js');

    if ($this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage')) {

        // bind admin routes
        $this->bindClass('CpMultiplaneGUI\\Controller\\Admin', 'cpmultiplane');

        // add settings entry
        $this->on('cockpit.view.settings.item', function () {
            $this->renderView('cpmultiplanegui:views/partials/settings.php');
        });

    }

    // custom nav
    // add menu item for all Collections and Singletons from "menu.aside"
    $this->on('app.layout.contentbefore', function() {

        $collections = [];
        if (isset($this->modules['collections'])) {

            $cols = $this->module('collections')->getCollectionsInGroup();

            foreach($cols as $collection) {
                if (isset($collection['in_menu']) && $collection['in_menu']) {
                    $collection['active'] = preg_match('#^/collections/(entries|entry)/'.$collection['name'].'#', $this['route']);
                    $collections[] = $collection;
                }
            }

        }

        $singletons = [];
        if (isset($this->modules['singletons'])) {

            $sings = $this->module('singletons')->getSingletonsInGroup();

            foreach($sings as $singleton) {
                if (isset($singleton['in_menu']) && $singleton['in_menu']) {
                    $singleton['active'] = preg_match('#^/singletons/form/'.$singleton['name'].'#', $this['route']);
                    $singletons[] = $singleton;
                }
            }

        }

        // add assetsmanager to custom icons
        $other = [];

        $other[] = [
            'name' => 'assets',
            'label' => $this('i18n')->get('Assets'),
            'route' => '/assetsmanager',
            'icon'  => 'assets.svg',
            'active' => strpos($this['route'], '/assetsmanager') === 0
        ];

        $this->renderView('cpmultiplanegui:views/partials/nav.php', compact('collections', 'singletons', 'other'));

    });

    // side bar options for pages
    $this->on('collections.entry.aside', function() {

        $pages = $this->retrieve('multiplane/pages', 'pages');
        $posts = $this->retrieve('multiplane/pages', 'posts');

        if (strpos($this['route'], '/collections/entry/'.$pages) === 0) {
            $this->renderView('cpmultiplanegui:views/partials/pages.entry.aside.php');
        }

        elseif (strpos($this['route'], '/collections/entry/'.$posts) === 0) {
            $this->renderView('cpmultiplanegui:views/partials/posts.entry.aside.php');
        }

    });

    // quick hack to add options to EditorFormats addon
    // As long as @pauloamgomes doesn't change the form id, the route or the variable names, it will work
    // route: https://github.com/pauloamgomes/CockpitCms-EditorFormats/blob/master/Controller/Admin.php#L30
    // form id: https://github.com/pauloamgomes/CockpitCms-EditorFormats/blob/master/views/formats/format.php#L9
    // toolbar: https://github.com/pauloamgomes/CockpitCms-EditorFormats/blob/master/views/formats/format.php#L129
    // format.plugins: https://github.com/pauloamgomes/CockpitCms-EditorFormats/blob/master/views/formats/format.php#L127
    if (isset($this['modules']['editorformats'])) {

        // load only if EditorFormats Controller was called
        $this->on('app.editorformats.controller.admin.init', function() {

            if (strpos($this['route'], '/editor-formats/format') !== false) {

                $this->on('app.layout.contentafter', function() {

                    echo '<div class="uk-hidden" id="add-mpgetimage-to-editor-formats">';
                    // toolbar option
                    echo '{ toolbar.indexOf("mpgetimage") === -1 ? toolbar.push("mpgetimage") : "" }';
                    // plugin option
                    echo '{ format.plugins.mpgetimage = format.plugins.mpgetimage || false }';
                    echo '</div>';

                    // move div inside riot view to update the variables
                    echo '<script>App.$("#account-form").prepend(App.$("#add-mpgetimage-to-editor-formats"));</script>';

                });

            }

        });

    }

});

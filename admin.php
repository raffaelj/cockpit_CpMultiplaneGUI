<?php

$this->on('admin.init', function() {
    
    // add custom assets
    $this('admin')->addAssets('cpmultiplanegui:assets/field-simple-gallery.tag');

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

        if (strpos($this['route'], '/collections/entry/'.$pages) === 0) {
            $this->renderView('cpmultiplanegui:views/partials/pages.entry.aside.php', compact('collections', 'singletons', 'other'));
        }

    });

});

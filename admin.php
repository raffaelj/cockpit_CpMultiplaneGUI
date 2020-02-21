<?php

$this->on('admin.init', function() {
    
    // add custom assets
    $this('admin')->addAssets('cpmultiplanegui:assets/components/field-simple-gallery.tag');
    $this('admin')->addAssets('cpmultiplanegui:assets/components/field-seo.tag');
    $this('admin')->addAssets('cpmultiplanegui:assets/components/field-key-value-pair.tag');
    $this('admin')->addAssets('cpmultiplanegui:assets/getImage.js');

    if ($this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage')) {

        // bind admin routes
        $this->bindClass('CpMultiplaneGUI\\Controller\\Admin', 'cpmultiplane');

        // add settings entry
        $this->on('cockpit.view.settings.item', function () {
            $this->renderView('cpmultiplanegui:views/partials/settings.php');
        });

    }

    $config = $this->module('cpmultiplanegui')->getConfig();

    // display "Show in menu" toggle
    $this->on('collections.settings.aside', function() {
        $this->renderView('cpmultiplanegui:views/partials/collections.settings.aside.php');
    });

    $this->on('forms.settings.aside', function() {
        $this->renderView('cpmultiplanegui:views/partials/forms.settings.aside.php');
    });

    $this->on('singletons.settings.aside', function() {
        $this->renderView('cpmultiplanegui:views/partials/singletons.settings.aside.php');
    });

    // custom nav
    // add menu item for all Collections and Singletons from "menu.aside"
    if (isset($config['guiDisplayCustomNav']) && $config['guiDisplayCustomNav']) {

        $this->on('app.layout.contentbefore', function() {

            // check, if `in_menu` feature exists - was dropped between Cockpit 0.9.2 and 0.9.3
            $in_menu = $this->path('collections:views/partials/menu.php') ? 'in_menu' : 'gui_in_header';

            $collections = [];
            if (isset($this->modules['collections'])) {

                $cols = $this->module('collections')->getCollectionsInGroup();

                foreach($cols as $collection) {
                    if (isset($collection[$in_menu]) && $collection[$in_menu]) {
                        $collection['active'] = preg_match('#^/collections/(entries|entry)/'.$collection['name'].'#', $this['route']);
                        $collections[] = $collection;
                    }
                }

            }

            $singletons = [];
            if (isset($this->modules['singletons'])) {

                $sings = $this->module('singletons')->getSingletonsInGroup();

                foreach($sings as $singleton) {
                    if (isset($singleton[$in_menu]) && $singleton[$in_menu]) {
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

    }

    // side bar options for pages
    $this->on('collections.entry.aside', function($collection) {

        $pages = $this->retrieve('multiplane/pages', 'pages');
        $posts = $this->retrieve('multiplane/pages', 'posts');

        if ($collection == $pages) {

            $_forms = $this->module('forms')->forms();
            $forms  = [];

            foreach ($_forms as $name => $meta) {
               $forms[] = [
                 'name' => $name,
                 'label' => !empty($meta['label']) ? $meta['label'] : $name,
               ];
            }

            // sort forms
            usort($forms, function($a, $b) {
                return mb_strtolower($a['label']) <=> mb_strtolower($b['label']);
            });

            $_collections = $this->module('collections')->collections();
            $collections = [];
            foreach ($_collections as $name => $meta) {
               if ($name == $collection) continue;
               $collections[] = [
                 'name' => $name,
                 'label' => !empty($meta['label']) ? $meta['label'] : $name,
               ];
            }

            // sort forms
            usort($collections, function($a, $b) {
                return mb_strtolower($a['label']) <=> mb_strtolower($b['label']);
            });

            // $this->renderView('cpmultiplanegui:views/partials/pages.entry.aside.php');
            $this->renderView('cpmultiplanegui:views/partials/pages.entry.aside.php', compact('forms', 'collections'));
        }

        elseif ($collection == $posts) {
            $this->renderView('cpmultiplanegui:views/partials/posts.entry.aside.php');
        }

    });

    // link to website in system menu
    $this->on('cockpit.menu.system', function() {

        $config = $this->module('cpmultiplanegui')->getConfig();

        $url = isset($config['siteUrl']) ? $config['siteUrl'] : $this['site_url'];

        $this->renderView('cpmultiplanegui:views/partials/system.menu.php', compact('url'));

    });

    // quick hack to add options to EditorFormats addon
    // As long as @pauloamgomes doesn't change the form id, the route or the variable names, it will work
    // route:          https://github.com/pauloamgomes/CockpitCms-EditorFormats/blob/master/Controller/Admin.php#L30
    // form id:        https://github.com/pauloamgomes/CockpitCms-EditorFormats/blob/master/views/formats/format.php#L9
    // toolbar:        https://github.com/pauloamgomes/CockpitCms-EditorFormats/blob/master/views/formats/format.php#L129
    // format.plugins: https://github.com/pauloamgomes/CockpitCms-EditorFormats/blob/master/views/formats/format.php#L127
    if (isset($this['modules']['editorformats'])) {

        // load only if EditorFormats Controller was called
        $this->on('app.editorformats.controller.admin.init', function() {

            if (strpos($this['route'], '/editor-formats/format') !== false) {

                $this->on('app.layout.contentafter', function() {

                    $this->renderView('cpmultiplanegui:views/partials/editorformats.options.php');

                });

            }

        });

    }

});

// unique check for startpage toggle
$this->on('admin.init', function() {

    $config = $this->module('cpmultiplanegui')->getConfig();

    $pages = !empty($config['pages']) ? $config['pages'] : 'pages';

    $this->on("collections.save.before.{$pages}", function($name, &$entry, $isUpdate) {

        if (isset($entry['startpage']) && $entry['startpage'] == true) {

            // check, if another page exists, that was the startpage before

            $filter = ['startpage' => true];

            if ($isUpdate && isset($entry['_id'])) {
                $filter['_id'] = ['$not' => $entry['_id']];
            }

            $check = $this->module('collections')->findOne($name, $filter);

            if ($check) {

                // set old startpage to false

                $check['startpage'] = false;

                $this->module('collections')->save($name, [$check], ['revision' => true]);

            }

        }

    });

});

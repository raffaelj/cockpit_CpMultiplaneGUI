<?php

// init + load i18n
$locale = $app->module('cockpit')->getUser('i18n', $app('i18n')->locale);

if ($translationspath = $app->path("cpmultiplanegui:i18n/{$locale}.php")) {
    $app('i18n')->load($translationspath, $locale);
}

$this->on('admin.init', function() {

    // add js MP_SITE_URL var for getImage route (TinyMCE plugin)
    $this->on('app.layout.header', function() {
        $url = $this->module('cpmultiplanegui')->getSiteUrl(true);
        echo '<script>var MP_SITE_URL = "' . $url . '"</script>';
    });

    // add custom assets
    $this('admin')->addAssets([
        'cpmultiplanegui:assets/components/field-simple-gallery.tag',
        'cpmultiplanegui:assets/components/field-seo.tag',
        'cpmultiplanegui:assets/components/field-key-value-pair.tag',
        'cpmultiplanegui:assets/getImage.js'
    ]);

    // bind admin routes
    $this->bindClass('CpMultiplaneGUI\\Controller\\Admin', 'multiplane');

    // add settings entry
    if ($this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage')) {
        $this->on('cockpit.view.settings.item', function () {
            $this->renderView('cpmultiplanegui:views/partials/settings.php');
        });
    }

    // add to modules menu
    if ($this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage')) {
        $this->helper('admin')->addMenuItem('modules', [
            'label'  => 'Multiplane',
            'icon'   => 'cpmultiplanegui:icon.svg',
            'route'  => '/multiplane',
            'active' => strpos($this['route'], '/multiplane') === 0
        ]);
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

            $collections = [];
            if (isset($this->modules['collections'])) {

                $_collections = $this->module('collections')->getCollectionsInGroup();

                foreach($_collections as $c) {
                    if (isset($c['multiplane']['gui_in_header'])
                      && $c['multiplane']['gui_in_header'] === true) {

                        $collections[] = [
                            'name' => $c['name'],
                            'label' => !empty($c['label']) ? $c['label'] : $c['name'],
                            'icon' => $c['icon'] ?? '',
                            'color' => $c['color'] ?? '',
                            'active' => preg_match('#^/collections/(entries|entry)/'.$c['name'].'$#', $this['route']),
                        ];
                    }
                }

            }

            $singletons = [];
            if (isset($this->modules['singletons'])) {

                $_singletons = $this->module('singletons')->getSingletonsInGroup();

                foreach($_singletons as $s) {
                    if (isset($s['multiplane']['gui_in_header'])
                      && $s['multiplane']['gui_in_header'] === true) {

                        $singletons[] = [
                            'name' => $s['name'],
                            'label' => !empty($s['label']) ? $s['label'] : $s['name'],
                            'icon' => $s['icon'] ?? '',
                            'color' => $s['color'] ?? '',
                            'active' => preg_match('#^/singletons/form/'.$s['name'].'$#', $this['route']),
                        ];
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

        $_collection = $this->module('collections')->collection($collection);

        if ($_collection['multiplane']['sidebar'] ?? false) {

            $type = $_collection['multiplane']['type'] ?? 'subpages';

            $config = $this->module('cpmultiplanegui')->getConfig();

            if ($type == 'pages') {

                $_forms = $config['use']['forms'] ?? [];
                $forms  = [];
                foreach ($_forms as $name) {
                    $meta = $this->module('forms')->form($name);
                    if (!$meta) continue;
                    $forms[] = [
                        'name' => $name,
                        'label' => !empty($meta['label']) ? $meta['label'] : $name,
                    ];
                }
                // sort forms
                usort($forms, function($a, $b) {
                    return mb_strtolower($a['label']) <=> mb_strtolower($b['label']);
                });

                $collections = [];
                $_collections = $config['use']['collections'] ?? [];
                foreach($_collections as $name) {

                    if ($name == $collection) continue;

                    $meta = $this->module('collections')->collection($name);
                    if (!$meta) continue;

                    if (!isset($meta['multiplane']['type'])
                      || $meta['multiplane']['type'] != 'subpages' ) {
                        continue;
                    }

                    $collections[] = [
                        'name'  => $name,
                        'label' => !empty($meta['label']) ? $meta['label'] : $name,
                    ];
                }
                // sort collections
                usort($collections, function($a, $b) {
                    return mb_strtolower($a['label']) <=> mb_strtolower($b['label']);
                });

                $this->renderView('cpmultiplanegui:views/partials/pages.entry.aside.php', compact('forms', 'collections'));
            }

            else {
                $this->renderView('cpmultiplanegui:views/partials/posts.entry.aside.php');
            }

        }

    });

    // link to website in system menu
    $this->on('cockpit.menu.system', function() {

        $url = $this->module('cpmultiplanegui')->getSiteUrl();
        if ($url == '') $url = '/';

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

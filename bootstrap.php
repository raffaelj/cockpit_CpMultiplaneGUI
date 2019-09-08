<?php
/**
 * GUI and some ui tweaks for Cockpit CMS, that fit with CpMultiplane frontend - work in progress
 * 
 * @see       https://github.com/raffaelj/cockpit_CpMultiplaneGUI
 * @see       https://github.com/raffaelj/CpMultiplane
 * @see       https://github.com/agentejo/cockpit/
 * 
 * @version   0.1.5
 * @author    Raffael Jesche
 * @license   MIT
 */


$this->module('cpmultiplanegui')->extend([

    'getConfig' => function() {

        static $config;

        if (!isset($config)) {

            $config = array_replace_recursive(
                $this->app->storage->getKey('cockpit/options', 'multiplane', []),
                $this->app->retrieve('multiplane', [])
            );

        }

        return $config;

    },

    'findMultiplaneDir' => function() {

        // to do...

        if (file_exists(dirname(COCKPIT_DIR) . '/modules/Multiplane/bootstrap.php')) {
            return dirname(COCKPIT_DIR);
        }

    },

    'createDefaults' => function() {
        // to do...
    },

]);

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


// acl
$this('acl')->addResource('cpmultiplanegui', ['manage']);

// admin ui
if (COCKPIT_ADMIN && !COCKPIT_API_REQUEST) {
    include_once(__DIR__ . '/admin.php');
}

<?php
/**
 * GUI and some ui tweaks for Cockpit CMS, that fit with CpMultiplane frontend - work in progress
 * 
 * @see       https://github.com/raffaelj/cockpit_CpMultiplaneGUI
 * @see       https://github.com/agentejo/cockpit/
 * 
 * @version   0.1.4
 * @author    Raffael Jesche
 * @license   MIT
 */


$this->module('cpmultiplanegui')->extend([

    'getConfig' => function() {

        $config = array_replace_recursive(
            $this->app->storage->getKey('cockpit/options', 'multiplane', []),
            $this->app->retrieve('multiplane', [])
        );

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


// acl
$this('acl')->addResource('cpmultiplanegui', ['manage']);

// admin ui
if (COCKPIT_ADMIN && !COCKPIT_API_REQUEST) {
    include_once(__DIR__ . '/admin.php');
}

<?php
/**
 * GUI and some ui tweaks for Cockpit CMS, that fit with CpMultiplane frontend - work in progress
 * 
 * @see       https://github.com/raffaelj/cockpit_CpMultiplaneGUI
 * @see       https://github.com/raffaelj/CpMultiplane
 * @see       https://github.com/agentejo/cockpit/
 * 
 * @version   0.1.6
 * @author    Raffael Jesche
 * @license   MIT
 */


$this->module('cpmultiplanegui')->extend([

    'createProfile' => function($name, $data = []) {

        if (!trim($name)) {
            return false;
        }

        $configpath = $this->app->path('#storage:').'/multiplane';

        if (!$this->app->path('#storage:multiplane')) {

            if (!$this->app->helper('fs')->mkdir($configpath)) {
                return false;
            }
        }

        if ($this->exists($name)) {
            return false;
        }

        $time = time();

        $profile = array_replace_recursive([
            'name'      => $name,
            'label'     => $name,
            '_id'       => uniqid($name),
            '_created'  => $time,
            '_modified' => $time
        ], $data);

        $export = $this->app->helper('utils')->var_export($profile, true);

        if (!$this->app->helper('fs')->write("#storage:multiplane/{$name}.profile.php", "<?php\n return {$export};")) {
            return false;
        }

        $this->app->trigger('cpmultiplanegui.createprofile', [$profile]);

        return $profile;

    },

    'updateProfile' => function($name, $data) {

        $metapath = $this->app->path("#storage:multiplane/{$name}.profile.php");

        if (!$metapath) {
            return false;
        }

        $data['_modified'] = time();

        $profile  = include($metapath);
        $profile  = array_merge($profile, $data);
        $export   = var_export($profile, true);

        if (!$this->app->helper('fs')->write($metapath, "<?php\nreturn {$export};")) {
            return false;
        }

        $this->app->trigger('cpmultiplanegui.updateprofile', [$profile]);
        $this->app->trigger("cpmultiplanegui.updateprofile.{$name}", [$profile]);

        // if (function_exists('opcache_reset')) opcache_reset();

        return $profile;
    },

    'saveProfile' => function($name, $data = []) {

        if (!trim($name)) {
            return false;
        }

        return isset($data['_id']) ? $this->updateProfile($name, $data) : $this->createProfile($name, $data);
    },

    'removeProfile' => function($name) {

        if ($profile = $this->profile($name)) {

            $this->app->helper('fs')->delete("#storage:multiplane/{$name}.profile.php");

            $this->app->trigger('cpmultiplanegui.removeprofile', [$name]);
            $this->app->trigger("cpmultiplanegui.removeprofile.{$name}", [$name]);

            return true;
        }

        return false;
    },

    'exists' => function($name) {
        return $this->app->path("#storage:multiplane/{$name}.profile.php");
    },

    'profile' => function($name) {

        static $profiles; // cache

        if (is_null($profiles)) {
            $profiles = [];
        }

        if (!is_string($name)) {
            return false;
        }

        if (!isset($profiles[$name])) {

            $profiles[$name] = false;

            if ($path = $this->exists($name)) {
                $profiles[$name] = include($path);
            }
        }

        return $profiles[$name];
    },

    'profiles' => function($extended = false) {

        $stores = [];

        foreach ($this->app->helper('fs')->ls('*.profile.php', '#storage:multiplane') as $path) {

            $store = include($path->getPathName());

            if ($extended) {
                $store['itemsCount'] = $this->count($store['name']);
            }

            $stores[$store['name']] = $store;
        }

        return $stores;
    },

    'getConfig' => function() {

        static $config;

        if (!isset($config)) {

            $config = array_replace_recursive(
                $this->app->storage->getKey('cockpit/options', 'multiplane', []), // old ui
                $this->app->retrieve('multiplane', []) // config file
            );

            if (isset($config['profile']) && $profile = $this->profile($config['profile'])) {
                $config = array_replace_recursive($config, $profile);
            }

        }

        return $config;

    },

    'findMultiplaneDir' => function() {

        // to do...

        if (file_exists(dirname(COCKPIT_DIR) . '/modules/Multiplane/bootstrap.php')) {
            return dirname(COCKPIT_DIR);
        }

        return false;

    },

    'getSiteUrl' => function($absolute = false) {

        $url = '';

        if ($path = $this->findMultiplaneDir()) {
            $url = $this->app->pathToUrl($path);
        }

        return $absolute ? $this->app['site_url'] . $url : $url;

    },

    'createDefaults' => function() {
        // to do...
    },

]);


// ACL
$app('acl')->addResource('cpmultiplanegui', ['create', 'delete', 'manage']);

// admin ui
if (COCKPIT_ADMIN && !COCKPIT_API_REQUEST) {
    include_once(__DIR__ . '/admin.php');
}

<?php
/**
 * GUI and some ui tweaks for Cockpit CMS, that fit with CpMultiplane frontend - work in progress
 * 
 * @see       https://github.com/raffaelj/cockpit_CpMultiplaneGUI
 * @see       https://github.com/raffaelj/CpMultiplane
 * @see       https://github.com/agentejo/cockpit/
 * 
 * @version   0.2.2
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
            '_id'       => $name,
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
        $export   = $this->app->helper('utils')->var_export($profile, true);

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

            $config = $this->app->retrieve('multiplane', []);

            if (isset($config['profile']) && $profile = $this->profile($config['profile'])) {
                $config = array_replace_recursive($config, $profile);
            }

        }

        return $config;

    },

    'findMultiplaneDir' => function() {

        // to do...
        $checkFile = '/modules/Multiplane/bootstrap.php';

        if (\defined('MP_COCKPIT_ADMIN_ROUTE') && \basename(\dirname(COCKPIT_DIR)) == 'lib') {

            // mp lib skeleton -  I expect Cockpit and CpMultiplane in parallel inside lib folder

            $checkDir = dirname(COCKPIT_DIR) . '/CpMultiplane';

            if (\file_exists($checkDir . $checkFile)) {
                return $checkDir;
            }

        }

        elseif (\file_exists(\dirname(COCKPIT_DIR) . $checkFile)) {

            // default usage - I expect Cockpit to be in a sub folder of CpMultiplane

            return \dirname(COCKPIT_DIR);
        }

        return false;

    },

    'getSiteUrl' => function($absolute = false) {

        $url = '';

        if ($path = $this->findMultiplaneDir()) {

            if (\defined('MP_COCKPIT_ADMIN_ROUTE') && \basename(\dirname(COCKPIT_DIR)) == 'lib') {
                $path = \dirname(\dirname($path));
            }

            $url = $this->app->pathToUrl($path);
        }
        if ($url == '/') $url = '';

        return $absolute ? $this->app['site_url'] . $url : $url;

    },

    'createDefaults' => function() {
        // to do...
    },

]);

// pass config to UniqueSlugs addon if not already present, requires UniqueSlugs version 0.5.3
$this->on('cockpit.bootstrap', function() {

    if (!isset($this['modules']['uniqueslugs'])) return;

    $config = $this->module('cpmultiplanegui')->getConfig();

    if (empty($config['use']['collections'])) return;

    $isMultilingual = $config['isMultilingual'] ?? false;

    $uConfig = $this->retrieve('unique_slugs');

    foreach($config['use']['collections'] as $col) {

        $fieldName = 'title';

        if (!isset($uConfig['collections'][$col])) {
            $uConfig['collections'][$col] = $fieldName;
        } else {
            $fieldName = $uConfig['collections'][$col];
        }

        if ($isMultilingual && !isset($uConfig['localize'][$col])) {
            $uConfig['localize'][$col] = $fieldName;
        }
    }

    $this->set('unique_slugs', $uConfig);

}, 200); // change config with priority higher than 100

// ACL
$app('acl')->addResource('cpmultiplanegui', ['create', 'delete', 'manage']);

// admin ui
if (COCKPIT_ADMIN && !COCKPIT_API_REQUEST) {
    include_once(__DIR__ . '/admin.php');
}

// CLI
if (COCKPIT_CLI) {
    $this->path('#cli', __DIR__ . '/cli');
}

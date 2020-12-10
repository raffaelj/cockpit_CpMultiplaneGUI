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

    'fieldNames' => [                             // field mappings to default field names
        'slug'              => '_id',
        'nav'               => 'nav',
        'permalink'         => 'permalink',
        'published'         => 'published',
        'startpage'         => 'startpage',
        'title'             => 'title',
        'content'           => 'content',
        'description'       => 'description',
        'excerpt'           => 'excerpt',
        'type'              => 'type',            // only if pageTypeDetection == 'type'
        'subpagemodule'     => 'subpagemodule',
        'privacypage'       => 'privacypage',
        'seo'               => 'seo',
        'featured_image'    => 'featured_image',
        'background_image'  => 'background_image',
        'logo'              => 'logo',            // only in site
        'tags'              => 'tags',
        'category'          => 'category',        // not used for now, will be like tags
        'contactform'       => 'contactform',
    ],

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

        $time = \time();

        $profile = \array_replace_recursive([
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

    }, // end of createProfile()

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

    }, // end of updateProfile()

    'saveProfile' => function($name, $data = []) {

        if (!trim($name)) {
            return false;
        }

        return isset($data['_id']) ? $this->updateProfile($name, $data) : $this->createProfile($name, $data);

    }, // end of saveProfile()

    'removeProfile' => function($name) {

        if ($profile = $this->profile($name)) {

            $this->app->helper('fs')->delete("#storage:multiplane/{$name}.profile.php");

            $this->app->trigger('cpmultiplanegui.removeprofile', [$name]);
            $this->app->trigger("cpmultiplanegui.removeprofile.{$name}", [$name]);

            return true;
        }

        return false;

    }, // end of removeProfile()

    'exists' => function($name) {

        return $this->app->path("#storage:multiplane/{$name}.profile.php");

    }, // end of exists()

    'profile' => function($name) {

        static $profiles; // cache

        if (\is_null($profiles)) {
            $profiles = [];
        }

        if (!\is_string($name)) {
            return false;
        }

        if (!isset($profiles[$name])) {

            $profiles[$name] = false;

            if ($path = $this->exists($name)) {
                $profiles[$name] = include($path);
            }
        }

        return $profiles[$name];

    }, // end of profile()

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

    }, // end of profiles()

    'getConfig' => function() {

        static $config;

        if (!isset($config)) {

            $config = $this->app->retrieve('multiplane', []);

            if (isset($config['profile']) && $profile = $this->profile($config['profile'])) {
                $config = \array_replace_recursive($config, $profile);
            }

        }

        return $config;

    }, // end of getConfig()

    'findMultiplaneDir' => function() {

        if (\defined('MP_DIR')) return MP_DIR;

        // to do...
        $checkFile = '/modules/Multiplane/bootstrap.php';

        if (\defined('MP_COCKPIT_ADMIN_ROUTE') && \basename(\dirname(COCKPIT_DIR)) == 'lib') {

            // mp lib skeleton -  I expect Cockpit and CpMultiplane in parallel inside lib folder

            $checkDir = \dirname(COCKPIT_DIR) . '/CpMultiplane';

            if (\file_exists($checkDir . $checkFile)) {
                return $checkDir;
            }

        }

        elseif (\file_exists(\dirname(COCKPIT_DIR) . $checkFile)) {

            // default usage - I expect Cockpit to be in a sub folder of CpMultiplane

            return \dirname(COCKPIT_DIR);
        }

        return false;

    }, // end of findMultiplaneDir()

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

    }, // end of getSiteUrl()

]);

// load autoconfig
include_once(__DIR__.'/autoconfig/uniqueslugs.php');
include_once(__DIR__.'/autoconfig/imageresize.php');

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

<?php

// pass config to ImageResize addon if not already present

$this->module('cpmultiplanegui')->extend([

    'hasAutoConfigImageResize' => false,

    'autoConfigImageResize' => function() {

        // this event might be triggered in a loop, so don't run it multiple times
        if ($this->hasAutoConfigImageResize) return;

        if (!isset($this->app['modules']['imageresize'])) return;

        $iConfig = $this->app->module('imageresize')->getConfig();

        if (!empty($iConfig['profiles'])) return;

        $mpdir = $this->findMultiplaneDir();

        if (!$mpdir) return;

        if (!$mpConfig) {
            try {
                \define('MP_SELF_EXPORT', true);
                include_once($mpdir . '/bootstrap.php');
                $mpConfig = $this->app->module('multiplane')->self_export();
            }
            catch(\Exception $e) {
                return;
            }
        }

        $theme  = $mpConfig['theme'];
        $name   = $theme['name'];
        $themes = $mpConfig['themes'];

        $lexyConfig = $themes[$name]['config']['lexy'] ?? [];

        if (isset($theme['parentTheme']) && isset($themes[$theme['parentTheme']]['config']['lexy'])) {
            $lexyConfig = \array_replace_recursive($themes[$theme['parentTheme']]['config']['lexy'], $lexyConfig);
        }

        if (empty($lexyConfig)) return;

        unset($lexyConfig['uploads']);

        $iConfig['profiles'] = $lexyConfig;

        $this->app->modules['imageresize']->config = $iConfig;

        $this->hasAutoConfigImageResize = true;

    }

]);

$this->on('cockpit.asset.upload', function(&$asset, &$_meta, &$opts, &$file, &$path) {
    $this->module('cpmultiplanegui')->autoConfigImageResize();
}, 200); // imageresize fires on default priority 100

$this->on('cockpit.assets.remove', function($assets) {
    $this->module('cpmultiplanegui')->autoConfigImageResize();
}, 200); // imageresize fires on default priority 100

$this->on('app.imageresize.controller.admin.init', function() {

    $routes = [
        '/imageresize/getProfiles',
        '/imageresize/addResizedAsset',
    ];

    if (!\in_array($this['route'], $routes)) return;

    $this->module('cpmultiplanegui')->autoConfigImageResize();
});

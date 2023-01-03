<?php

namespace CpMultiplaneGUI\Controller;

class Admin extends \Cockpit\AuthController {

    public function index() {

        if (!$this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage')) {
            return $this->helper('admin')->denyRequest();
        }

        $profiles = $this->app->module('cpmultiplanegui')->profiles();

        $currentProfile = $this->app->retrieve('multiplane/profile', '');

        return $this->render('cpmultiplanegui:views/index.php', compact('profiles', 'currentProfile'));

    }

    public function profile($name = null) {

        if (!$this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage')) {
            return $this->helper('admin')->denyRequest();
        }

        $profile = $this->app->module('cpmultiplanegui')->profile($name);

        $_collections = $this->app->module('collections')->getCollectionsInGroup();
        $collections  = [];
        $fields       = [];
        foreach ($_collections as $c) {
            foreach ($c['fields'] as $field) $fields[] = $field['name'];
            $collections[] = [
                'name'  => $c['name'],
                'label' => !empty($c['label']) ? $c['label'] : $c['name'],
                'color' => $c['color'] ?? '',
                'icon'  => $c['icon'] ?? '',
            ];
        }
        // field names for pre render selection
        $fieldnames = \array_keys(\array_flip($fields));

        $_singletons = $this->app->module('singletons')->getSingletonsInGroup();
        $singletons  = [];
        foreach ($_singletons as $s) {
            $singletons[] = [
                'name'  => $s['name'],
                'label' => !empty($s['label']) ? $s['label'] : $s['name'],
                'color' => $s['color'] ?? '',
                'icon'  => $s['icon'] ?? '',
            ];
        }

        $_forms = $this->app->module('forms')->forms();
        $forms  = [];
        foreach ($_forms as $f) {
            $forms[] = [
                'name'  => $f['name'],
                'label' => !empty($f['label']) ? $f['label'] : $f['name'],
                'color' => $f['color'] ?? '',
                'icon'  => $f['icon'] ?? '',
            ];
        }

        $fieldNames = $this->app->module('cpmultiplanegui')->fieldNames;

        $themes = [];

        return $this->render('cpmultiplanegui:views/profile.php', compact(
            'profile',
            'fieldnames',
            'collections',
            'singletons',
            'forms',
            'fieldNames',
            'themes'
        ));

    }

    public function save_profile($name) {

        $profile = $this->param('profile', false);

        if (!$profile) return false;

        $isUpdate = isset($profile['_id']);

        if (!$isUpdate && !$this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage')) {
            return $this->helper('admin')->denyRequest();
        }

        return $this->app->module('cpmultiplanegui')->saveProfile($name, $profile);

    }

    /**
     * delete multiplane key in cockpit/options from old ui
     */
    public function clean_database() {

        if (!$this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage')) {
            return $this->helper('admin')->denyRequest();
        }

        $return = $this->app->storage->removeKey('cockpit/options', 'multiplane');

        return compact('return');

    }

    public function get_multiplane_config() {

        if (!$this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage')) {
            return $this->helper('admin')->denyRequest();
        }

        // Multiplane is in addons dir
        if (isset($this->modules['multiplane'])) {
            return $this->app->module('multiplane')->self_export();
        }

        // Multiplane is in modules dir of CpMultiplane
        $mpdir = $this->app->module('cpmultiplanegui')->findMultiplaneDir();

        if (!$mpdir) {
            return ['error' => 'Couldn\'t find Multiplane dir'];
        }

        try {

            \define('MP_SELF_EXPORT', true);

            include($mpdir . '/bootstrap.php');

            return $this->app->module('multiplane')->self_export();
        }
        catch(\Exception $e) {
            return ['error' => $e->getMessage()];
        }

    }

    public function edit_forms_in_use() {

        $hasAccess = $this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage') || $this->module('cockpit')->hasaccess('cpmultiplanegui', 'edit_forms_in_use');
        if (!$hasAccess) return $this->helper('admin')->denyRequest();

        $config = $this->module('cpmultiplanegui')->getConfig();
        $currentProfile = $config['profile'] ?? null;
        $profile = $this->module('cpmultiplanegui')->profile($currentProfile);
        if (!$profile) return false;

        $form = $this->param('form', null);
        $use  = $this->param('use', null);
        if ($form == null || $use == null) return false;

        $formsInUse = $profile['use']['forms'] ?? [];
        if ($use == 1) {
            if (array_search($form, $formsInUse) === false) {
                $formsInUse[] = $form;
            }
        }
        if ($use == 0) {
            if (($key = array_search($form, $formsInUse)) !== false) {
                array_splice($formsInUse, $key, 1);
            }
        }

        $profile['use']['forms'] = $formsInUse;

        $result = $this->app->module('cpmultiplanegui')->saveProfile($profile['name'], $profile);

        return $result ? ['success' => true] : ['success' => false];

    }

    /**
     * Get site branding config with fallback values from (localized) seo field
     * in site singleton or system defaults.
     *
     * Use this method in the seo field options with
     * `"optsFromRequest": "/multiplane/getBranding"`
     *
     * Fallback examples:
     *
     * branding: singleton/seo/config/branding || singleton/site_name || app.name
     *
     * branding_de: singleton/seo_de/config/branding || singleton/seo/config/branding
     *              || singleton/site_name_de  || singleton/site_name || app.name
     */
    public function getBranding() {

        $data = [];
        $site = [];

        $config = $this->app->module('cpmultiplanegui')->getConfig();

        if (!empty($config['siteSingleton'])) {
            $site = $this->app->module('singletons')->getData($config['siteSingleton']) ?? [];
        }

        $seoName = $this->app->module('cpmultiplanegui')->fieldNames['seo'];
        $seo = isset($site[$seoName]) && \is_array($site[$seoName]) ? $site[$seoName] : [];

        $site_name = $site['site_name'] ?? $this->app['app.name'];

        $defaultBranding = !empty($seo['config']['branding'])
                           ? $seo['config']['branding'] : $site_name;

        $defaultSpacer   = !empty($seo['config']['spacer'])
                           ? $seo['config']['spacer'] : ' - ';

        $data['branding']     = $defaultBranding;
        $data['spacer']       = $defaultSpacer;

        // TODO:
        // $data['hideBranding'] = $seo['config']['hideBranding'] ?? false;

        $appLanguages = $this->app['languages'] ?? [];
        foreach ($appLanguages as $code => $label) {

            if ($code == 'default') continue;

            $seoName = "{$seoName}_{$code}";

            if (!empty($site["site_name_{$code}"])) {
                $site_name = $site["site_name_{$code}"];
            }
            $seo = isset($site[$seoName]) && \is_array($site[$seoName]) ? $site[$seoName] : [];

            $branding = !empty($seo['config']['branding'])
                        ? $seo['config']['branding'] : $site_name;

            $spacer   = !empty($seo['config']['spacer'])
                        ? $seo['config']['spacer'] : $defaultSpacer;

            $data["branding_{$code}"]     = $branding;
            $data["spacer_{$code}"]       = $spacer;

            // TODO:
            // $data["hideBranding_{$code}"] = $seo['config']['hideBranding'] ?? false;;
        }

        return $data;

    }

}

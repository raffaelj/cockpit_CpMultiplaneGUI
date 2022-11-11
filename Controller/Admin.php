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

    } // end of index()

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

    } // end of profile()

    public function save_profile($name) {

        $profile = $this->param('profile', false);

        if (!$profile) return false;

        $isUpdate = isset($profile['_id']);

        if (!$isUpdate && !$this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage')) {
            return $this->helper('admin')->denyRequest();
        }

        return $this->app->module('cpmultiplanegui')->saveProfile($name, $profile);

    } // end of save_profile()

    // delete multiplane key in cockpit/options from old ui
    public function clean_database() {

        if (!$this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage')) {
            return $this->helper('admin')->denyRequest();
        }

        $return = $this->app->storage->removeKey('cockpit/options', 'multiplane');

        return compact('return');

    } // end of clean_database()

    public function get_multiplane_config() {

        if (!$this->module('cockpit')->hasaccess('cpmultiplanegui', 'manage')) {
            return $this->helper('admin')->denyRequest();
        }

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

    } // end of get_multiplane_config()

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

}

<?php

namespace CpMultiplaneGUI\Controller;

class Admin extends \Cockpit\AuthController {

    public function index() {}

    public function settings() {

        $config = $this->app->module('cpmultiplanegui')->getConfig();
        
        $collections = $this->app->module('collections')->getCollectionsInGroup();
        
        $fields = [];

        foreach ($collections as $col) {
            foreach ($col['fields'] as $field) {
                $fields[] = $field['name'];
            }
        }

        // field names for pre render selection
        $fieldnames = array_keys(array_flip($fields));

        return $this->render('cpmultiplanegui:views/settings.php', compact('config', 'fieldnames'));

    }

    public function saveConfig() {

        $config = $this->param('config', false);

        if ($config) {
            $this->app->storage->setKey('cockpit/options', 'multiplane', $config);
        }

        return $config;

    }

}

<?php

namespace CpMultiplaneGUI\Controller;

class Admin extends \Cockpit\AuthController {

    public function index() {

        // $config = $this->app->module('rljutils')->getConfig();

        // return $this->render('cpmultiplanegui:views/index.php', ['config' => $config]);

    }

    public function settings() {

        // $config = $this->app->module('rljutils')->getConfig();

        return $this->render('cpmultiplanegui:views/settings.php');

    }

    public function saveConfig() {

        // $config = $this->param('config', false);

        // if ($config) {
            // $this->app->storage->setKey('cockpit/options', 'cpmultiplanegui', $config);
        // }

        // return $config;

    }

}

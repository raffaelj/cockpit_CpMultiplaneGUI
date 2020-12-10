<?php

// pass config to UniqueSlugs addon if not already present, requires UniqueSlugs version 0.5.3
$this->on('cockpit.bootstrap', function() {

    if (!isset($this['modules']['uniqueslugs'])) return;

    $config = $this->module('cpmultiplanegui')->getConfig();

    if (empty($config['use']['collections'])) return;

    $isMultilingual = $config['isMultilingual'] ?? false;

    $uConfig = $this->retrieve('unique_slugs');

    $fieldNames = $this->module('cpmultiplanegui')->fieldNames;
    if (isset($config['fieldNames']) && \is_array($config['fieldNames'])) {
        foreach ($config['fieldNames'] as $fieldName => $replacement) {
            if (\is_string($replacement) && !empty(\trim($replacement))) {
                $fieldNames[$fieldName] = \trim($replacement);
            }
        }
    }

    foreach ($config['use']['collections'] as $col) {

        $fieldName = $fieldNames['title'];

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

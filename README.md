# CpMultiplaneGUI

**This addon is not compatible with Cockpit CMS v2.**

See also [Cockpit CMS v1 docs](https://v1.getcockpit.com/documentation), [Cockpit CMS v1 repo](https://github.com/agentejo/cockpit) and [Cockpit CMS v2 docs](https://getcockpit.com/documentation/), [Cockpit CMS v2 repo](https://github.com/Cockpit-HQ/Cockpit).

---

Addon for [Cockpit CMS][1], that adds some ui options for the [CpMultiplane][2] frontend.

* adds profiles for easier config management
* adds a few fields to the sidebar, so you don't have to define them in your collection definitions
* some gui tweaks for easier access
* custom fields
  * `simple-gallery` to force users to use assets instead of images
  * `seo`
  * `key-value-pair`

## Installation

Copy this repository into `/addons` and name it `CpMultiplaneGUI` or use the cli.

### via git

```bash
cd path/to/cockpit
git clone https://github.com/raffaelj/cockpit_CpMultiplaneGUI.git addons/CpMultiplaneGUI
```

### via cp cli

```bash
cd path/to/cockpit
./cp install/addon --name CpMultiplaneGUI --url https://github.com/raffaelj/cockpit_CpMultiplaneGUI/archive/master.zip
```

### via composer

Make sure, that the path to cockpit addons is defined in your projects' `composer.json` file.

```json
{
    "name": "my/cockpit-project",
    "extra": {
        "installer-paths": {
            "addons/{$name}": ["type:cockpit-module"]
        }
    }
}
```

```bash
cd path/to/cockpit-root
composer create-project --ignore-platform-reqs aheinze/cockpit .
composer config extra.installer-paths.addons/{\$name} "type:cockpit-module"

composer require --ignore-platform-reqs raffaelj/cockpit-cpmultiplanegui
```

## To do

* [ ] setup via gui - create collections, singletons and forms from default templates

[1]: https://github.com/agentejo/cockpit/
[2]: https://github.com/raffaelj/CpMultiplane

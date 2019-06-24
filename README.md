# CpMultiplaneGUI


Addon for [Cockpit CMS][1], that adds some ui options for the [CpMultiplane][1] frontend.

* adds a few fields to the sidebar, so you don't have to define them in your collection definitions
* some gui tweaks for easier access
* custom field `simple-gallery` to force users to use assets instead of images

This addon is part of the [CpMultiplaneBundle][3] with all recommended addons for CpMultiplane. Sometimes, the versions might be different. It's easier for me to do the active development over there. Otherwise I would have to change versions and pull changes in multiple folders after each commit to keep them in sync.

## Installation

Copy this repository into `/addons` and name it `CpMultiplaneGUI` or

```bash
cd path/to/cockpit
git clone https://github.com/raffaelj/cockpit_CpMultiplaneGUI.git addons/CpMultiplaneGUI
```

[1]: https://github.com/agentejo/cockpit/
[2]: https://github.com/raffaelj/CpMultiplane
[3]: https://github.com/raffaelj/cockpit_CpMultiplaneBundle

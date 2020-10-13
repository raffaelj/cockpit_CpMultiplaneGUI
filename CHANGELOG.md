# Changelog

## 0.2.2

* fixed js error in seo field if description fallback field is no string
* fixed duplicated buttons in wysiwyg editor if editor is rebuild --> for compatibility with BlockEditor addon
* added custom sort option for posts
* improved path guessing to communicate with CpMultiplane

## 0.2.1

* added `composer.json`

## 0.2.0

* fixed empty url link in system menu
* improved ui
* removed more code parts from deprecated settings
* added UniqueSlugs auto config
* compatible with CpMultiplane 0.2.0

## 0.1.7

* fixed wrong getImage url when using `COCKPIT_ENV_ROOT`
* removed outdated settings page
* added German translation
* minor ui improvements

## 0.1.6

* massive rewrite
* added profiles
* changed the key for custom navigation in header from `in_menu` to `multiplane.gui_in_header` and added custom settings (Cockpit dropped that feature in version 0.9.3)
* added unique check for startpage toggle
* refactored sidebar

## 0.1.5

* added option for custom navigation to settings, disabled by default
* some cleanup

## 0.1.4

* now getImage TinyMCE plugin doesn't load automtically anymore, if EditorFormats addon is active
* added sidebar to posts (only published field for now)
* added lexy renderer settings to GUI
* fixed typo in Singleton links (custom navigation)

## 0.1.3

* fixed: settings page didn't apply changes, if config was an empty array

## 0.1.2

* added tynymce (wysiwyg) plugin to resize images properly with the `/getImage` url

## 0.1.1

* started to implement GUI to change CpMultiplane settings - the most (important) options are implemented

## 0.1.0

* initial release

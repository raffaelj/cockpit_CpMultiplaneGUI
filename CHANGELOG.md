# Changelog

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

<?php

if (!COCKPIT_CLI) return;

$lang     = $app->param('lang', null);
$language = $app->param('language', $lang);
$author   = $app->param('author', 'Cockpit CLI');

if (!$lang) {
    return CLI::writeln("--lang parameter is missing", false);
}

$addonDir     = $app->path('cpmultiplanegui:');
$addonI18nDir = 'cpmultiplanegui:i18n';

// settings
$extensions = ['php', 'html', 'js', 'tag'];
$strings    = [];
$exclude    = ['lib', 'docs'];

// source: https://www.php.net/manual/en/function.var-export.php#122853
function varexport($expression, $return=FALSE) {
    $export = var_export($expression, TRUE);
    $array  = preg_split("/\r\n|\n|\r/", $export);
    $array  = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [NULL, ']$1', ' => ['], $array);
    $export = join(PHP_EOL, array_filter(["["] + $array));
    if ((bool)$return) return $export; else echo $export;
}

// source: https://stackoverflow.com/a/15370487
class DirFilter extends RecursiveFilterIterator
{
    protected $exclude;
    public function __construct($iterator, array $exclude)
    {
        parent::__construct($iterator);
        $this->exclude = $exclude;
    }
    public function accept()
    {
        return !($this->isDir() && in_array($this->getFilename(), $this->exclude));
    }
    public function getChildren()
    {
        return new DirFilter($this->getInnerIterator()->getChildren(), $this->exclude);
    }
}

$directory = new RecursiveDirectoryIterator($addonDir);
$filtered  = new DirFilter($directory, $exclude);
$iterator  = new RecursiveIteratorIterator($filtered, RecursiveIteratorIterator::SELF_FIRST);

foreach ($iterator as $file) {

    if (in_array($file->getExtension(), $extensions)) {

        $contents = file_get_contents($file->getRealPath());

        preg_match_all('/(?:\@lang|App\.i18n\.get|App\.ui\.notify)\((["\'])((?:[^\1]|\\.)*?)\1(,\s*(["\'])((?:[^\4]|\\.)*?)\4)?\)/', $contents, $matches);

        if (!isset($matches[2])) continue;

        foreach ($matches[2] as &$string) {
            $strings[$string] = $string;
        }

    }
}

if (count($strings)) {

    if ($app->path("{$addonI18nDir}/{$lang}.php")) {
        $langfile = include($app->path("{$addonI18nDir}/{$lang}.php"));
        $strings  = array_merge($strings, $langfile);
    }

    // remove empty string
    if (isset($strings[''])) unset($strings['']);

    // compare with core i18n file and strip existing keys
    if ($app->path("#config:cockpit/i18n/{$lang}.php")) {
        $langfile = include($app->path("#config:cockpit/i18n/{$lang}.php"));
        foreach ($langfile as $k => $v) {
            unset($strings[$k]);
        }
    }

    ksort($strings);

    $app->helper('fs')->write("{$addonI18nDir}/{$lang}.php", '<?php return '.varexport($strings, true).';');
}

CLI::writeln("Done! Language file created: {$addonI18nDir}/{$lang}.php", true);

<?php

/**
 * Adds test-related namespaces to WinterCMS's composer.json autoload section.
 *
 * Pest loads tests/Pest.php before PHPUnit's bootstrap runs, so Composer
 * must be able to resolve the full TestCase class hierarchy before the app boots.
 *
 * WinterCMS module directories are all-lowercase which conflicts with standard
 * PSR-4 resolution on Linux. Classmap entries store exact paths so case never
 * matters at runtime.
 *
 * Run from the WinterCMS root directory.
 */

$composerJson = getcwd() . '/composer.json';
$config = json_decode(file_get_contents($composerJson), true);

// Plugin test namespace via PSR-4 (file is at tests/TestCase.php — case matches)
$config['autoload']['psr-4']['RLuders\\JWTAuth\\Tests\\'] = 'plugins/rluders/jwtauth/tests/';

// WinterCMS does not PSR-4-map its module classes in the installed composer.json;
// it relies on its custom ClassLoader (Winter\Storm\Support\ClassLoader) which is
// only registered after the app boots. PluginTestCase::setUp() calls static methods
// on System\Classes\PluginManager BEFORE parent::setUp() boots the app, so Composer
// must be able to resolve module classes on its own.
// Adding 'modules' to classmap makes composer dump-autoload scan all module files
// and emit exact path→class mappings that work on case-sensitive Linux filesystems.
$classmapDirs = [
    'modules',   // All WinterCMS module classes (System\*, Backend\*, Cms\*, etc.)
];
foreach ($classmapDirs as $dir) {
    if (!in_array($dir, $config['autoload']['classmap'] ?? [], true)) {
        $config['autoload']['classmap'][] = $dir;
    }
}

file_put_contents($composerJson, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);

echo "Registered plugin test namespace and WinterCMS bootstrap classmaps\n";

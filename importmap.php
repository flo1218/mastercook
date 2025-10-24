<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@symfony/ux-translator' => [
        'path' => './vendor/symfony/ux-translator/assets/dist/translator_controller.js',
    ],
    '@app/translations' => [
        'path' => './var/translations/index.js',
    ],
    '@app/translations/configuration' => [
        'path' => './var/translations/configuration.js',
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@hotwired/turbo' => [
        'version' => '8.0.18',
    ],
    'intl-messageformat' => [
        'version' => '10.7.18',
    ],
    'tslib' => [
        'version' => '2.8.1',
    ],
    '@formatjs/icu-messageformat-parser' => [
        'version' => '2.11.4',
    ],
    '@formatjs/fast-memoize' => [
        'version' => '2.2.7',
    ],
    '@formatjs/icu-skeleton-parser' => [
        'version' => '1.8.16',
    ],
    'chart.js/auto' => [
        'version' => '4.5.1',
    ],
    'chart.js' => [
        'version' => '4.5.1',
    ],
    '@swup/fade-theme' => [
        'version' => '2.0.2',
    ],
    '@swup/slide-theme' => [
        'version' => '2.0.2',
    ],
    '@swup/forms-plugin' => [
        'version' => '3.6.0',
    ],
    '@swup/plugin' => [
        'version' => '4.0.0',
    ],
    'swup' => [
        'version' => '4.8.2',
    ],
    'delegate-it' => [
        'version' => '6.3.0',
    ],
    '@swup/debug-plugin' => [
        'version' => '4.1.0',
    ],
    'tom-select' => [
        'version' => '2.4.3',
    ],
    '@orchidjs/sifter' => [
        'version' => '1.1.0',
    ],
    '@orchidjs/unicode-variants' => [
        'version' => '1.1.2',
    ],
    'tom-select/dist/css/tom-select.bootstrap5.css' => [
        'version' => '2.4.3',
        'type' => 'css',
    ],
    '@kurkle/color' => [
        'version' => '0.4.0',
    ],
    '@swup/theme' => [
        'version' => '2.1.0',
    ],
    'path-to-regexp' => [
        'version' => '8.3.0',
    ],
    'tom-select/dist/css/tom-select.default.min.css' => [
        'version' => '2.4.3',
        'type' => 'css',
    ],
    'tom-select/dist/css/tom-select.default.css' => [
        'version' => '2.4.3',
        'type' => 'css',
    ],
    'tom-select/dist/css/tom-select.bootstrap4.css' => [
        'version' => '2.4.3',
        'type' => 'css',
    ],
];

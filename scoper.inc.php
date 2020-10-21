<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

return [
    'prefix' => 'WenpriseSpaceNameVendor',

    'finders' => [
        Finder::create()
              ->files()
              ->ignoreVCS(true)
              ->notName('/LICENSE|.*\\.md|.*\\.dist|Makefile|composer\\.json|composer\\.lock/')
              ->exclude([
                  'doc',
                  'test',
                  'test_old',
                  'tests',
                  'Tests',
                  'vendor-bin',
              ])
              ->in('vendor'),
    ],

    'files-whitelist' => [],

    'patchers' => [
        function (string $filePath, string $prefix, string $contents): string
        {
            // Change the contents here.

            return $contents;
        },
    ],

    'whitelist' => [
        'Composer\*',
    ],

    'whitelist-global-constants' => true,
    'whitelist-global-classes'   => true,
    'whitelist-global-functions' => true,
];

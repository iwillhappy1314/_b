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

    'patchers' => [
        function (string $filePath, string $prefix, string $contents): string
        {
            if ($filePath === '/path/to/offending/file') {
                return preg_replace(
                    "%\$class = 'Humbug\\\\Format\\\\Type\\\\' . \$type;%",
                    '$class = \'' . $prefix . '\\\\Humbug\\\\Format\\\\Type\\\\\' . $type;',
                    $content
                );
            }

            return $contents;
        },
    ],

    'files-whitelist' => [

    ],

    'whitelist' => [
        'Composer\*',
        'WPackio\*',
    ],

    'whitelist-global-constants' => true,
    'whitelist-global-classes'   => true,
    'whitelist-global-functions' => true,
];

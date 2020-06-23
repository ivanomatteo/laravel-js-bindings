<?php

/*
 * You can place your custom package configuration in here.
 */
return [

    'const' => [
        'out_path' => 'resources/js/const',
        'in_path' => 'app/Constants',
        'in_namespace' => 'App\\Constants',
    ],
    'routes' => [
        'out_path' => 'resources/js',
        'exclude_prefixes' => ['debugbar.'],
    ],
    'lang' => [
        'out_path' => 'resources/js/lang',
    ],

];

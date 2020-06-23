<?php

namespace IvanoMatteo\LaravelJsBindings;

use DirectoryIterator;

class FsUtils
{
    static function findPsr4Classes($path = null, $baseNamespace = "App", $accept = null)
    {
        if (!isset($path)) {
            $path = app_path('');
        }
        $baseNamespace = preg_replace("/^\\\\/", '', $baseNamespace);
        $baseNamespace = preg_replace("/\\\\$/", '', $baseNamespace);

        $out = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $path
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $item) {
            /**
             * @var \SplFileInfo $item
             */
            if ($item->isReadable() && $item->isFile() && mb_strtolower($item->getExtension()) === 'php') {

                $c = str_replace(
                    "/",
                    "\\",
                    mb_substr($item->getRealPath(), mb_strlen($path), -4)
                );

                $c = $baseNamespace . "$c";

                if (\Str::startsWith($c, "\\")) {
                    $c = substr($c, 1, strlen($c) - 1);
                }

                include_once $item->getRealPath();

                if (class_exists($c, false)) {
                    $rc = new \ReflectionClass($c);

                    if ($accept === null || $accept($rc)) {
                        $out[] = $rc;
                    }
                }
            }
        }
        return $out;
    }

    static function listDir($dir, $accept = null)
    {
        $iter = new \DirectoryIterator($dir);
        $out = [];
        foreach ($iter as $item) {

            /**
             * @var \SplFileInfo $item
             */
            if (!$item->isDot()) {
                if ($accept === null || $accept($item)) {
                    $out[] = $item;
                }
            }
        }
        return $out;
    }
}

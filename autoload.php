<?php

declare(strict_types=1);

/** @todo автолоадер неидеален, не учитаны все кейсы при посике файлов */
spl_autoload_register(function (string $class): void {
    $baseDir = __DIR__ . '/src/';
    $file = $baseDir . lcfirst(str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php');

    if (file_exists($file)) {
        require $file;

        return;
    }

    $file = lcfirst(str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php');

    if (file_exists($file)) {
        require $file;
    }
});

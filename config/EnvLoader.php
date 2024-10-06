<?php

declare(strict_types=1);

namespace Сonfig;

use Exception;

class EnvLoader
{
    /**
     * @throws Exception
     */
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            throw new Exception('Env file not found: ' . $path);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=')) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                if (!array_key_exists($key, $_ENV) || !array_key_exists($key, getenv())) {
                    putenv("$key=$value");
                    $_ENV[$key] = $value;
                }
            }

            if (strpos(trim($line), '#') === 0) {
                continue;
            }
        }
    }
}
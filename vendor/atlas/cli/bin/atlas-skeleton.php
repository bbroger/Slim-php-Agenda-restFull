#!/usr/bin/env php
<?php
use Atlas\Cli\Config;
use Atlas\Cli\Fsio;
use Atlas\Cli\Logger;
use Atlas\Cli\Skeleton;

error_reporting(E_ALL);

$autoload = false;

$files = array(
    __DIR__ . '/../../../autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php'
);

foreach ($files as $file) {
    if (file_exists($file)) {
        $autoload = $file;
        break;
    }
}

if (! $autoload) {
    echo "Please install and update Composer before continuing." . PHP_EOL;
    exit(1);
}

require $autoload;

if (! isset($_SERVER['argv'][1])) {
    echo "Please specify the path to a config file." . PHP_EOL;
    exit(1);
}

$configFile = $_SERVER['argv'][1];
if (! file_exists($configFile) && ! is_readable($configFile)) {
    echo "Config file missing or not readable: {$configFile}" . PHP_EOL;
    exit(1);
}

$input = require $configFile;
if (! is_array($input)) {
    echo "Config file '$configFile' does not return a PHP array." . PHP_EOL;
    exit(1);
}

$keys = isset($_SERVER['argv'][2])
    ? explode('.', $_SERVER['argv'][2])
    : [];

foreach ($keys as $key) {
    echo "Nested config key '{$key}' ";
    if (! isset($input[$key]) || ! is_array($input[$key])) {
        echo "is not set, or is not an array.". PHP_EOL;
        exit(1);
    }
    $input = $input[$key];
    echo "found." . PHP_EOL;
}

$command = new \Atlas\Cli\Skeleton(
    new Config($input),
    new Fsio(),
    new Logger()
);

$code = $command();
exit($code);

<?php
/**
 * Check FuelPHP Documentaion Translation
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2012 Kenji Suzuki
 * @link       https://github.com/kenjis/fuel-docs-tools
 */

define('TESTPATH', realpath('.') . '/');

spl_autoload_register(function ($class) {
    $file = TESTPATH . '../libs/' . $class . '.php';
    if (is_readable($file)) {
        require $file;
    }
});

require TESTPATH . '../vendor/autoload.php';

mb_internal_encoding('UTF-8');

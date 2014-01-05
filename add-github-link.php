<?php
/**
 * Add GitHub Links to FuelPHP Documentaion
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2012 Kenji Suzuki
 * @link       https://github.com/kenjis/fuel-docs-tools
 */

spl_autoload_register(function ($class) {
    require './libs/' . $class . '.php';
});

if ( ! isset($argv[1])) {
    echo 'Add GitHub links to HTML files' . PHP_EOL . PHP_EOL;
    echo 'Usage:' . PHP_EOL;
    echo ' php add-github-link.php <version>' . PHP_EOL;
    exit(1);
}

$config = array(
    'target_dir' => './fuel-docs-nekoget/',
    'version'    => $argv[1],
    'copyright_line' => '&copy; FuelPHP Development Team 2010-2014 - <a href="http://fuelphp.com">FuelPHP</a> is released under the MIT license.',
);

$fm = new FileManager($config, 'AddGitHubLink');
$fm->fix();

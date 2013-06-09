<?php
/**
 * Check FuelPHP Documentaion Translation
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2012 Kenji Suzuki
 * @link       https://github.com/kenjis/fuel-docs-tools
 */

$en_dir = './fuel-docs';
$ja_dir = './fuel-docs-nekoget';


mb_internal_encoding('UTF-8');

spl_autoload_register(function ($class) {
    require './libs/' . $class . '.php';
});

function check_line(FuelDocs $docs_en, FuelDocs $docs_ja)
{
    $error = false;
    
    echo 'checking branches:', PHP_EOL;
    system('cd ' . $docs_en->getDir() . '; git status');
    system('cd ' . $docs_ja->getDir() . '; git status');
    echo PHP_EOL;
    
    foreach ($docs_en as $file => $val) {
        $line_en = $docs_en->getLineCount($file);
        $line_ja = $docs_ja->getLineCount($file);
        
        if ($line_en !== $line_ja - 1) {
          echo $file, ' : en ', $line_en, ' ja ', $line_ja, PHP_EOL;
          $error = true;
        }
    }
    
    if ($error) {
        exit(1);
    }
}

function check_progress(FuelDocs $docs_en, FuelDocs $docs_ja)
{
    $rates = array();
    
    foreach ($docs_en as $file => $val) {
        $content_en = $docs_en->getContents($file);
        $content_ja = $docs_ja->getContents($file);
        
        $rate = FuelDocs::calcProgress($content_en, $content_ja);
        echo $file, "\t", sprintf("%d", $rate), PHP_EOL;
        $rates[] = $rate;
        //if ($file === 'general/constants.html') exit;
    }
	
    echo PHP_EOL, 'average: ', array_sum($rates) / count($rates), PHP_EOL;
}


if (! isset($argv[1])) {
    echo 'Usage:', PHP_EOL;
    echo '  ', $argv[0] . ' line' . PHP_EOL;
    echo '  ', $argv[0] . ' progress' . PHP_EOL;
    exit(1);
}

$cmd = $argv[1];

$docs_en = new FuelDocs($en_dir);
$docs_ja = new FuelDocs($ja_dir);

switch ($cmd) {
    case 'line':
        check_line($docs_en, $docs_ja);
        break;
    case 'progress':
        check_line($docs_en, $docs_ja);
        check_progress($docs_en, $docs_ja);
        break;
    default:
        echo 'No such command: ', $cmd, PHP_EOL;
        exit(1);
}

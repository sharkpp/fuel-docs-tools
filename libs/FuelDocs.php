<?php
/**
 * Check FuelPHP Documentaion Translation
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2012 Kenji Suzuki
 * @link       https://github.com/kenjis/fuel-docs-tools
 */

class FuelDocs implements Iterator
{
    protected $dir;
    protected $docs = array();
    
    protected static $debug = false;
    
    public function __construct($dir)
    {
        $this->dir = $dir;
        
        $filelist = new SortedFileList($dir);
        foreach ($filelist as $file) {
            if ($file->isDir()) {
                continue;
            }
            
            // skip .git directory
            if (substr($file->getPath(), 0, strlen($dir) + 5) === $dir . '/.git') {
                continue;
            }
            
            if ($file->getExtension() == 'html') {
                //echo $file, PHP_EOL;
                $len = strlen($dir);
                $name = substr($file, $len + 1);
                $this->docs[$name] = array(
                    'path' => $file->__toString(),
                    'line' => static::getFileLines($file->__toString()),
                );
            }
        }
        
        //var_dump($this->docs);
    }
    
    public function getDir()
    {
        return $this->dir;
    }
    
    public static function getFileLines($file)
    {
        return count(file($file));
    }
    
    public function getLineCount($file)
    {
        if (isset($this->docs[$file])) {
            return $this->docs[$file]['line'];
        } else {
            return null;
        }
    }
    
    public function getContents($file)
    {
        if (isset($this->docs[$file])) {
            return file_get_contents($this->docs[$file]['path']);
        } else {
            return null;
        }
    }
    
    public static function prepContents($contents)
    {
        // remove JavaScript
        $pattern = '/\<script type="text\/javascript"\>.+?\<\/script\>/us';
        $replacement = '';
        $contents = preg_replace($pattern, $replacement, $contents);
        
        $lines = explode("\n", $contents);
        $output = '';
        $inCode = false;
        
        foreach ($lines as $line)
        {
            $line = trim($line);
            //var_dump($line);
            
            // don't translate
            if ($line === '<strong>FuelPHP, a PHP 5.3 Framework</strong>') {
                $line = '';
            }
            if ($line === 'Documentation') {
                $line = '';
            }
            if ($line === '&copy; FuelPHP Development Team 2010-2013 - <a href="http://fuelphp.com">FuelPHP</a> is released under the MIT license.') {
                $line = '';
            }
            if (substr($line, 0, 18) === '<h4 class="method"') {
                $line = '';
            }
            
            // sample code
            $before = '';
            $after = '';
            
            if (preg_match('/\<pre\>\<code\>/u', $line) || preg_match('/\<pre .+?\>\<code\>/u', $line)) {
                $inCode = true;
                $before = '<code>' . "\n";
            }
            if (preg_match('/\<\/code\>\<\/pre\>/u', $line)) {
                $inCode = false;
                $after = "\n" . '</code>';
            }
            
            $output .= $before . trim(strip_tags($line)) . $after . "\n";
        }
        
        return rtrim($output);
    }
    
    public static function calcProgress($content1, $content2)
    {
        $content1 = static::prepContents($content1);
        $content2 = static::prepContents($content2);
        
        $lines1 = explode("\n", $content1);
        $lines2 = explode("\n", $content2);
        
        $i     = 0;
        $same  = 0;
        $total = 0;
        $inCode = false;
        foreach ($lines1 as $line)
        {
            $line1 = $lines1[$i];
            $line2 = $lines2[$i];
            
            if (preg_match('/\<code\>/u', $line)) {
                $inCode = true;
                $line1 = strip_tags($line1);
                $line2 = strip_tags($line2);
            }
            if (preg_match('/\<\/code\>/u', $line)) {
                $inCode = false;
                $line1 = strip_tags($line1);
                $line2 = strip_tags($line2);
            }
            
            if ($line1 === '' && $line2 === '') {
                $i++;
                continue;
            }
            
            if ($line1 === $line2) {
                if ($inCode) {
                    // comments
                    if (preg_match('/\s\/\//u', $line) || preg_match('/^\/\//u', $line)) {
                        if (static::$debug) {
                            echo '[same]', PHP_EOL;
                        }
                        $same++;
                    } else {
                        $total--;   // don't count code line
                    }
                } else {
                    if (static::$debug) {
                        echo '[same]', PHP_EOL;
                    }
                    $same++;
                }
            }
            
            if (static::$debug) {
                echo ' 1:', $line1, PHP_EOL;
                echo ' 2:', $line2, PHP_EOL;
            }
            $i++;
            $total++;
        }
        
        return (1 - ($same / $total)) * 100;
    }


    public function current()
    {
        return current($this->docs);
    }
    
    public function key()
    {
        return key($this->docs);
    }
    
    public function next()
    {
        return next($this->docs);
    }
    
    public function rewind()
    {
        return reset($this->docs);
    }
    
    public function valid()
    {
        return (key($this->docs) === null) ? false : true;
    }
}

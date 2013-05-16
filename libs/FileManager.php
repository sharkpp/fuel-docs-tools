<?php
/**
 * Add GitHub Links to FuelPHP Documentaion
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2012 Kenji Suzuki
 * @link       https://github.com/kenjis/fuel-docs-tools
 */

class FileManager {
    protected $target_dir;
    protected $file_classname;
    protected $version;
    protected $copyright_line;

    public function __construct($config, $file_classname)
    {
        $this->target_dir     = $config['target_dir'];
        $this->version        = $config['version'];
        $this->copyright_line = $config['copyright_line'];
        $this->file_classname = $file_classname;
    }

    public function get($item)
    {
        if (isset($this->$item)) {
            return $this->$item;
        } else {
            throw new RuntimeException($item . ' is not found.');
        }
    }

    public function fix($path = null)
    {
        if (is_null($path)) {
            $path = $this->target_dir;
        }

        try {
            $filelist = new SortedFileList($path);

            foreach ($filelist as $file) {
                //var_dump($file);

                if ($file->isDir()) {
                    //echo ' Dir: ' . $file . PHP_EOL;
                    if ($file->getFilename() !== '.' && $file->getFilename() !== '..') {
                        $this->fix($file);
                    }
                } else {
                    if ($file->isFile()) {
                        $file_obj = new $this->file_classname($file, $this);

                        if ($file_obj->isTarget()) {
                            echo 'File: ' . $file . PHP_EOL;
                            $new_cont = $file_obj->fix();
                            file_put_contents($file, $new_cont);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

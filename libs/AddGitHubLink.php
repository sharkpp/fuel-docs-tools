<?php
/**
 * Add GitHub Links to FuelPHP Documentaion
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2012 Kenji Suzuki
 * @link       https://github.com/kenjis/fuel-docs-tools
 */

class AddGitHubLink
{
    protected $filename;
    protected $file_content;
    protected $version;
    protected $target_dir;
    protected $copyright_line;

    public function __construct($filename, $file_manager)
    {
        $this->filename       = $filename;
        $this->file_content   = file($filename);
        $this->file_manager   = $file_manager;
        $this->version        = $file_manager->get('version');
        $this->target_dir     = $file_manager->get('target_dir');
        $this->copyright_line = $file_manager->get('copyright_line');
    }

    public function isTarget()
    {
        //var_dump($this->filename);
        $pathinfo = pathinfo($this->filename);

        if (isset($pathinfo['extension'])) {
            $ext = $pathinfo['extension'];
        } else {
            return false;
        }

        if ($ext === 'html') {
            return true;
        }

        return false;
    }

    public function fix()
    {
        $content = '';
        $check = false;

        foreach ($this->file_content as $line) {
            if ($check) {
                // https://github.com/fuel/docs/commits/1.5/develop/index.html
                $link_en = '<a href="https://github.com/fuel/docs/commits/' . $this->version . '/develop/';
                $link_en .= str_replace($this->target_dir, '', $this->filename);
                $link_en .= '">原文コミット履歴</a>';

                // https://github.com/NEKOGET/FuelPHP_docs_jp/commits/1.5/develop_japanese/index.html
                $link_ja = '<a href="https://github.com/NEKOGET/FuelPHP_docs_jp/commits/' . $this->version . '/develop_japanese/';
                $link_ja .= str_replace($this->target_dir, '', $this->filename);
                $link_ja .= '">翻訳コミット履歴</a>';

                // https://github.com/NEKOGET/FuelPHP_docs_jp/blob/1.5/develop_japanese/index.html
                $link_ja_edit = '<a href="https://github.com/NEKOGET/FuelPHP_docs_jp/blob/' . $this->version . '/develop_japanese/';
                $link_ja_edit .= str_replace($this->target_dir, '', $this->filename);
                $link_ja_edit .= '">GitHubで修正</a>';

                if (!preg_match('/^\[/u', $line, $matches)) {
                    $content .= '[ ' . $link_en . ' | ' . $link_ja . ' | ' . $link_ja_edit . ' ]' . "\n";
                    echo 'Added: ' . $this->filename . PHP_EOL;
                } else {
                    $line = '[ ' . $link_en . ' | ' . $link_ja . ' | ' . $link_ja_edit . ' ]' . "\n";
                }

                $check = false;
            }

            if (preg_match('/' . preg_quote($this->copyright_line, '/') . '/u', $line, $matches)) {
                $check = true;
            }

            $content .= $line;
        }

        return $content;
    }
}

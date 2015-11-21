<?php
/**
 * Find Translation criteria violation
 *
 * @author     sharkpp <https://github.com/sharkpp>
 * @license    MIT License
 * @copyright  2015 sharkpp
 * @link       https://github.com/sharkpp/fuel-docs-tools
 */

function directoryToArray($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '') {
        $arrayItems = array();
        $skipByExclude = false;
        $handle = opendir($directory);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
            preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
            if($exclude){
                preg_match($exclude, $file, $skipByExclude);
            }
            if (!$skip && !$skipByExclude) {
                if (is_dir($directory. DIRECTORY_SEPARATOR . $file)) {
                    if($recursive) {
                        $arrayItems = array_merge($arrayItems, directoryToArray($directory. DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude));
                    }
                    if($listDirs){
                        $file = $directory . DIRECTORY_SEPARATOR . $file;
                        $arrayItems[] = $file;
                    }
                } else {
                    if($listFiles){
                        $file = $directory . DIRECTORY_SEPARATOR . $file;
                        $arrayItems[] = $file;
                    }
                }
            }
        }
        closedir($handle);
        }
        return $arrayItems;
    }
  
  $en_dir = './fuel-docs';
  $ja_dir = './fuel-docs-nekoget';

  $list = directoryToArray($ja_dir);
  $list = array_filter($list, function($v){
      return preg_match('/.html$/', $v);
    });

// https://github.com/NEKOGET/FuelPHP_docs_jp/wiki/FuelPHP-Documentation-%E6%97%A5%E6%9C%AC%E8%AA%9E%E7%89%88%E5%9F%B7%E7%AD%86%E5%9F%BA%E6%BA%96

// 表記
// ・日本語の句点は全角の「。」を、読点は全角の「、」を使う。
// ・日本語には全角文字を使う。
// ・英数字、符号には半角文字 (いわゆる 1 バイト文字) を使う。
// ・数字は、慣習的に使われている場合を除いて、算用数字 (1、2、3) を使う。
// ・半角文字と全角文字の間には、半角文字 1 字分のスペースを入れる。
// ・丸括弧 ( ) の外側には、全角文字がきても半角文字がきても間に半角文字 1 文字分のスペースを入れる。
//   ・丸括弧 ( ) の内側には、全角文字がきても半角文字がきても間にスペースを入れない。
//   ・丸括弧 ( ) 内には句点は付けない。複数の文がある場合は、各文の間にだけ句点を付ける。
//   ・丸括弧が末尾にくる場合は、括弧の外に句点を付ける。

foreach ($list as $path)
{
  $data = @ file_get_contents($path);
  $data = str_replace("\r", "\n", str_replace("\r\n", "\n", $data));
  $data = explode("\n", $data);
  foreach ($data as $line_no => $line)
  {
    // ・半角文字と全角文字の間には、半角文字 1 字分のスペースを入れる。
    if (0 < preg_match_all(
          '`([^a-zA-Z0-9:;,.\/=~()!"@_-])' .
          '([a-zA-Z0-9:;,.\/=~()!"@_-]+)' .
          '([^a-zA-Z0-9:;,.\/=~()!"@_-])`su', $line, $m))
    {
      for ($i = 0, $num = count($m[0]); $i < $num; $i++) {
        $pre  = $m[1][$i];
        $text = $m[2][$i];
        $post = $m[3][$i];
        if ('>GitHubで' == $m[0][$i])
          continue;
        if ((' ' == $pre || '、' == $pre || '。' == $pre || '「' == $pre) && (' ' == $post || "\n" == $post || "\r" == $post))
          continue;
        if ('<' == $pre || '>' == $post)
          continue;
        if ('"' == $text || '(' == $text || ')' == $text || ':' == $text || 'value="' == $text || '...' == $text)
          continue;
        if ('/' == $text)
          continue;
        if (mb_convert_encoding($pre, 'ASCII', 'utf-8') == $pre && mb_convert_encoding($post, 'ASCII', 'utf-8') == $post)
          continue;

        echo sprintf('%s(%d): "%s" "%s" "%s"'.PHP_EOL, $path, $line_no + 1, $pre, $text, $post);
      }
    }
    // ・数字は、慣習的に使われている場合を除いて、算用数字 (1、2、3) を使う。
    if (0 < preg_match_all(
          '`(.)(一|二|三|四|五|六|七|八|九|十)(.)`su', $line, $m))
    {
      for ($i = 0, $num = count($m[0]); $i < $num; $i++) {
        $pre  = $m[1][$i];
        $text = $m[2][$i];
        $post = $m[3][$i];

    //    if ('一度' == $text.$post || '一部' == $text.$post || '一般' == $text.$post)
    //      continue;
    //    if ('第一に' == $pre.$text.$post)
    //      continue;
        if ('つ' != $post && '番' != $post && '対' != $post)
          continue;

        echo sprintf('%s(%d): "%s" "%s" "%s"'.PHP_EOL, $path, $line_no + 1, $pre, $text, $post);
      }
      // ・日本語の句点は全角の「。」を、読点は全角の「、」を使う。
      if (0 < preg_match_all(
            '`([^a-zA-Z0-9:;,.\/=~()!"@_-])' .
            '([,.] )' .
            '([^a-zA-Z0-9:;,.\/=~()!"@_-])`su', $line, $m))
      {
        for ($i = 0, $num = count($m[0]); $i < $num; $i++) {
          $pre  = $m[1][$i];
          $text = $m[2][$i];
          $post = $m[3][$i];

          if (mb_convert_encoding($pre, 'ASCII', 'utf-8') == $pre && mb_convert_encoding($post, 'ASCII', 'utf-8') == $post)
            continue;

          echo sprintf('%s(%d): "%s" "%s" "%s"'.PHP_EOL, $path, $line_no + 1, $pre, $text, $post);
        }
      }
    }
  }
} 

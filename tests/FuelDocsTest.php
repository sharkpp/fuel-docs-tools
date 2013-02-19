<?php
/**
 * Check FuelPHP Documentaion Translation
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2012 Kenji Suzuki
 * @link       https://github.com/kenjis/fuel-docs-tools
 */

class FuelDocsTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        //
    }
    
        public function test_prepContents_javascript()
    {
        $contents = <<<'EOL'
	<script type="text/javascript">
		var path = './../../';
		var class_prefix = "Agent::";
	</script>
	<script src="./../../assets/js/combined.js"></script>
EOL;
        $test = FuelDocs::prepContents($contents);
        $expected = <<<'EOL'
EOL;
        $this->assertEquals($expected, $test);
    }
    
    public function test_prepContents_code()
    {
        $contents = <<<'EOL'
							<td><pre class="php"><code>array(
	'enabled' => true,
	'url'     => 'http://browsers.garykeith.com/stream.asp?BrowsCapINI',
	 'method' => 'wrapper',
	 'file'   => '/tmp/php_browscap.ini',
)</code></pre>
EOL;
        $test = FuelDocs::prepContents($contents);
        $expected = <<<'EOL'
<code>
array(
'enabled' => true,
'url'     => 'http://browsers.garykeith.com/stream.asp?BrowsCapINI',
'method' => 'wrapper',
'file'   => '/tmp/php_browscap.ini',
)
</code>
EOL;
        $this->assertEquals($expected, $test);
    }
    
    public function test_prepContents_footer()
    {
        $contents = <<<'EOL'
		<footer>
			<p>
				&copy; FuelPHP Development Team 2010-2013 - <a href="http://fuelphp.com">FuelPHP</a> is released under the MIT license.
[ <a href="https://github.com/fuel/docs/commits/1.5/develop/classes/agent/config.html">原文コミット履歴</a> | <a href="https://github.com/NEKOGET/FuelPHP_docs_jp/commits/1.5/develop_japanese/classes/agent/config.html">翻訳コミット履歴</a> | <a href="https://github.com/NEKOGET/FuelPHP_docs_jp/blob/1.5/develop_japanese/classes/agent/config.html">GitHubで修正</a> ]
			</p>
		</footer>
EOL;
        $test = FuelDocs::prepContents($contents);
        $expected = <<<'EOL'



[ 原文コミット履歴 | 翻訳コミット履歴 | GitHubで修正 ]
EOL;
        $this->assertEquals($expected, $test);
    }
    
    public function test_prepContents_h1()
    {
        $contents = <<<'EOL'
				<h1>
					<strong>FuelPHP, a PHP 5.3 Framework</strong>
					Documentation
				</h1>
EOL;
        $test = FuelDocs::prepContents($contents);
        $expected = <<<'EOL'

EOL;
        $this->assertEquals($expected, $test);
    }
    
        public function test_prepContents_h4()
    {
        $contents = <<<'EOL'
			<article>
				<h4 class="method" id="method_is_multi">is_multi($arr, $all_keys = false)</h4>
				<p>The <strong>is_multi</strong> method checks if the array passed is multi-dimensional array or not.</p>
EOL;
        $test = FuelDocs::prepContents($contents);
        $expected = <<<'EOL'


The is_multi method checks if the array passed is multi-dimensional array or not.
EOL;
        $this->assertEquals($expected, $test);
    }
}

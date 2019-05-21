#!/web/bin/php
<?PHP
namespace phprax;
/**
 * dbArray Examples
 *
 * PHP version 5
 *
 * @category  DBA
 * @package   dbArray
 * @author    James Dornan <james@catch22.com>
 * @copyright 2005-2008 James Dornan <james@catch22.com>
 * @license   http://www.phpractical.com/license/0_50.txt P4PHP
 * @version   SVN: 1786
 * @link      http://www.phpractical.com/dbArray
 * @see       http://www.php.net/dba
 * @todo      More examples
 * @motto     Cleverness catches it's master.
 *
 */
error_reporting(E_ALL);

require_once '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . '_.php';

new _();

/*
 * TEST FOR UNSET KEY WHILE IN LOOP
 */

$tmpDir = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');

$opts1 = [
    'handler' => 'db4',
    'debug'   => false,
    'file'    => $tmpDir . DIRECTORY_SEPARATOR . 'unset.db'
];

@unlink($opts1['file']);


$db = dbArray::create($opts1);

for($count = 0; $count < 50; $count++) {
    $db[] = sha1(base_convert(mt_rand(0x19A100, 0x39AA3FF), 10, 36));
}

foreach($db as $key=>$value) {
    unset($db[$key]);
}


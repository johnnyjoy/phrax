#!/web/bin/php
<?PHP
namespace phprax;

/**
 * DbArray Examples
 *
 * PHP version 5
 *
 * @category  DBA
 * @package   DbArray
 * @author    James Dornan <james@catch22.com>
 * @copyright 2005-2008 James Dornan <james@catch22.com>
 * @license   http://www.phpractical.com/license/0_50.txt P4PHP
 * @version   SVN: 1786
 * @link      http://www.phpractical.com/DbArray
 * @see       http://www.php.net/dba
 * @todo      More examples
 * @motto     Cleverness catches it's master.
 *
 */
error_reporting(E_ALL);

require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

new _();

$tmpDir = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');

$opts1 = array('handler' => 'db4', 'file' => $tmpDir . DIRECTORY_SEPARATOR .
    'DbArrayExample1.db');

@unlink($tmpDir . DIRECTORY_SEPARATOR . 'dbArrayExample1.db');
@unlink($tmpDir . DIRECTORY_SEPARATOR . 'dbArrayExample2.db');

echo 'Creating DB file. ' . PHP_EOL;
$db = dbArray::create($opts1);
echo 'Created' . PHP_EOL;

print 'Create db[movies]' . PHP_EOL;
$db['movies'] = dbArray::create(array(
    'handler' => 'db4',
    'file'    => $tmpDir . DIRECTORY_SEPARATOR . 'DbArrayExample2.db'));

$db['A'] = 1;
$db['B'] = 2;
$db['C'] = 3;


print 'Create db[movies][] = The Incredibles' . PHP_EOL;
$db['movies'][] = 'The Incredibles';
$db['movies'][] = 'Finding Nemo';

// You can also access data as an object
/*
foreach ($db->getOffset('movies') as $title) {
    print "I like $title again.\n";
}
*/

// delete in the correct order, deletes do not cascade.
// $movies->delete();
// $db->delete();
?>

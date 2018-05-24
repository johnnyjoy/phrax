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

$opts1 = array('handler' => 'qdbm', 'file' => $tmpDir . DIRECTORY_SEPARATOR .
    'dbArrayExample1.db');

@unlink($tmpDir . DIRECTORY_SEPARATOR . 'dbArrayExample1.db');
@unlink($tmpDir . DIRECTORY_SEPARATOR . 'dbArrayExample2.db');

echo 'Testing dbArray...' . PHP_EOL;

echo PHP_EOL;

// Basic Example

echo 'Settings...' . PHP_EOL;

echo 'Tmp Dir: ' . $tmpDir . PHP_EOL;
echo 'Handler: ' . $opts1['handler'] . PHP_EOL;
echo 'File: ' . $opts1['file'] . PHP_EOL;

echo PHP_EOL;

echo 'Creating DB file. ' . PHP_EOL;
$db = dbArray::create($opts1);
echo 'Created' . PHP_EOL;

echo PHP_EOL;

echo 'Testing auto index.' . PHP_EOL;

echo 'set $db[] = \'a\';' . PHP_EOL;
$db[] = 'a';

echo 'set $db[] = \'b\';' . PHP_EOL;
$db[] = 'b';

echo 'set $db[] = \'c\';' . PHP_EOL;
$db[] = 'c';

echo PHP_EOL;

echo 'Testing scalar index.' . PHP_EOL;

echo 'set $db[\'a\'] = 1;' . PHP_EOL;
$db['a'] = 1;

echo 'set $db[\'b\'] = 2;' . PHP_EOL;
$db['b'] = 2;

echo 'set $db[\'c\'] = 3;' . PHP_EOL;
$db['c'] = 3;

echo PHP_EOL;

echo 'Foreach loop through $db...' . PHP_EOL;

foreach ($db as $key=>$value) {
    echo 'Key: ' . $key . PHP_EOL;
    echo 'Value: ' . $value . PHP_EOL;
}

echo 'Foreach loop complete.' . PHP_EOL;

// Nesting dbArray objects

print 'Create db[movies]' . PHP_EOL;

$db['movies'] = dbArray::create(array(
    'handler' => 'qdbm',
    'file'    => $tmpDir . DIRECTORY_SEPARATOR . 'dbArrayExample2.db'));

print 'Create db[movies][] = The Incredibles' . PHP_EOL;
$db['movies'][] = 'The Incredibles';
print 'Create db[movies][] = Toy Story' . PHP_EOL;
$db['movies'][] = 'Toy Story';
print 'Create db[movies][] = Toy Story 2' . PHP_EOL;
$db['movies'][] = 'Toy Story 2';
print 'Create db[movies][] = Finding Nemo' . PHP_EOL;
$db['movies'][] = 'Finding Nemo';
print 'Create db[movies][] = Monsters Inc.' . PHP_EOL;
$db['movies'][] = 'Monsters Inc.';
// $db['movies'][] = 'A Bug\'s Life';

// print_r($db);

foreach ($db['movies'] as $key=>$value) {
    print $key . ' => ' . $value . PHP_EOL;
}

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

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

$tmpDir = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
// $tmpDir = '/media/tmpfs';
// $tmpDir = '/run/shm';

$opts1 = [
    'handler' => 'db4',
    'file'    => $tmpDir . DIRECTORY_SEPARATOR . 'SpeedTest.db'
];

@unlink($opts1['file']);

echo 'dbArray Speed Test...' . PHP_EOL;

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

echo 'Write Test.' . PHP_EOL;
echo 'Setting 5,000 elements with random 40 character strings.' . PHP_EOL;

$start = microtime(true);

for($count = 0; $count < 5000; $count++) {
    $db[] = sha1(base_convert(mt_rand(0x19A100, 0x39AA3FF), 10, 36));
}

$seconds = microtime(true) - $start; 
echo 'Time: ' . $seconds . 'seconds' . PHP_EOL;
echo 'Transactions: ' . (5000/$seconds) . ' per second' . PHP_EOL;
echo 'Count: ' . count($db) . PHP_EOL;

echo PHP_EOL;

echo 'Keys Test.' . PHP_EOL;
echo 'Reading the keys of 5000 records...' . PHP_EOL;

$start = microtime(true);

$keys = $db->keys();

$seconds = microtime(true) - $start; 
echo 'Time: ' . $seconds . 'seconds' . PHP_EOL;
echo 'Transactions: ' . (5000/$seconds) . ' per second' . PHP_EOL;
echo 'Count: ' . count($db) . PHP_EOL;

echo PHP_EOL;

echo 'Exists Test.' . PHP_EOL;
echo 'Checking 5000 records with offsetExists used by isset...' . PHP_EOL;

$start = microtime(true);
$i = 0;

foreach($keys as $key) {
    if (!isset($db[$key]))
      $i++;
}

$seconds = microtime(true) - $start; 
echo 'Errors: ' . $i .  PHP_EOL;
echo 'Time: ' . $seconds . 'seconds' . PHP_EOL;
echo 'Transactions: ' . (5000/$seconds) . ' per second' . PHP_EOL;
echo 'Count: ' . count($db) . PHP_EOL;

echo PHP_EOL;

echo 'Read Test.' . PHP_EOL;
echo 'Reading 5,000 elements.' . PHP_EOL;

$start = microtime(true);

reset($db);

for($count = 0; $count < 5000; $count++) {
    $foo = $db[$count];
}

$seconds = microtime(true) - $start;
echo 'Time: ' . $seconds . 'seconds' . PHP_EOL;
echo 'Transactions: ' . (5000/$seconds) . ' per second' . PHP_EOL;
echo 'Count: ' . count($db) . PHP_EOL;

echo PHP_EOL;

echo 'Random Read Test.' . PHP_EOL;
echo 'Reading 5,000 elements at random.' . PHP_EOL;

$start = microtime(true);

for($count = 0; $count < 5000; $count++) {
    strlen($db[rand(0, 4999)]);
}

$seconds = microtime(true) - $start; 
echo 'Time: ' . $seconds . 'seconds' . PHP_EOL;
echo 'Transactions: ' . (5000/$seconds) . ' per second' . PHP_EOL;
echo 'Count: ' . count($db) . PHP_EOL;

echo PHP_EOL;

echo 'Unset Test.' . PHP_EOL;
echo 'Deleting 5,000 elements.' . PHP_EOL;

$start = microtime(true);

for($count = 0; $count < 5000; $count++) {
    unset($db[$count]);
}

for ($db->rewind(); $db->valid(); $db->next()) {
   echo $db->key() . PHP_EOL;
}

$seconds = microtime(true) - $start; 
echo 'Time: ' . $seconds . 'seconds' . PHP_EOL;
echo 'Transactions: ' . (5000/$seconds) . ' per second' . PHP_EOL;
echo 'Count: ' . count($db) . PHP_EOL;

echo PHP_EOL;

echo 'Object Serialization write Test.' . PHP_EOL;
echo 'Write 5,000 objects at random.' . PHP_EOL;

$start = microtime(true);

for($count = 0; $count < 5000; $count++) {
    $db[] = (object)sha1(base_convert(mt_rand(0x19A100, 0x39AA3FF), 10, 36));
}

$seconds = microtime(true) - $start; 
echo 'Time: ' . $seconds . 'seconds' . PHP_EOL;
echo 'Transactions: ' . (5000/$seconds) . ' per second' . PHP_EOL;

// echo 'Hello' . PHP_EOL;
// var_dump($db);

// $db->_debug = true;
echo 'Count: ' . count($db) . PHP_EOL;
// $db->_debug = false;

// echo 'Hello' . PHP_EOL;
// var_dump($db);

echo PHP_EOL;

/**
 * Object serialization test
 */
echo 'Object Serialization read Test.' . PHP_EOL;
echo 'Read 5,000 objects at random.' . PHP_EOL;

$start = microtime(true);

for($count = 0; $count < 5000; $count++) {
    get_class($db[$count]);
}

$seconds = microtime(true) - $start; 
echo 'Time: ' . $seconds . 'seconds' . PHP_EOL;
echo 'Transactions: ' . (5000/$seconds) . ' per second' . PHP_EOL;
echo 'Count: ' . count($db) . PHP_EOL;

echo 'Done.' . PHP_EOL;

?>

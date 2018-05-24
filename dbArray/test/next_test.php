#!/web/bin/php
<?PHP
namespace phprax;
error_reporting(E_ALL);

require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

new _();

$tmpDir = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');

$opts1 = array('handler' => 'qdbm', 'file' => $tmpDir . DIRECTORY_SEPARATOR .
    'dbArray_next.db');

@unlink($opts1['file']);

echo 'dbArray next test...' . PHP_EOL;

echo PHP_EOL;

echo 'Creating DB file. ' . PHP_EOL;
$db = dbArray::create($opts1);
echo 'Created' . PHP_EOL;

echo PHP_EOL;

echo 'Assigning a=>1, b=>2, c=>3, and d=>4.' . PHP_EOL;

$db['a'] = 1;
$db['b'] = 2;
$db['c'] = 3;
$db['d'] = 4;

$db->rewind();

var_dump($db->next());
var_dump($db->next());
var_dump($db->next());

print_r(array_keys($db()));

echo 'Done.' . PHP_EOL;

?>

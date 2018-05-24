--TEST--
dbArray keys test
--FILE--
<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

$_ = new _();

$dir  = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
$file = $dir . DIRECTORY_SEPARATOR . 'dbArray_keys.db';

$opts['handler'] = 'db4';
$opts['file']    = $file;
// $opts['debug']   = true;

@unlink($file);

$db  = dbArray::create($opts);

$db->kong = 'monkey';

$db[] = 'A';
$db[] = 'B';
$db[] = 'C';
$db[] = 'D';
$db[] = 'E';
$db[] = 'F';
$db[99]['X'] = 'XXX';
$db100[]['Y'] = 'YYY';
$db[101]['Z'] = 'ZZZ';

print_r($db->keys());
print_r($db->keys(true));

echo 'Done' . PHP_EOL;
?>
--EXPECTF--
Array
(
    [0] => 0
    [1] => 1
    [2] => 2
    [3] => 3
    [4] => 4
    [5] => 5
    [6] => 99
    [7] => kong
)
Array
(
    [0] => 0
    [1] => 1
    [2] => 2
    [3] => 3
    [4] => 4
    [5] => 5
    [6] => 99
    [7] => __count
    [8] => kong
)
Done

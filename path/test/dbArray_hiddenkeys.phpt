--TEST--
dbArray hidden keys test
--FILE--
<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

$_ = new _();

$dir  = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
$file = $dir . DIRECTORY_SEPARATOR . 'dbArray_hiddenkeys.db';

$opts['handler'] = 'qdbm';
$opts['file']    = $file;
// $opts['debug']   = true;

@unlink($file);

$db  = dbArray::create($opts);

$db->kong = 'monkey';

$db['A'] = '1';
$db['B'] = '2';
$db['C'] = '3';
$db[] = 'A';
$db[] = 'B';
$db[] = 'C';
$db[] = 'D';
$db[] = 'E';
$db[] = 'F';
$db[99]['X'] = 'XXX';
$db100[]['Y'] = 'YYY';
$db[101]['Z'] = 'ZZZ';

echo 'Count 1: ' . count($db) . PHP_EOL;

$db->addHiddenkeys(array('A', 'B', 'C', 'kong'));

echo 'Count 2: ' . count($db) . PHP_EOL;

var_dump($db->isHidden('A'));
var_dump($db->isHidden('B'));
var_dump($db->isHidden('C'));
var_dump($db->isHidden('kong'));

print_r($db->keys());

$db->removeHiddenkeys(array('A', 'B'));

var_dump($db->isHidden('A'));
var_dump($db->isHidden('B'));

echo 'Count 3: ' . count($db) . PHP_EOL;

print_r($db->keys());

$db->resetHiddenkeys();

echo 'Count 4: ' . count($db) . PHP_EOL;

print_r($db->keys());


print_r($db->keys(true));

echo 'Done' . PHP_EOL;
?>
--EXPECTF--
Count 1: 12
Count 2: 8
bool(true)
bool(true)
bool(true)
bool(true)
Array
(
    [0] => 0
    [1] => 1
    [2] => 2
    [3] => 3
    [4] => 4
    [5] => 5
    [6] => 99
    [7] => 101
)
bool(false)
bool(false)
Count 3: 10
Array
(
    [0] => 0
    [1] => 1
    [2] => 2
    [3] => 3
    [4] => 4
    [5] => 5
    [6] => 99
    [7] => 101
    [8] => A
    [9] => B
)
Count 4: 12
Array
(
    [0] => 0
    [1] => 1
    [2] => 2
    [3] => 3
    [4] => 4
    [5] => 5
    [6] => 99
    [7] => 101
    [8] => A
    [9] => B
    [10] => C
    [11] => kong
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
    [7] => 101
    [8] => A
    [9] => B
    [10] => C
    [11] => __count
    [12] => kong
)
Done

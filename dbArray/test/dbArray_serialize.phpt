--TEST--
dbArray serialize test
--FILE--
<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

$_ = new _();

$dir  = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
$file = $dir . DIRECTORY_SEPARATOR . 'dbArray_serialize.db';
$opts = array('handler' => 'db4', 'file' => $file);

@unlink($file);

$db  = dbArray::create($opts);

$db[] = 'a';
$db[] = 'b';
$db[] = 'c';
$db[] = ['x','y','z'];
$db[] = new \stdClass;

$dbSerialized = serialize($db);

unset($db);

$db = unserialize($dbSerialized);

$db[] = 'foo';

echo $db[0] . PHP_EOL;
echo $db[1] . PHP_EOL;
echo $db[2] . PHP_EOL;
echo $db[3][0] . PHP_EOL;
echo $db[3][1] . PHP_EOL;
echo $db[3][2] . PHP_EOL;
echo gettype($db[4]) . PHP_EOL;
echo $db[5] . PHP_EOL;

echo 'Done' . PHP_EOL;
?>
--EXPECTF--     
a
b
c
x
y
z
object
foo
Done

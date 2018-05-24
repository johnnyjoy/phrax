--TEST--
dbArray basic test - open, read, write
--FILE--
<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

$_ = new _();

@unlink($file);

$dir  = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
$file = $dir . DIRECTORY_SEPARATOR . 'dbArray_basic.db';
$opts = array('handler' => 'db4', 'file' => $file);
$db   = dbArray::create($opts);

$db[0] = 'a';
$db[1] = 'b';
$db[2] = 'c';
$db[3] = array('x','y','z');
$db[4] = new \stdClass;

var_dump($db[0]);
var_dump($db[1]);
var_dump($db[2]);
var_dump($db[3]);
var_dump($db[4]);

echo 'Done' . PHP_EOL;
?>
--EXPECTF--     
string(1) "a"
string(1) "b"
string(1) "c"
array(3) {
  [0]=>
  string(1) "x"
  [1]=>
  string(1) "y"
  [2]=>
  string(1) "z"
}
object(stdClass)#7 (0) {
}
Done

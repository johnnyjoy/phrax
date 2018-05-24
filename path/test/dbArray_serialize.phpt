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
$db[] = array('x','y','z');
$db[] = new \stdClass;

$dbSerialized = serialize($db);

unset($db);

$db = unserialize($dbSerialized);

$db[] = 'foo';

var_dump($db());

echo 'Done' . PHP_EOL;
?>
--EXPECTF--     
array(6) {
  [0]=>
  string(1) "a"
  [1]=>
  string(1) "b"
  [2]=>
  string(1) "c"
  [3]=>
  array(3) {
    [0]=>
    string(1) "x"
    [1]=>
    string(1) "y"
    [2]=>
    string(1) "z"
  }
  [4]=>
  object(stdClass)#14 (0) {
  }
  [5]=>
  string(3) "foo"
}
Done

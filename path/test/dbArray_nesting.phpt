--TEST--
dbArray nesting test
--FILE--
<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

$_ = new _();

$dir  = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
$file = $dir . DIRECTORY_SEPARATOR . 'dbArray_nesting.db';
$opts = array('handler' => 'qdbm', 'file' => $file);

@unlink($opts['file']);

$db  = dbArray::create($opts);

$opts['file'] = $dir . DIRECTORY_SEPARATOR . 'dbArray_nesting0.db';
@unlink($opts['file']);
$db[] = dbArray::create($opts);

$opts['file'] = $dir . DIRECTORY_SEPARATOR . 'dbArray_nesting1.db';
@unlink($opts['file']);
$db[] = dbArray::create($opts);

$opts['file'] = $dir . DIRECTORY_SEPARATOR . 'dbArray_nesting2.db';
@unlink($opts['file']);
$db[] = dbArray::create($opts);

$db[0][] = 'A';
$db[1][] = 'B';
$db[2][] = 'C';

$opts['file'] = $dir . DIRECTORY_SEPARATOR . 'dbArray_nesting0-0.db';
@unlink($opts['file']);
$db[0][] = dbArray::create($opts);

$opts['file'] = $dir . DIRECTORY_SEPARATOR . 'dbArray_nesting0-1.db';
@unlink($opts['file']);
$db[0][] = dbArray::create($opts);

$opts['file'] = $dir . DIRECTORY_SEPARATOR . 'dbArray_nesting0-2.db';
@unlink($opts['file']);
$db[0][] = dbArray::create($opts);

$db[0][1][] = 'nesting';
$db[0][2][] = 'nesting';

var_dump($db());

echo 'Done' . PHP_EOL;
?>
--EXPECTF--     
array(3) {
  [0]=>
  array(4) {
    [0]=>
    string(1) "A"
    [1]=>
    array(1) {
      [0]=>
      string(7) "nesting"
    }
    [2]=>
    array(1) {
      [0]=>
      string(7) "nesting"
    }
    [3]=>
    array(0) {
    }
  }
  [1]=>
  array(1) {
    [0]=>
    string(1) "B"
  }
  [2]=>
  array(1) {
    [0]=>
    string(1) "C"
  }
}
Done

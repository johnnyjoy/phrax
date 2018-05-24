--TEST--
dbArray properties test
--FILE--
<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

$_ = new _();

$dir  = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
$file = $dir . DIRECTORY_SEPARATOR . 'dbArray_properties.db';

$opts['handler'] = 'db4';
$opts['file']    = $file;
// $opts['debug']   = true;

@unlink($file);

$db  = dbArray::create($opts);

echo '$db->kong = \'monkey\';' . PHP_EOL;
$db->kong = 'monkey';

echo '$db->depth[][] = \'test\';' . PHP_EOL;
$db->depth[][] = 'test';

echo '$db->obj = new \\stdClass;' . PHP_EOL;
$db->obj = new \stdClass;

echo '$db->counter = 20;' . PHP_EOL;
$db->counter = 20;

echo '$db->counter++;' . PHP_EOL;
$db->counter++;

echo '$db->one++;' . PHP_EOL;
$db->one++;

echo '$db->deleteme = \'gone\';' . PHP_EOL;
$db->deleteme = 'gone';

echo 'unset($db->deleteme);' . PHP_EOL;
unset($db->deleteme);

var_dump($db->kong);
var_dump($db->depth[0][0]);
var_dump($db->obj);
var_dump($db->counter);
var_dump($db->one);
var_dump(isset($db->deleteme));

// print_r($db->toArray());

echo 'Done' . PHP_EOL;
?>
--EXPECTF--
$db->kong = 'monkey';
$db->depth[][] = 'test';
$db->obj = new \stdClass;
$db->counter = 20;
$db->counter++;
$db->one++;
$db->deleteme = 'gone';
unset($db->deleteme);
string(6) "monkey"
string(4) "test"
object(stdClass)#7 (0) {
}
int(21)
int(1)
bool(false)
Done

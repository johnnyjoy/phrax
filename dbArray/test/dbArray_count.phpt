--TEST--
dbArray count test
--FILE--
<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

$_ = new _();

$dir  = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
$file = $dir . DIRECTORY_SEPARATOR . 'dbArray_count.db';

$opts['handler'] = 'qdbm';
$opts['file']    = $file;
// $opts['debug']   = true;

@unlink($file);

$db  = dbArray::create($opts);

$db->kong = 'monkey';
$db->obj = new \stdClass;
$db[] = 'A';
$db[] = 'B';
$db[] = 'C';
$db[] = 'D';
$db[] = 'E';
$db[] = 'F';
$db['delete'] = 'ME';
$db->deleteme = 'gone';
$db[]['X'] = 'XXX';
$db[]['Y'] = 'YYY';
$db[]['Z'] = 'ZZZ';

echo count($db) . PHP_EOL;

unset($db[0]);
unset($db[1]);
unset($db[5]);
unset($db[7]);
unset($db->deleteme);
unset($db['delete']);

echo count($db) . PHP_EOL;

echo 'Done' . PHP_EOL;
?>
--EXPECTF--
13
7
Done

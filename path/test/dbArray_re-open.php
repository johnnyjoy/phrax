<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

$_ = new _();

$dir  = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
$file = $dir . DIRECTORY_SEPARATOR . 'dbArray_re-open.db';
$opts = array('handler' => 'db4', 'file' => $file);

@unlink($file);

$db1  = dbArray::create($opts);

$db1[0] = 'a';
$db1[1] = 'b';
$db1[2] = 'c';
$db1[3] = array('x','y','z');
$db1[4] = new \stdClass;

unset($db1);

$db2 = dbArray::create($opts);

var_dump($db2[0]);
var_dump($db2[1]);
var_dump($db2[2]);
var_dump($db2[3]);
var_dump($db2[4]);

echo 'Done' . PHP_EOL;
?>
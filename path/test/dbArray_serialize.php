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

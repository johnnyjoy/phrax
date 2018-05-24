--TEST--
dbArray unset test
--FILE--
<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

$_ = new _();

@unlink($file);

$dir  = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
$file = $dir . DIRECTORY_SEPARATOR . 'dbArray_unset.db';
$opts = array('handler' => 'db4', 'file' => $file);
$db   = dbArray::create($opts);

$db[0] = 'hike';
$db[1] = array('apple', 'orange', 'monkey');
$db[2] = new \stdClass;

unset($db[1]);
unset($db[2]);

echo 'Count: ' . count($db) . PHP_EOL;

echo 'Done' . PHP_EOL;
?>
--EXPECTF--     
Count: 1
Done

--TEST--
dbArray unlink
--FILE--
<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

$_ = new _();

$dir  = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
$file = $dir . DIRECTORY_SEPARATOR . 'dbArray_unlink.db';
$opts = array('handler' => 'db4', 'file' => $file);
$opts['unlink'] = true;

// @unlink($file);

$db   = dbArray::create($opts);

$db[0] = 'hike';
$db[1] = array('apple', 'orange', 'monkey');
$db[2] = new \stdClass;

unset($db);

var_dump(file_exists($file));

echo 'Done' . PHP_EOL;
?>
--EXPECTF--     
bool(false)
Done

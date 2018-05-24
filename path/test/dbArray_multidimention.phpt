--TEST--
dbArray multidimenional test
--FILE--
<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

$_ = new _();

$dir  = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
$file = $dir . DIRECTORY_SEPARATOR . 'dbArray_multidimentional.db';
$opts = array('handler' => 'db4', 'file' => $file);

@unlink($file);

$db  = dbArray::create($opts);

echo '$db[][] = \'monkey\';' . PHP_EOL;
$db[][] = 'monkey';

echo '$db[\'a\'][\'b\'] = \'c\';' . PHP_EOL;
$db['a']['b'] = 'c';

echo '$db[1][1] = true;' . PHP_EOL;
$db[1][1] = true;

var_dump($db[0][0]);
var_dump($db['a']['b']);
var_dump($db[1][1]);

echo 'Done' . PHP_EOL;
?>
--EXPECTF--
$db[][] = 'monkey';
$db['a']['b'] = 'c';
$db[1][1] = true;
string(6) "monkey"
string(1) "c"
bool(true)
Done

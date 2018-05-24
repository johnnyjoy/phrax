--TEST--
path test
--FILE--
<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . '_.php';

$pathstring = '/etc:/etc/passwd:/tmp';

$opts = ['file' => true, 'dir' => true];

$path = new path($pathstring, $opts);

echo 'Done' . PHP_EOL;
?>
--EXPECTF--
Done

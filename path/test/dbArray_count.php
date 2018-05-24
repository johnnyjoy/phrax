<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . '_.php';

$pathstring = '/etc:/etc/passwd:/tmp';

$opts = ['file' => true, 'dir' => true, 'exists' => true];

$path = new path($pathstring, $opts);

$path[] = '/usr/local';
$path[] = '/var';
$path[] = 'asassassas';

echo $path->save() . PHP_EOL;

// var_dump($path);

echo 'Done' . PHP_EOL;
?>

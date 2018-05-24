<?php
namespace phprax\path;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . '_.php';

$inc = new phpinc();
$inc[] = $inc[0];
$inc[0] = '/tmp';

echo $inc->save() . PHP_EOL;
echo ini_get('include_path') . PHP_EOL;

echo 'Done' . PHP_EOL;
?>

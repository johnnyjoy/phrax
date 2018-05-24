--TEST--
dbArray foreach test
--FILE--
<?php
namespace phprax;
require_once 'testinclude.php';

$file = $dbtestdir . DIRECTORY_SEPARATOR . 'dbArray_foreach.db';

$opts['file']    = $file;
// $opts['debug']   = true;

@unlink($file);

$db  = dbArray::create($opts);

$db->kong = 'monkey';
$db[] = 'A';
$db[] = 'B';
$db[] = 'C';
$db[] = 'D';
$db[] = 'E';
$db[] = 'F';
$db[99]['X'] = 'XXX';
$db100[]['Y'] = 'YYY';
$db[101]['Z'] = 'ZZZ';

$results = array();

echo count($db) . PHP_EOL;

foreach($db as $key=>$value) {
    $results[$key] = $value;
}

ksort($results);
print_r($results);

echo 'Done' . PHP_EOL;
?>
--EXPECTF--
9
Array
(
    [kong] => monkey
    [0] => A
    [1] => B
    [2] => C
    [3] => D
    [4] => E
    [5] => F
    [99] => Array
        (
            [X] => XXX
        )

    [101] => Array
        (
            [Z] => ZZZ
        )

)
Done

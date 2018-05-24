--TEST--
dbArray increment test
--FILE--
<?php
namespace phprax;
require_once '..' .  DIRECTORY_SEPARATOR . '..' .  DIRECTORY_SEPARATOR . '..' .
    DIRECTORY_SEPARATOR . 'nexus.php';

$_ = new _();

@unlink($file);

$dir  = (isset($_ENV['TMP_DIR']) ? $_ENV['TMP_DIR'] : '/tmp');
$file = $dir . DIRECTORY_SEPARATOR . 'dbArray_incerement.db';
$opts = array('handler' => 'db4', 'file' => $file);
$db   = dbArray::create($opts);

echo '$db[\'increment\'] = 10;' . PHP_EOL;
$db['increment'] = 10;
echo $db['increment'] . PHP_EOL;

echo '$db[\'increment\']++;' . PHP_EOL;
$db['increment']++;
echo $db['increment'] . PHP_EOL;

echo '++$db[\'increment\'];' . PHP_EOL;
++$db['increment'];
echo $db['increment'] . PHP_EOL;

echo '$db[\'increment\'] += 5;' . PHP_EOL;
$db['increment'] += 5;
echo $db['increment'] . PHP_EOL;

echo '$db[\'increment\'] -= 4;' . PHP_EOL;
$db['increment'] -= 4;
echo $db['increment'] . PHP_EOL;

echo '$db[\'increment\']--;' . PHP_EOL;
$db['increment']--;
echo $db['increment'] . PHP_EOL;

echo '--$db[\'increment\'];' . PHP_EOL;
--$db['increment'];
echo $db['increment'] . PHP_EOL;
echo 'Done' . PHP_EOL;

?>
--EXPECTF--     
$db['increment'] = 10;
10
$db['increment']++;
11
++$db['increment'];
12
$db['increment'] += 5;
17
$db['increment'] -= 4;
13
$db['increment']--;
12
--$db['increment'];
11
Done

<?php

class AAA {
    private static $sfoo = 1;
    private $ifoo = 2;
}

$cl1 = static function() {
    return AAA::$sfoo;
};

$cl2 = function() {
    return ++$this->ifoo;
};

$a = new AAA();

$bcl1 = Closure::bind($cl1, null, 'AAA');
$bcl2 = Closure::bind($cl2, &$a, 'AAA');

echo $bcl1(), "\n";
echo $bcl2(), "\n";
echo $bcl2(), "\n";

print_r($a);
?>

<?php
namespace phprax\_\method;

class hello
{
    public static function callStatic()
    {
        echo 'Hello' . PHP_EOL;
    }
    public function call()
    {
        self::callStatic();
    }
}

?>

<?php
namespace phprax\_\method;

/**
 * @todo Comment, comment, commment.
 */

class isSerialized
{
    public function __construct($_)
    {
        $this->_ =& $_;
    }

    public static function callStatic($str)
    {
        // Either it's 'b:0', which is what the value of false looks like
        // serialized, or it can be unserialized. If either of those tests
        // are true then we return true, otherwise false.
        return $str === 'b:0;' || @unserialize($str) !== false ? true : false;
    }

    public function call($str)
    {  
        return self::callStatic($str);
    }
}
?>

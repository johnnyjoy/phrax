<?php
namespace phprax\_\method;
/**
 * Load dynamic extnesions
 */
class loadExtension
{
    public function __construct($_)
    {
        $this->_ =& $_;
    }

    public static function callStatic($extension)
    {
        if (extension_loaded($extension))
            return true;

        if (!function_exists('dl'))
            return false;

        return @dl(((PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '') . $extension .
            '.' . PHP_SHLIB_SUFFIX);
    }

    public function call($extension)
    {  
        return self::callStatic($extension);
    }
}
?>

<?php
namespace phprax\_\method;

/**
 * @todo Comment, comment, commment.
 */
class ro
{
    public function __construct($_)
    {
        $this->_ =& $_;
    }

    public static function callStatic($file)
    {
        require_once $file;
    }

    public function call($extension)
    {  
        require_once $file;
    }
}
?>

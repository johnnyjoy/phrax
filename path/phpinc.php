<?PHP
namespace phprax\path;
/**
 * vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
 *
 * Class to manage php ini
 *
 * @category  Utility
 * @package   Phprax
 * @author    James Dornan <james@catch22.com>
 * @copyright 2003-2008 James Dornan <james@catch22.com>
 * @since     Class available since Release 0.5
 */

/**
 * Class: phpinc
 *
 * @see \phprax\path
 */
class phpinc extends \phprax\path
{
    /**
     * __construct
     *
     * @param mixed $path
     * @param array $options
     * @return void
     * @access public
     */
    public function __construct()
    {
        parent::load(ini_get('include_path'));
    }

    /**
     * check, whether a value exists
     *
     * @param int $offset array key
     *
     * @return boolean
     * @access public
     */
    public function offsetExists($offset)
    {
        return isset($this->_path[$offset]);
    }

    /**
     * set a property
     *
     * @param int    $offset array key
     * @param string $value  array value
     *
     * @return void
     * @access public
     */
    public function offsetSet($offset, $value)
    {
        parent::offsetSet($offset, $value);

        ini_set('include_path', $this->save());
    }

    /**
     * unset a property
     *
     * @param int $offset array key
     *
     * @return void
     * @access public
     */
    public function offsetUnset($offset)
    {
        parent::offsetUnset($offset);

        ini_set('include_path', $this->save());
    }

    /**
     * unshift
     *
     * @param string $value
     * @return void
     */
    public function unshift($value)
    {
        parent::unshift($value);

        ini_set('include_path', $this->save());
    }

    /**
     * wakeup
     *
     * @return void
     * @access public
     */
     public function __wakeup()
     {
        ini_set('include_path', $this->save());
     }
}

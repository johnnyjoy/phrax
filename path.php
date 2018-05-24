<?PHP
namespace phprax;
use phprax\path;
/**
 * vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
 *
 * Colon delimited path handler
 *
 * @category  Utility
 * @package   Phprax
 * @author    James Dornan <james@catch22.com>
 * @copyright 2003-2008 James Dornan <james@catch22.com>
 * @since     Class available since Release 0.5
 */

/**
 * Class: path
 *
 * @see \Iterator
 * @see \ArrayAccess
 * @see \Countable
 */
class path implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * _path
     *
     * @var array
     * @access private
     */
    private $_path = [];

    /**
     * _options
     *
     * @var array
     * @access private
     */
    private $_options = ['exists' => false, 'file' => false, 'dir' => false,
        'readable' => false];

    /**
     * _dilimiter
     *
     * @var string
     * @access private
     */
    private $_dilimiter = ':';

    /**
     * __construct
     *
     * @param mixed $path
     * @param array $options
     * @return void
     * @access public
     */
    public function __construct($path = false, array $options)
    {
        if (is_array($options) && count($options) > 0)
            $this->setOptions($options);

        if ($path)
            $this->load($path);
    }

    /**
     * setOptions
     *
     * @param array $options
     * @return void
     * @access public
     */
    public function setOptions(array $options)
    {
        $this->_options = array_merge($this->_options, $options);
    }

    /**
     * load
     *
     * @param mixed $path
     * @return void
     * @access public
     */
    public function load($path)
    {
        if (!is_string($path))
            throw new \Exception('Illegal non-string path.');

        if (($data = explode($this->_dilimiter, (string) trim($path))) === false)
            throw new \Exception('Illegal path string supplied.');

        foreach(array_map('trim', $data) as $key=>$value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * Return the path as a string
     *
     * @return string
     * @access public
     */
    public function save()
    {
        return is_array($this->_path) ?
            implode($this->_dilimiter, $this->_path) : '';
    }

    /**
     * sleep
     *
     * @return array
     * @access public
     */
    public function __sleep()
    {
        return ['_options', '_path'];
    }

    /**
     * wakeup
     *
     * @return void
     * @access public
     */
    public function __wakeup()
    {
    }

    /**
     * method used when object called as a string
     *
     * @return string
     * @access public
     */
    public function __toString()
    {
        return  $this->save();
    }

    /**
     * invoke magic method
     *
     * @param mixed
     *
     * @return mixed
     * @access public
     */
    public function __invoke()
    {
        $argc = func_num_args();
        $argv = func_get_args();

        // No argument, then export data as an array.
        if ($argv == 0)
            return $this->toArray();

        // Import an array
        if ($argv == 1 && is_array($argv[0]))
            return $this->load($argv[0]);

        if ($argv == 1 && is_string($argv[0]))
            $this->_path[] = $data;

    }

    /**
     * unshift
     *
     * @param string $value
     * @return void
     */
    public function unshift($value)
    {
        if (!is_string($value))
            throw new \Exception('Illegal non-string value');

        array_unshift($this->_path, trim($value));
    }

    /**
     * replace
     *
     * @param string $oldvalue
     * @param string $newvalue
     * @return void
     */
    public function replace($oldvalue, $newvalue)
    {
        $key = array_search($oldvalue, $this->_path);

        if ($key === false)
            throw new \Exception('Value not found');

        $this[$key] = $newvalue;
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
     * get a value
     *
     * @param int $offset array key
     *
     * @return string
     * @access public
     */
    public function offsetGet($offset)
    {
        return isset($this->_path[$offset]) ? $this->_path[$offset] : '';
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
        if (!is_numeric($offset) && $offset !== NULL)
            throw new \Exception('Illegal non-numeric index');

        if (!is_string($value))
            throw new \Exception('Illegal non-string value');

        if ($this->_options['exists'] && !file_exists($value))
            throw new \Exception('Value does not exist.');

        if ($this->_options['readable'] && !is_readable($value))
            throw new \Exception('Value is not readable.');

        if (is_link($value))
            $pathname = readlink($value);
        else
            $pathname = $value;

        if ($this->_options['file'] && !$this->_options['dir'] &&
            !is_file($pathname)) {
                throw new \Exception('Value is not a regular file.');
        }

        if ($this->_options['dir'] && !$this->_options['file'] &&
            !is_dir($pathname)) {
                throw new \Exception('Value is not a directory.');
        }

        if ($this->_options['dir'] && $this->_options['file'] &&
            (!is_dir($pathname) && !is_file($pathname))) {
                throw new \Exception('Value is not a file or directory.');
        }

        $this->_path[$offset] = $value;
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
        if (!is_numeric($offset))
            throw new \Exception('Illegal non-numeric index');

        unset($this->_path[$offset]);
    }

    /**
     *
     * @return void
     * @access public
     */
    public function rewind()
    {
        return reset($this->_path);
    }

    /**
     * Return the current array element
     *
     * @return mixed
     * @access public
     */
    public function current()
    {
        return current($this->_path);
    }

    /**
     * Return the key of the current array element
     *
     * @return int
     * @access public
     */
    public function key()
    {
        return key($this->_path);
    }

    /**
     * next, advance one element in the array
     *
     * @return void
     * @access public
     */
    public function next()
    {
        return next($this->_path);
    }

    /**
     * Is the current element valid?
     *
     * @return boolean
     * @access public
     */
    public function valid()
    {
        return $this->current() ? true : false;
    }

    /**
     * Count of array elements
     *
     * @return int
     * @access public
     */
    public function count()
    {
        return count($this->_path);
    }

    /**
     * to array
     *
     * @return array
     * @access public
     */
    public function toArray()
    {
        return $this->_path;
    }

    /**
     * keys
     *
     * return an array of all the keys
     *
     * @return array
     * @access public
     */
    public function keys()
    {
        return array_keys($this->_path);
    }
}

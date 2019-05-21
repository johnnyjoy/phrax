<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
namespace phprax;
use phprax\dbArray;
/**
 * dbArray manages DB file as an array.
 *
 * PHP version 5
 *
 * @category  DBA
 * @package   DbArray
 * @author    James Dornan <james@catch22.com>
 * @copyright 2005-2008 James Dornan <james@catch22.com>
 * @license   http://www.phpractical.com/license/0_50.txt P4PHP
 * @version   SVN: 1786
 * @link      http://www.phpractical.com/DbArray
 * @see       http://www.php.net/dba
 * @motto     Cleverness catches it's master.
 *
 * @todo      Handle keys as an array, using mtime comparison to refresh.
 * @todo      Change handler selection into it's own object
 * @todo      Check into creating a options class for the options
 * @todo      Set dba_optimize to fire off after counting n number of writes
 * @todo      camel case things.
 * @todo      Test access to the same db file by multiple processes
 *
 * @example
 *
 * dbArray takes a single array as it's argument.
 *
 * read - read access
 *  boolean
 *  default: false
 *
 * write - write access
 *  boolean
 *  default: false
 *
 * create - create file if needed, if does not exist
 *  boolean
 *  default: true
 *
 * new - create file if needed, truncate exists
 *  boolean
 *  default: false
 *
 * lock - lock file
 *  boolean
 *  default: false
 *
 * handler - dba_open file type handler
 *  string
 *  default: false, program will choose
 *
 * file - name of file to be opened
 *  string
 *  default: false, program exist without a file
 *
 * import - array of key/value pairs to be imported into db file
 *  array
 *  default: false, nothing to import
 *
 * hidden_keys - db file keys to hide
 *  array
 *  default: empty array
 *
 * // Simple
 * $db = new DbArray(['file' => '/tmp/foo.db']);
 * $db['key'] = $value;
 * $db->delete();
 *
 * // Nesting is okay
 *
 * $db1 = new DbArray(['file' => '/tmp/foo.db']);
 * $db1['key'] = DbArray::create([
 *      'file' => '/tmp/bar.db',
 *      'import' => ['A', 'B', 'C']
 * ]);
 *
 * print $db1['key'][0]."\n"; // Will print 'A'
 *
 * $db1['key']->delete();
 * $db1->delete();
 *
 */

/**
 * dbArray class that allows access to dba files as an array
 *
 * @category  DBA
 * @package   dbArray
 * @author    James Dornan <james@catch22.com>
 * @copyright 2003-2018 James Dornan <james@catch22.com>
 * @license   http://www.phpractical.com/license/0_50.txt P4PHP
 * @version   Release: 0.5
 * @link      http://www.phpractical.com/DbArray
 * @since     Class available since Release 0.5
 */
// class dbArray implements \Iterator, \ArrayAccess, \Countable, \Serializable {
// Would like to extend _ also.
// class dbArray extends _ implements \Iterator, \ArrayAccess, \Countable {
class dbArray implements \Iterator, \ArrayAccess, \Countable {
    /**
     * handler of currect db file
     *
     * @var string
     */
    private $_handler               = null;

    /**
     * false, for use when returning a reference
     *
     * @var boolean
     */
    private $_false                 = false;

    /**
     * current dba resource
     *
     * @var resource
     */
    private $_resource              = false;

    /**
     * current key
     *
     * @var string
     */
    private $_key                   = null;

    /**
     * get flag
     *
     * @var boolean
     */
    private $_get_key               = false;

    /**
     * flush flag
     *
     * @var boolean
     */
    private $_flush                 = false;

    /**
     * flush new flag
     *
     * @var boolean
     */
    private $_flushNew               = null;

    /**
     * current value
     *
     * @var mixed
     */
    private $_lastValue             = null;

    /**
     * current value
     *
     * @var mixed
     */
    private $_value                 = null;

    /**
     * debug
     *
     * @var boolean
     */
    private $_debug                 = true;

    /**
     * file info
     *
     * @var object
     */
    private $_fileinfo              = false;

    /**
     * storage object
     *
     * @var object
     */
    private $_storage_object        = false;

    /**
     * perferred handlers
     *
     * @var array
     */
    private $_perferred_handlers     = [
        'gdbm',
        'db4',
        'flatfile'
    ];

    /**
     * hidden keys
     *
     * @var array
     */
    private $_hidden_keys          = ['__count'];

    /**
     * backup of original hidden keys.
     *
     * @var array
     */
    private $_hidden_keys_backup   = false;

    /**
     * Default values
     *
     * @var array
     */
    private $_options               = [
        'readonly'      => false,
        'create'        => true,
        'truncate'      => false,
        'lock'          => false,
        'handler'       => false,
        'file'          => false,
        'unlink'        => false,
        'import'        => false,
        'debug'         => false,
        'processors'    => ['serialize', 'gzip'],
        'hidden_keys'   => []
    ];

    /**
     * db last mtime
     *
     * @vat int
     */
    private $_last_mtime = false;

    /**
     * write count
     *
     * @vat int
     */
    private $_writeCount = 0;

    /**
     * constructor
     *
     * @param string $options user supplied options
     */
    public function __construct($options = false)
    {
        // Check that the dba extnesion is loaded.
        $this->_checkExt();

        if (!class_exists('SplFileInfo'))
            throw new \Exception('SplFileInfo class is required.');

        if (!empty($options['file']))
            return $this->open($options);
    }

   /**
    * Check for the extension central to this class, dba.
    *
    * If the extension is not loaded we'll give it one more chance, then throw
    * an exception.
    */
    private function _checkExt()
    {
        if (!_::loadExtension('dba'))
            throw new \Exception('dba extension missing');
    }

    /**
     * Tasks to complete when the object is destroyed.
     */
    public function __destruct()
    {
        if ($this->_options['unlink'])
            $this->delete();
        else
            $this->_flushValue();
    }

    /**
     * sleep
     *
     * @return array
     */
    public function __sleep()
    {
        return [
            '_options',
            '_hidden_keys',
            '_hidden_keys_backup',
            '_value',
            '_key'
        ];
    }

    /**
     * wakeup
     *
     * @return void
     */
    public function __wakeup()
    {
        $this->open($this->_options);
    }

    /**
     * method used when object called as a string
     *
     * @return string
     */
    public function __toString()
    {
        return serialize($this->toArray());
    }

    /**
     * call method
     *
     * @param string $method     name of method being called
     * @param array  $parameters an array of parameters
     *
     * @return mixed
     *
     * @todo Add support for external extensions
     */
    public function __call($method, $parameters)
    {
        if (!is_object($this->_fileinfo) || $method == 'openFile')
            return false;

        if (method_exists($this->_fileinfo, $method))
            return call_user_func_array([$this->_fileinfo, $method], $parameters);

        // This would be a good place to call user extensions
        return false;
    }

    /**
     * set method
     *
     * @param string $name  name of member
     * @param mixed  $value value to be assigned to member
     *
     * @return boolean
     */
    public function __set($name, $value)
    {
        return $this->offsetSet($name, $value);
    }

    /**
     * get method
     *
     * @param string $name name of member
     *
     * @return mixed
     */
    public function &__get($name)
    {
        return $this->offsetGet($name, true);
    }

    /**
     * isset method
     *
     * @param string $name name of member
     *
     * @return boolean
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * unset method
     *
     * @param string $name name of member
     *
     * @return void
     */
    public function __unset($name)
    {
        return $this->offsetUnset($name);
    }

    /**
     * invoke magic method
     *
     * @param $data import array
     *
     * @return mixed
     */
     public function __invoke($data = false)
     {
         // No argument, then export data as an array.
         if (empty($data))
             return $this->toArray();

         // Import an array
         if (is_array($data))
             return $this->import($data);

         // Other actions here.
         switch($data) {
         default:
             echo 'This is a test.' . PHP_EOL;
         }
     }

    /**
     * Return mode string for dba_open
     *
     * @return string
     */
    public function getMode()
    {
        // import option settings into the current symbol table.
        extract($this->_options, EXTR_SKIP);

        // Illegal combinations
        if ($readonly && ($create || $truncate)) {
            $create   = false;
            $truncate = false;
        }

        if ($create && $truncate) {
            throw new \Exception('Cannot combine the create and truncate '
                . 'options.');
        }

        // Set the mode for open, based on the options supplied
        if ($readonly) {
            $mode = 'r';
        } else {
            $mode = 'w';

            // create if it does not exist
            if ($create)
                $mode = 'c';

            // create if it does not exist, and truncate if it does.
            if ($truncate)
                $mode = 'n';
        }

        // locking
        // I wonder if this is still required for windows.
        if ($lock)
            $mode .= \stripos($_ENV['OS'], 'windows') === false ? 'd' : 'l';

        return $mode;
    }

    /**
     * Remove db file
     *
     * @return boolean return value of unlink
     */
    public function delete()
    {
        $this->close();

        return file_exists($this->_options['file'])
            ? \unlink($this->_options['file'])
            : true;
    }

    /**
     * open db file
     *
     * @param string $options user supplied options
     *
     * @return boolean
     *
     * @todo This needs to be broken up a bit.
     */
    public function open(array $options)
    {
        // In case we are reusing this object with a new file.
        $this->close();

        // Merge supplied options with defaults
        $this->_options = \array_merge($this->_options, $options);

        // set debug flag
        $this->_debug = $this->_options['debug'] === true;

        // Check to see if this will be a new file.
        $created = !\file_exists($this->_options['file']);

        $this->_resource = \dba_popen($this->_options['file'], $this->getMode(),
            $this->getHandler());


        if ($this->_resource === false)
            throw new \Exception('cannot open file, ' . $this->_options['file']);

        // Backup of hidden keys so that they may be reset later.
        if ($this->_hidden_keys_backup === false)
            $this->_hidden_keys_backup = $this->_hidden_keys;

        // If count is missing, add it.
        if ($created) {
            $this['__count'] = 0;
        } elseif (!isset($this['__count'])) {

            // Add the missing __count for use with the \count function
            $keys  = $this->keys();

            // current cont is equal to the number of records in the file
            // minus the number of hidden keys within those records.
            $this['__count'] = count($keys) - count(array_intersect($keys,
                    $this->_hidden_keys));
 
            if ($count > 0)
                $this->rewind();
        }

        // See if we are importing an array into the db file.
        if ($this->_options['import']) {
            $this->import($this->_options['import']);
            $this->_options['import'] = false;
            $this->rewind();
        }

        // Set up our file into object for more information about the file
        // this class is representing.
        $this->_fileinfo = new \SplFileInfo($this->_options['file']);

        // Get the last mtime of the file.
        $this->_last_mtime = $this->getMTime();

        // Load storage processors.
        if (is_array($this->_options['processors']))
            $this->_storage_object = new dbArray\storage($this->_options['processors']);

        return true;
    }

    /**
     * clean up and close the db file.
     *
     * @return bool
     */
    public function close()
    {
        // nothing to close
        if (!$this->_resource)
            return true;

        // first flush any refence changes
        $this->_flushValue();

        // If it's not read only we need to optimize and sync
        if (!$this->_options['readonly']) {
            \dba_optimize($this->_resource);
            \dba_sync($this->_resource);
        }

        $resource        = $this->_resource;
        $this->_resource = false;
        $this->_fileinfo = false;

        return @\dba_close($resource);
    }

    /**
     * create new DbArray
     *
     * @param array $options array of options used my the constrctor
     *
     * @return object
     */
    public static function create($options)
    {
        return new self($options);
    }

    /**
     * return the preferrred handler
     *
     * @return string db file type
     */
    public function getHandler()
    {
        if (!empty($this->_options['handler']))
            return $this->_options['handler'];

        $handler = $this->getExtHandler($this->_options['file']);

        if (!empty($handler))
            return $handler;

        return $this->getPerferredHandler();
    }

    /**
     * return the name of the current file.
     *
     * @return string current file name
     */
    public function getFilename()
    {
        return $this->_options['file'];
    }

    /**
     * return name of handler from file externsion, if any.
     *
     * @param string $file name of file
     *
     * @return mixed valid dba handler or false
     */
    public function getExtHandler($file)
    {
        $path_parts = \pathinfo($file);
        $extension  = \strtolower($path_parts['extension']);

        if (\in_array($extension, \dba_handlers()))
            return $extension;
        else
            return false;
    }

    /**
     * return the perferred handler
     *
     * @return string
     */
    public function getPerferredHandler()
    {
        $handlers = \dba_handlers();

        foreach ($this->_perferred_handlers as $handler) {
            if (\in_array($handler, $handlers, true))
                return $handler;
        }
    }

    /**
     * check, whether a handler exists
     *
     * @param string $handler string name for a dba handler
     *
     * @return boolean
     */
    public function handlerExists($handler)
    {
        return \in_array($handler, \dba_handlers(), true);
    }

    /**
     * get hidden keys array
     *
     * @return array keys hidden when using next.
     */
    public function getHiddenkeys()
    {
        return $this->_hidden_keys;
    }

    /**
     * add hidden key(s)
     *
     * @param mixed $keys key or array of keys to be hidden
     *
     * @return void
     */
    public function addHiddenkeys($keys)
    {
        if (\is_object($keys))
            $keys = (array) $keys;
        else if (!\is_array($keys))
            $keys = [$keys];

        foreach ($keys as $index=>$key) {
            if (isset($this[$key])) {
                $this['__count']--;
                $this->_flushValue();
            }
        }

        $this->_hidden_keys = \array_merge($this->_hidden_keys, $keys);
    }

    /**
     * remove key(s) form the hidden list
     *
     * @param mixed $keys key or array of keys to be unhidden
     *
     * @return void
     */
    public function removeHiddenkeys($keys)
    {
        if (\is_object($keys))
            $keys = (array)$keys;
        else if (!\is_array($keys))
            $keys = [$keys];

        foreach ($keys as $index=>$key) {
            if (isset($this[$key])) {
                $this['__count']++;
                $this->_flushValue();
            }
        }

        $this->_hidden_keys = \array_diff($this->_hidden_keys, $keys);
    }

    /**
     * reset the hidden key list
     *
     * @return void
     */
    public function resetHiddenKeys()
    {
        // If there is no reason to reset, we wont.
        if ($this->_hidden_keys == $this->_hidden_keys_backup)
            return;

        // Adjust the count
        foreach(\array_merge($this->_hidden_keys, $this->_hidden_keys_backup)
                as $key) {

            // If the key never had an impact on the count we have nothing to do
            if (!isset($this[$key]))
                continue;

            if (
                \in_array($key, $this->_hidden_keys)
                && !\in_array($key, $this->_hidden_keys_backup)
            ) {
                $this['__count']++;
            }

            if (
                !\in_array($key, $this->_hidden_keys)
                && \in_array($key, $this->_hidden_keys_backup)
            ) {
                $this['__count']--;
            }

            $this->_flushValue();
        }

        $this->_hidden_keys = $this->_hidden_keys_backup;
    }

    /**
     * should key be hidden
     *
     * @param string $key array key
     *
     * @return boolean
     */
    public function isHidden($key)
    {
        return \in_array($key, $this->_hidden_keys, true);
    }

    /**
     * check, whether a value exists
     *
     * @param string $offset array key
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        // first flush any refence changes
        $this->_flushValue();

        return \dba_exists($offset, $this->_resource);
    }

    /**
     * write
     *
     * @param mixed $data to be stored in db file
     *
     * @return string
     */
    private function _write($data)
    {
        return $this->_storage_object ? 
            $this->_storage_object->write($data) : $data;
    }

    /**
     * read
     *
     * @param string $data output from dba_fetch
     *
     * @return mixed
     */
    private function _read($data)
    {
        return $this->_storage_object ?
            $this->_storage_object->read($data) : $data;
    }

    /**
     * _flushValue
     *
     */
    private function _flushValue()
    {
        $this->_d(__METHOD__);

        // If last action was not an offsetGet, then return.
        if (!$this->_flush) {
            $this->_d('Get flag not true.');
            return;
        }

        // set the flag back.
        $this->_flush = false;

        if ($this->_lastValue === $this->_value)
            return;

        $this->_d('Get Key = ');
        \ob_start();
        \var_dump($this->_get_key);
        $this->_d(\ob_get_contents());
        \ob_end_clean();

        $this->_d('Value = ');
        \ob_start();
        \var_dump($this->_value);
        $this->_d(\ob_get_contents());
        \ob_end_clean();

        $this->offsetSet($this->_get_key, $this->_value);

        if (!$this->_options['readonly'])
            \dba_sync($this->_resource);
    }

    /**
     * get a value from a db file using the key.
     *
     * This is tricky, so please try to follow. This function will return the
     * value found at the requested offset as a reference. The returned
     * reference can be altered by the interpeter after being returned. We
     * have no idea if the variable returend has been altered or not. We're
     * going to have to check, somehow.
     *
     * First step is to flag that we have performed an offset get which might
     * be altered. This is done only if the dba file is opened writable.
     * 
     * Next we need to flush the changed variable to the dba file. This is done
     * by checking if we need to run flushValue at the start of almost any method.
     * Using a trick function did not appear to work well enough, and was like
     * using a fully automatic machine gun to swat a fly.
     *
     * @param string $offset array key
     * @param boolean $flush should we flag for later flushing, default true
     *
     * @return mixed
     */
    public function &offsetGet($offset, $flush = true)
    {
        $this->_d(__METHOD__ . ' "' . $offset . '"');

        // first flush any refence changes
        if ($this->_flush)
            $this->_flushValue();

        // set the _flush flag to true, if $flush is true and this is a file we
        // can write to.
        // If the readonly option is not true _flush will be se to true.
        if ($flush)
            $this->_flush = !$this->_options['readonly'];

        // If the request is for the current value, just return that.
        if ($offset !== null && $this->_key === $offset) {
            $this->_flushNew  = false;
            $this->_lastValue = $this->_value;
            $this->_get_key   = $offset;

            return $this->_value;
        }

        if (($value = \dba_fetch($offset, $this->_resource)) !== false) {
            $this->_flushNew   = false;
            $this->_value      = ($offset === '__count' ? $value : $this->_read($value));
            $this->_lastValue  = $this->_value;
            $this->_key        = $offset;
            $this->_get_key    = $offset;

            return $this->_value;
        }

        $this->_flushNew   = true;
        $this->_get_key    = $offset;
        $this->_key        = false;
        $this->_value      = null;
        $this->_lastValue  = false;

        return $this->_value;
    }

    /**
     * set a property
     *
     * @param string $offset array key
     * @param mixed  $value  array value
     *
     * @return boolean
     */
    public function offsetSet($offset, $value)
    {
        $this->_d(__METHOD__);

        // first flush any refence changes
        $this->_flushValue();

        // I know. Can you tell me a better way?
        // Search for the lowest unused numeric offset.
        if ($offset === null || $offset == '')
            for ($offset = 0; isset($this[$offset]); $offset++) {}

        // Check to see if this a hiddent offset
        $hidden = $this->isHidden($offset);

        $this->_d(__METHOD__ . ' Offset: ' . $offset);
        // $this->_d(__METHOD__ . ' Value: ' . $value);

        // I suspect that the count will have to be updated using a different
        // distinct method to make the code more clear, simple, and readable.
        // Also, should updating the count change the _key and _value variables
        // at all?
        if ($offset === '__count') {
            if (!\is_numeric($value)) {
                throw new \Exception('illegal non-numeric value for __count "'
                    . $value . '"');
            }

            $writevalue = (int) $value;
        } else {
            $writevalue = $this->_write($value);
        }

        if (isset($this[$offset])) {
            if ($this->_options['handler'] == 'cdb')
                throw new \Exception('Illegal cdb update, set or get only.');

            if (!\dba_replace($offset, $writevalue, $this->_resource)) {
                throw new \Exception('Replace - offset ' . $offset .
                    ' with value of ' . $value);
            }
        } else {
            if (!\dba_insert($offset, $writevalue, $this->_resource)) {
                throw new \Exception('Insert - offset ' . $offset .
                    ' with value of ' . $value);
            }

            // increment the count of visible elements
            if (!$hidden) {
                $this['__count']++;
                $this->_flushValue();
            }
        }

        // For visible values we'll set the current key and value properties for
        // use in key, current, and others.
        $this->_key   = $hidden ? null : $offset;
        $this->_value = $hidden ? null : $value;

        return true;
    }

    /**
     * unset a property
     *
     * @param string $offset array key
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->_flushValue();

        if (\dba_delete($offset, $this->_resource)) {
            if (!$this->isHidden($offset)) {
                $this['__count']--;
                $this->_flushValue();
            }
        }
    }

    /**
     * Return the array "pointer" to the first element
     * PHP's reset() returns false if the array has no elements
     *
     * @return void
     */
    public function rewind()
    {
        // first flush any refence changes
        $this->_flushValue();

        // Get first key
        $key = \dba_firstkey($this->_resource);

        // Search for the first visible element.
        while ($this->isHidden($key)) {
            $key = \dba_nextkey($this->_resource);
        }

        // It's important to get the value. This will set the current element.
        $this->offsetGet($key, false);
    }

    /**
     * Return the current array element
     *
     * @return mixed
     */
    public function current()
    {
        return $this->_value;
    }

    /**
     * Return the key of the current array element
     *
     * @return mixed
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     * next, advance one element in the array
     *
     * @return void
     */
    public function next()
    {
        // Keep setting $key to the next key, until we hit the end.
        // Skipping any kidden keys along the way.
        do {
            if (($key = @\dba_nextkey($this->_resource)) === false) {
                $this->_key   = false;
                $this->_value = false;
                return;
            }
        } while ($this->isHidden($key));

        // This will _key and _value.
        $this->offsetGet($key, false);
    }

    /**
     * Is the current element valid?
     *
     * @return boolean
     */
    public function valid()
    {
        return isset($this[$this->_key]);
    }

    /**
     * Count of array elements
     *
     * @return int
     *
     * @todo is this useless?
     */
    public function count()
    {
        return isset($this['__count']) ? (int) $this['__count'] : 0;
    }

    /**
     * to array
     *
     * @param array $keys limit export to only these keys
     *
     * @return array
     */
    public function toArray(array $keys = [])
    {
        if (\count($this) < 1)
            return [];

        $returnArray = [];

        foreach ($this as $offset=>$value) {
            // If we are only looking for certain kiys, we'll skip the others
            if (\count($keys) > 0 && \in_array($offset, $keys, true))
                continue;

            // If the value is a nested version of this class invoke it's
            // toArray method also.
            if (\gettype($value) == 'object' && \is_a($value, __CLASS__))
                $value = $value->toArray();

            $returnArray[$offset] = $value;
        }

        return $returnArray;
    }

    /**
     * import an array
     *
     * @param array $import array to be incorporated into the db file
     *
     * @return void
     */
    public function import(array $import)
    {
        if (!$this->_resource)
            throw new \Exception('import failed, db file not open.');

        if (\count($import) < 1)
            throw new \Exception('import array or object empty.');

        if (!\is_array($import) && !\is_object($import))
            throw new \Exception('only array or objects may be imported.');

        foreach ($import as $offset=>$value) {
            $this[$offset] = $value;
        }
    }

    /**
     * return an array of all the keys
     *
     * @param mixed $callback
     *
     * @return boolean
     */
    public function walk(callable $callback)
    {
        if (count($this) < 1)
            return false;

        foreach($this as $key=>$value) {
            $this[$key] = call_user_func_array($callback, [$value, $key]);
        }

        return true;
    }

    /**
     * search for a specific value and return the first key that matches
     *
     * @param mixed $needle
     * @param boolean $strict
     *
     * @return boolean
     */
    public function search($needle, $strict = false)
    {
        if (!is_numeric($needle) && !is_string($needle) && !is_bool($needle))
            throw new \Exception('Illegal argument supplied.');

        if (count($this) < 1)
            return false;

        foreach($this as $key=>$value) {
            if (
                (!$strict && $value == $needle)
                || ($strict && $value === $needle)
            ) {
                $this->rewind();
                return $key;
            }
        }

        return false;
    }

    /**
     * return an array of all the keys
     *
     * @param boolean $hidden if true include the hidden keys
     *
     * @return array
     */
    public function keys($hidden = false)
    {
        if (!$this->_resource)
            return false;

        $keys = [];

        for (
            $key = \dba_firstkey($this->_resource);
            $key !== false;
            $key = \dba_nextkey($this->_resource)
        ) {
            if (!$hidden && $this->isHidden($key))
                continue;

            $keys[] = $key;
        }

        @\sort($keys);

        return $keys;
    }

    /**
     * debug message to stdout
     *
     * @param string message
     */
    private function _d($msg)
    {
        if ($this->_debug)
            echo $msg . PHP_EOL;
    }
}

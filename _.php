<?php
// This is an example of how the new class would be used.
namespace phprax;

/**
 * The nexus class should provide easy access to all areas within
 * the framework. No need to know the guts. All other classes in
 * the framework should extend this class, making all other classes
 * a window infor the framework.
 *
 * Initial Startup
 *
 * When first starting nexus will bootstrap a minimal setup to move
 * to a more complex and configurable setup after that. Since the
 * global items only need to be setup once, the initialization will
 * check for a flag before running. If that flag is set then this
 * step is skipped.
 *
 * Initializing steps
 *
 * 1) Add directory of currently executing file to the include_path
 *
 * 2) Registry simple autoload for classes using namespace paths.
 *    It's at this point that we can call other classes.
 *
 * What form should this flag take?
 * Let's try a constant within out namespace.
 *
 * Here is what our define might look like.
 * define(__NAMESPACE__ . '\INIT', true);
 */
class _
{
    /**
     * set debugging output
     *
     * @var boolean
     */
    protected $_debug = false;

    /**
     * user for returning a reference
     *
     * @var boolean
     */
     const TRUE = true;

    /**
     * user for returning a reference
     *
     * @var boolean
     */
     const FALSE = false;

    /**
     * Constructor
     *
     * @param boolean $debug
     *
     * @return void
     */
    public function __construct($debug = false)
    {
        // set debugging
        $this->debug($debug);

        // initialize
        _::init();
    }

   /**
    * init
    *
    * Initialize various items for the environment
    *
    * @return void
    */
    public static function init()
    {
        // No need to initialize twice.
        if (defined(__NAMESPACE__ . '\INIT'))
            return;

        // add the directory of this file to the include path
        _::_incPath();

        // Start a simplge namespace based autoloader
        _::_autoload();

        // Once done we set INIT
        define(__NAMESPACE__ . '\INIT', true);
    }

    /**
     * _
     *
     * This method simply returns the current class or a new one.
     *
     * @return object
     * @note: Should this always return a nexus class or the current
     *        class which has extented the nexus class?
     */
    public function &_($new = false)
    {
        return ($new ? new self($this->_debug) : $this);
    }

    // $db = _::_('dbarray')->import([1,1,123,3,43,4,4,4]);
    /**
     * Invoke
     */
    public function __invoke()
    {
        $argv = func_get_args();

        if (count($argv) < 1)
            return; // Should do something here.

        if (count($argv) == 1 && is_string($argv[0]))
            return new $argv[0];

        //if (is_string($argv[0]) && class_exists($argv[0]))
            //return new {$argv[0]}(
    }
    /**
     * inc path
     *
     * Place the current directory first in the include path, unless it's
     * already in the path.
     *
     * @return void
     */
    private static function _incPath()
    {
        $inc = get_include_path();
        $new = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');

        // If we have a path, and our new directory is in it, we
        // have nothing to do.
        if (empty($inc))
            set_include_path($new);
        else if (!in_array($new, explode(PATH_SEPARATOR, $inc)))
            set_include_path($new . PATH_SEPARATOR . $inc);
    }

    /**
     * debug
     *
     * set debug flag
     *
     * @return viod
     */
    public function debug($debug)
    {
        $this->_debug = $debug;
    }

    /**
     * _autoload
     *
     * Start up a very simple namespace based autoloader to get things
     * moving.
     *
     * @return boolean
     * @note spl_autoload is broken and requires a patch to load files
     *       of mixed case. This is a huge bug and, as of yet, not
     *       addressed. I have created my own patch which first tries
     *       to load a file with the same name as the class, and if
     *       that fails, it will try to load a file which is lowercase.
     */
    private static function _autoload()
    {
        spl_autoload_extensions('.php');
        spl_autoload_register(__NAMESPACE__ . '\\_::_nsAutoLoad');
        spl_autoload_register();
        return;
    }

    /**
     * _nsAutoLoad
     *
     * Case sensitive autoload, using namespace as filesytem.
     *
     * @param string $class_name
     */
    private static function _nsAutoLoad($class_name)
    {
        require_once(str_replace('\\', '/', $class_name) . '.php');
    }

    /**
     * __callstatic
     *
     * Static methods all live in the __CLASS__\method\ directory
     * named for the static method being called.
     *
     */
    public static function __callStatic($method, array $args)
    {
        $class = __CLASS__ . '\\method\\' . $method;

        $call = __CLASS__ . '\\method\\' . $method . '::callStatic';

        return call_user_func_array($call, $args);
    }

    /**
     * __call
     *
     * Methods live in the __CLASS__\method\ directory named for the method
     * being called.
     *
     */
    public function __call($method, array $args)
    {
        $callClass = __CLASS__ . '\\method\\' . $method;

        array_unshift($args, $this);

        return call_user_func_array([new $callClass($this), 'call'], $args);
    }
}

// Should initialize on contact. Not sure this is a good idea.
_::init();

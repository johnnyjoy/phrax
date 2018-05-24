<?PHP
namespace phprax\dbArray;
/**
 * storage handles storage of the data
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
 */

/**
 * vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
 *
 * @category  DBA
 * @package   DbArray
 * @author    James Dornan <james@catch22.com>
 * @copyright 2003-2008 James Dornan <james@catch22.com>
 * @license   http://www.phpractical.com/license/0_50.txt P4PHP
 * @version   Release: 0.5
 * @link      http://www.phpractical.com/DbArray
 * @since     Class available since Release 0.5
 */
class storage
{
    /**
     * conversion objects
     *
     * @var array
     */
    private $_processors = false;

    /**
     * construct
     *
     * @param array $plugins an array of stoage plugins
     *
     * @return void
     */
    public function __construct(array $processors)
    {
        if (count($processors) < 1)
            return;

        foreach($processors as &$processor) {
            if (is_object($processor)) {
                $this->_processors[] = &$processor;
                continue;
            }
            // If we want to pass options to the processor
            // this is where we would do it.
            $classname = 'phprax\\dbArray\\storage\\processor\\' . $processor;

            if (!class_exists($classname))
                throw new Exception('dbArray illegal processor class name');

            $this->_processors[] = new $classname();
        }
    }

    /**
     * write
     *
     * @param mixed $data input data
     *
     * @return mixed
     */
    public function write($data)
    {
        if (!$this->_processors)
            return $data;

        foreach($this->_processors as &$processor) {
            $data = $processor->write($data);
        }

        return $data;
    }

    /**
     * read
     *
     * @param mixed $data output data
     *
     * @return mixed
     */
    public function read($data)
    {
        if (!$this->_processors)
            return $data;

        for($i = count($this->_processors); $i > 0; $i--) {
            $data = $this->_processors[($i -1)]->read($data);
        }

        return $data;
    }
}

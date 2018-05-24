<?PHP
namespace phprax\dbArray\storage\processor;
use phprax\dbArray\storage;
/**
 * DbArray_Filter_Serialize serializes data for DbArray
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
class serialize implements storage\processor
{
    /**
     * write
     *
     * @param mixed &$data input data
     *
     * @return mixed
     */
    public function write($data)
    {
        // If it's scalar then we'll store it as-is.
        // return is_scalar($data) ? $data : serialize($data);
        return serialize($data);
    }

    /**
     * read
     *
     * @param mixed &$data output data
     *
     * @return mixed
     */
    public function read($data)
    {
        // If the data was not serialized then we'll return as-is.
        $unserialized = @unserialize($data);

        // If it's not a serialized boolean false value, and unserialized
        // returned flase we'll just return the data as-si.
        return (($data != 'b:0;' && $unserialized === false) ? $data :
            $unserialized);
    }
}

<?PHP
namespace phprax\dbArray\storage;
/**
 * DbArray_Plugin is an interface for DbArray Plugin classes
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
interface processor
{
    /**
     * write
     *
     * @param mixed &$data input data
     *
     * @return mixed
     */
    public function write($data);

    /**
     * read
     *
     * @param mixed &$data output data
     *
     * @return mixed
     */
    public function read($data);
}

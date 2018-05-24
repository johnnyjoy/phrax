<?PHP
namespace phprax\dbArray\storage\processor;
use phprax\dbArray\storage;
/**
 * DbArray_Filter_Gzip is a compression plugin for DbArray
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
class gzip implements storage\processor
{
    /**
     * compression level
     *
     * @var $level
     */
    protected $level = 9;

    /**
     * compression threshhold
     *
     * @var int
     */
     protected $threshhold = 10000;

    /**
     * In filter
     *
     * @param mixed $data input data
     *
     * @return mixed
     */
    public function write($data)
    {
        return (strlen($data) > $this->threshhold ?
            gzcompress($data, $this->level) : $data);
    }

    /**
     * Out filter
     *
     * @param mixed $data output data
     *
     * @return mixed
     */
    public function read($data)
    {
        $uncompressed = @gzuncompress($data);
        return ($uncompressed !== false ? $uncompressed : $data);
    }
}

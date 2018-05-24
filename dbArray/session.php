<?PHP
namespace phprax\_\dbArray;
use phprax\_\dbArray;
/**
 * DbArray_Session a session manager using DbArray
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
 * @note      Mostly untested
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
 * @version   Release: 0.5.2
 * @link      http://www.phpractical.com/DbArray
 * @since     Class available since Release 0.5
 */
class session
{
    /**
     * maxlifetime
     *
     * Maximum lifetime for a session in seconds.
     *
     * @var int
     */
    private $_maxlifetime   = 3600 * 72;

    /**
     * DbArray object
     *
     * @var object
     */
    private $_db            = false;

    /**
     * default options
     *
     * @var array
     */
    private $_options       = [
        'dir'    => false,
        'file'   => false,
        'gzip'   => true];

    /**
     * constructor
     *
     * @param array $options option passed to start db sessions
     *
     * @return void
     */
    public function __construct(array $options)
    {
        $this->_options['dir'] =
            get_cfg_var('session.save_path') ?
            get_cfg_var('session.save_path') :
            '/tmp';
        $this->_options        = array_merge($this->_options, $options);
        $this->_maxlifetime    = get_cfg_var('session.gc_maxlifetime');

        if (session_set_save_handler(
            [&$this, 'open'],
            [&$this, 'close'],
            [&$this, 'read'],
            [&$this, 'write'],
            [&$this, 'destroy'],
            [&$this, 'gc']
        )) {
            session_start();
        } else {
            throw new Exceptiion('session_set_save_handler failed.');
        }
    }

    /**
     * destruct
     *
     * @see http://bugs.php.net/bug.php?id=33772
     *
     * @return void
     */
    public function __destruct()
    {
        @session_write_close();
    }

    /**
     * open, placeholder
     *
     * @return boolean true
     */
    public function open()
    {
        $this->_db = new dbArray([
            'file'    => $this->_options['dir']
                . DIRECTORY_SEPARATOR
                . ($this->_option['file'] ? $this->_option['file'] : '/sessions.db'),
            'plugins' => ($this->_options['gzip'] ? ['gzip'] : false)]);

        return true;
    }

    /**
     * close
     *
     * with a database session there is nothing to do at this point.
     *
     * @return boolean true
     */
    public function close()
    {
        return true;
    }

    /**
     * read
     *
     * Read session data from the database using the session ID
     *
     * @param string $id session id
     *
     * @return string session data
     */
    public function read($id)
    {
        return isset($this->_db[$id]) ?
            preg_replace('/_mtime\|i:\d+;/', '', $this->_db[$id]) : '';
    }

    /**
     * write
     *
     * @param string $id      session id
     * @param string $session session data
     *
     * @return boolean
     */
    public function write($id, $session)
    {
        if ($session)
            $this->_db[$id] = '_mtime|i:' . mktime() . ';'.$session;

        return true;
    }

    /**
     * destroy
     *
     * @param string $id session id
     *
     * @return void
     */
    public function destroy($id)
    {
        unset($this->_db[$id]);
    }

    /**
     * gc
     *
     * @param int $maxlifetime seconds old to expire
     *
     * @return boolean
     */
    public function gc($maxlifetime)
    {
        $expire = mktime() - $maxlifetime;

        foreach ($this->_db as $id=>$session) {
            if (preg_replace(
                '/^_mtime|i:\(\d+\);.*/', '${1}',
                $session
            ) - $expire < mktime() - $maxlifetime) {
                unset($this->_db[$id]);
            }
        }
        return true;
    }

    /**
     * Start sessions
     *
     * @param array $options
     *
     * @return object
     */
    public static function start(array $options)
    {
        return new self($options);
    }
}

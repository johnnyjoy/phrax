#!/web/bin/php
<?PHP
namespace phprax;
/**
 * dbArray Examples
 *
 * PHP version 5
 *
 * @category  DBA
 * @package   dbArray
 * @author    James Dornan <james@catch22.com>
 * @copyright 2005-2008 James Dornan <james@catch22.com>
 * @license   http://www.phpractical.com/license/0_50.txt P4PHP
 * @version   SVN: 1786
 * @link      http://www.phpractical.com/dbArray
 * @see       http://www.php.net/dba
 * @todo      More examples
 * @motto     Cleverness catches it's master.
 *
 */
error_reporting(E_ALL);

require_once '_.php';

new _();

$p = new properties;
$p->load('/tmp/props');
print_r($p);
?>

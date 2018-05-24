<?PHP
namespace phprax;
use phprax\log;

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PHP version 5
 *
 * LICENSE:
 * This source file is subject to version 1.0 of the Dornan license
 * that is available through the world-wide-web at the following URI:
 * http://www.dornan.com/license/1_0.txt. If you did not receive a copy of
 * the Dornan License and are unable to obtain it through the web, please
 * send a note to license@dornan.com so we can mail you a copy immediately.
 *
 * DISCLAIMER:
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * A simple class use to pass parameters around
 *
 * @package    Phprax
 * @category   Log
 * @author     James Dornan <james@catch22.com>
 * @copyright  1998-2005 James Dornan <james@catch22.com> All rights reserved.
 * @license    http://www.catch22.com/license/1_0.txt
 * @version    0.95
 *
 * Log to a file, syslog, with the option to output a single line at a time
 * or all at once to the file. Types of items to log, info, verbose, debug,
 * error, etc.
 *
 * $_('log', array(params))
 */
class log
{
   /**
    * configuration information array
    *
    * @var array properties
    */
    protected $lines = [];

   /**
    * Properties constructor
    * 
    * @method void __construct(mixed $properties) 
    * 
    * @param mixed $properties Array to be loaded, of file name to be parsed
    * @return void
    */
    public function __construct($properties = [])
    {
        if (is_array($properties))
            $this->setProperties($properties);
        else if (is_string($properties))
            $this->load($properties);
    }

   /**
    * set properties array
    * 
    * @method void setProperties(array $properties) 
    *
    * @param array $properties
    * @return void
    */
   public function setProperties($properties)
   {
       $this->properties = &$properties;
   }

   /**
    * Get property element
    *
    * @method mixed __get(mixed $offset)
    *
    * @param mixed $offset
    * @return mixed
    */
    public function __get($offset)
    {
        return isset($this->properties[$offset]) ? $this->properties[$offset] : null;
    }

   /**
    * Set a property
    *
    * @method mixed __set(mixed $offset, mixed $value)
    *
    * @param mixed $offset
    * @param mixed $value
    * @return void
    */
    public function __set($offset, $value)
    {
        $this->properties[$offset] = $value;
    }

   /**
    * Return the properties array
    *
    * @method mixed toArrayt()
    *
    * @return array
    */
    public function toArray()
    {
        return $this->properties;
    }

   /**
    * check, whether a property exists
    *
    * @method boolean offsetExists(mixed $offset)
    *
    * @param mixed $offset
    * @return boolean
    */
    public function offsetExists($offset)
    {
        return isset($this->properties[$offset]);
    }

   /**
    * Get a property
    *
    * @method mixed &offsetGet(mixed $offset)
    *
    * @param mixed $offset
    * @return mixed
    */
    public function &offsetGet($offset)
    {
        return $this->properties[$offset];
    }

   /**
    * Set a property
    *
    * @method mixed offsetSet(mixed $offset)
    *
    * @param mixed $offset
    * @param mixed $value
    * @return void
    */
    public function offsetSet($offset, $value)
    {
        $this->properties[$offset] = $value;
    }

   /**
    * Unset a property
    *
    * @method void offsetUnset(mixed $offset)
    *
    * @param mixed $offset
    * @return void
    */
    public function offsetUnset($offset)
    {
        unset($this->properties[$offset]);
    }

   /**
    * Count properties elements
    *
    * @method int count()
    *
    * @return int
    */
    public function count()
    {
        return count($this->properties);
    }

   /**
    * IteratorAggregate data
    *
    * @method object getIterator()
    *
    * @return object 
    */
    public function getIterator()
    {
        return new ArrayIterator($this->properties);
    }

   /**
    * Load file into the properties array
    *
    * @method void load(string $filename)
    *
    * @param $filename string
    *
    * @return void
    * @link http://php.net/manual/en/function.file.php
    */
    public function load($filename)
    {
        $this->properties = [];

        if (($lines = file($filename)) === false)
            throw new Exception('Cannot read file ' . $filename);

        $this->parse($lines);
    }

   /**
    * Parse array of lines from a java style properties file
    *
    * @method void parse(array $lines)
    *
    * @param $lines array
    *
    * @return void
    * @link http://php.net/manual/en/function.strpos.php
    * @link http://php.net/manual/en/function.strlen.php
    * @link http://php.net/manual/en/function.substr.php
    * @link http://php.net/manual/en/function.unset.php
    */
    public function parse(array $lines)
    {
        $result            = [];
        $key               = null;
        $isWaitingNextLine = false;

        foreach($lines as $i => $line) {
            if (empty(trim($line)) || (!$isWaitingNextLine && strpos($line, '#') === 0))
                continue;

            if (!$isWaitingNextLine) {
                $key   = substr($line, 0, strpos($line, '='));
                $value = substr($line, strpos($line, '=') + 1, strlen($line));
            } else {
                $value .= $line;
            }

            if (strrpos($value, "\\") === strlen($value) - strlen("\\")) {
                $value = substr($value, 0, strlen($value) - 1) . "\n";
                $isWaitingNextLine = true;
            } else {
                $isWaitingNextLine = false;
            }

            $result[trim($key)] = trim($value);
            unset($lines[$i]);
        }

        $this->properties = &$result;
    }
}



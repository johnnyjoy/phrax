<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * FileInfo is an extension of \SplFileInfo with a few added methods.
 *
 * PHP version 5.4+
 *
 * LICENSE:
 * This source file is subject to version 1.0 of the PHPractical license
 * that is available through the world-wide-web at the following URI:
 * http://www.phpractical.com/license/0_50.txt  If you did not receive a copy of
 * the PHPractical License and are unable to obtain it through the web, please
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
 * @category   File
 * @package    SC
 * @author     James Dornan <james@catch22.com>
 * @copyright  1998-2005 James Dornan <james@catch22.com> All rights reserved.
 * @motto      Cleverness catches it's master.
 */

namespace SC;

/**
 * Class: File
 *
 * @see \SplFileInfo
 */
class File extends \SplFileInfo
{
    /**
     * __construct
     *
     * @param string $file_name
     */
    public function __construct($file_name)
    {
        parent::__construct($file_name);
        $this->setInfoClass(__CLASS__);
    }

    /**
     * exists
     *
     * @return boolean
     */
    public function exists()
    {
        return ($this->getPathName() ? \file_exists($this->getPathName()) : false);
    }

    /**
     * touch
     *
     * @return boolean
     */
    public function touch()
    {
        return ($this->getPathName() ? \touch($this->getPathName()) : false);
    }

    /**
     * mkdir
     *
     * @return boolean
     */
    public function mkdir()
    {
        return ($this->getPathName() ? \mkdir($this->getPathName()) : false);
    }

    /**
     * getContents
     *
     * @param boolean $use_include_path
     * @param int $offset
     */
    public function getContents($use_include_path = false, $offset = 0)
    {
        return ($this->getPathName() ? @\file_get_contents($this->getPathName(),
            $use_include_path, null, $offset) : null);
    }

    /**
     * putContents
     *
     * @param string $data
     * @param int $flags
     */
    public function putContents($data, $flags = 0)
    {
        return ($this->getPathName() ? @\file_put_contents($this->getPathName(),
            $data, $flags) : false);
    }
}

<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP version 5
 *
 * Copyright (c) 2009 KUBO Atsuhiro <kubo@iteman.jp>,
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    Piece_Unity
 * @subpackage Piece_Unity_Component_ExceptionHandler
 * @copyright  2009 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @link       http://d.hatena.ne.jp/perezvon/20070227/1172572129
 * @since      File available since Release 0.1.0
 */

require_once 'PEAR/Config.php';

// {{{ Piece_Unity_Service_ExceptionHandler_DebugInfo

/**
 * @package    Piece_Unity
 * @subpackage Piece_Unity_Component_ExceptionHandler
 * @copyright  2009 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @link       http://d.hatena.ne.jp/perezvon/20070227/1172572129
 * @since      Class available since Release 0.1.0
 */
class Piece_Unity_Service_ExceptionHandler_DebugInfo implements Piece_Unity_Service_ExceptionHandler_Interface
{

    // {{{ properties

    /**#@+
     * @access public
     */

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    /**#@-*/

    /**#@+
     * @access public
     */

    // }}}
    // {{{ handle()

    /**
     * @param Exception $exception
     */
    public function handle(Exception $exception)
    {
        $viewElement = new Piece_Unity_ViewElement();
        $viewElement->setElement('debugInfo',
             (object)array('exception' => $exception,
                           'source' => $this->_ripSource($exception->getFile(), $exception->getLine(), 10),
                           'trace' => preg_replace('/^#\d+ /', '', explode("\n", $exception->getTraceAsString())))
                                 );

        $config = new PEAR_Config();
        Piece_Unity_Service_ExceptionHandler_Rendering_PHP::render(
            $config->get('data_dir') . '/pear.piece-framework.com/Piece_Unity_Component_ExceptionHandler/' . basename(__FILE__),
            $viewElement
                                                                  );
    }

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    // }}}
    // {{{ _ripSource()

    /**
     * @param string  $file
     * @param integer $targetLine
     * @param integer $limit
     * @return string
     */
    private function _ripSource($file, $targetLine, $limit)
    {
        $startLine = $targetLine - $limit;
        if ($startLine < 1) {
            $startLine = 1;
        }

        $source = array();
        $handle = fopen($file, 'r');
        for ($currentLine = 1; !feof($handle); ++$currentLine) {
            if ($currentLine < $startLine) {
                fgets($handle, 4096);
                continue;
            }

            if ($currentLine > $targetLine + $limit) {
                break;
            }

            $code = rtrim(fgets($handle, 4096), "\x0d\x0a");
            $source[] = (object)array('line' => $currentLine, 'code' => $code);
        }
        fclose($handle);

        return $source;
    }        

    /**#@-*/

    // }}}
}

// }}}

/*
 * Local Variables:
 * mode: php
 * coding: iso-8859-1
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * indent-tabs-mode: nil
 * End:
 */

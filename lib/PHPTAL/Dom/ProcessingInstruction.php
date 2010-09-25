<?php
/**
 * PHPTAL templating engine
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  PHPTAL
 * @author   Laurent Bedubourg <lbedubourg@motion-twin.com>
 * @author   Kornel Lesiński <kornel@aardvarkmedia.co.uk>
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @link     http://phptal.org/
 */

/**
 * processing instructions, including <?php blocks
 *
 * @package PHPTAL
 * @subpackage Dom
 */
class PHPTAL_Dom_ProcessingInstruction extends PHPTAL_Dom_Node
{
    public function generateCode(PHPTAL_Php_CodeWriter $codewriter)
    {
        $codewriter->pushHTML($codewriter->interpolateHTML($this->getValueEscaped()));
    }
}

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
 * @version  SVN: $Id: UnknownModifierException.php 3527 2012-04-25 23:23:24Z ldath $
 * @link     http://phptal.org/
 */

/**
 * ${unknown:foo} found in template
 *
 * @package PHPTAL
 * @subpackage Exception
 */
class PHPTAL_UnknownModifierException extends PHPTAL_TemplateException
{
    private $modifier_name;
    public function __construct($msg, $modifier_name = null)
    {
        $this->modifier_name = $modifier_name;
        parent::__construct($msg);
    }

    public function getModifierName()
    {
        return $this->modifier_name;
    }
}

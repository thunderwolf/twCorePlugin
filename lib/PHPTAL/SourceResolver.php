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
 * @version  SVN: $Id: SourceResolver.php 2949 2009-11-07 20:35:23Z ldath $
 * @link     http://phptal.org/
 */

/**
 * @package PHPTAL
 */
interface PHPTAL_SourceResolver
{
    /**
     * Returns PHPTAL_Source or null.
     */
    public function resolve($path);
}
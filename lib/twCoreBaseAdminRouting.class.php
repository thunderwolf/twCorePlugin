<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: twCoreBaseAdminRouting.class.php 3306 2010-09-04 18:37:19Z ldath $
 */
class twCoreBaseAdminRouting
{
	static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
	{
		$r = $event->getSubject();

		$r->prependRoute('change_language', new sfRoute('/change_language', array('module' => 'twMain', 'action' => 'language')));
	}

	static public function addRouteForPlugin(sfEvent $event)
	{
		$event->getSubject()->prependRoute('tw_plugin', new sfPropelRouteCollection(array(
			'name'                 => 'tw_plugin',
			'model'                => 'twPlugin',
			'module'               => 'twPlugin',
			'prefix_path'          => 'tw_plugin',
			'with_wildcard_routes' => true,
			'requirements'         => array(),
		)));
	}

	static public function addRouteForSettings(sfEvent $event)
	{
		$event->getSubject()->prependRoute('tw_settings', new sfPropelRouteCollection(array(
			'name'                 => 'tw_settings',
			'model'                => 'twSettings',
			'module'               => 'twSettings',
			'prefix_path'          => 'tw_settings',
			'column'               => 'id',
			'with_wildcard_routes' => true,
			'requirements'         => array(),
		)));
	}
}
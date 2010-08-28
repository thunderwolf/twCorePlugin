<?php

/**
 * Subclass for performing query and update operations on the 'tw_plugin' table.
 *
 *
 *
 * @package plugins.twCorePlugin.lib.model
 */
class PlugintwPluginPeer extends BasetwPluginPeer
{
	public static function retrieveByPosition($position, $con = null) {
		return sfPropelActAsSortableBehavior::retrieveByPosition('twPluginPeer', $position, $con);
	}

	public static function getMaxPosition($con = null) {
		return sfPropelActAsSortableBehavior::getMaxPosition('twPluginPeer', $con);
	}

	public static function doSelectOrderByPosition($order = Criteria::ASC, $criteria = null, $con = null) {
		return sfPropelActAsSortableBehavior::doSelectOrderByPosition('twPluginPeer', $order, $criteria, $con);
	}

	public static function doSort($order, $con = null) {
		return sfPropelActAsSortableBehavior::doSort('twPluginPeer', $order, $con);
	}
}

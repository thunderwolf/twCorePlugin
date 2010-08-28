<?php

class PlugintwPluginModulePeer extends BasetwPluginModulePeer
{
	public static function retrieveByPosition($position, $con = null) {
		return sfPropelActAsSortableBehavior::retrieveByPosition('twPluginModulePeer', $position, $con);
	}

	public static function getMaxPosition($con = null) {
		return sfPropelActAsSortableBehavior::getMaxPosition('twPluginModulePeer', $con);
	}

	public static function doSelectOrderByPosition($order = Criteria::ASC, $criteria = null, $con = null) {
		return sfPropelActAsSortableBehavior::doSelectOrderByPosition('twPluginModulePeer', $order, $criteria, $con);
	}

	public static function doSort($order, $con = null) {
		return sfPropelActAsSortableBehavior::doSort('twPluginModulePeer', $order, $con);
	}
}

<?php

/**
 * Subclass for representing a row from the 'tw_plugin_status' table.
 *
 *
 *
 * @package plugins.twCorePlugin.lib.model
 */
class PlugintwPluginStatus extends BasetwPluginStatus
{
	public function __toString()
	{
		return $this->getName();
	}
}

<?php

class twCoreUpgradeTask extends sfBaseTask
{
	const VERSION = 3;

	protected function configure() {
		$this->addArguments(array(
			new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
		));

		$this->addOptions(array(
			new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
			new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'))
		);

		$this->namespace = 'twcore';
		$this->name = 'upgrade';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
The [twcore:upgrade|INFO] task upgrades twCorePlugin to the newest version.
Call it with:
  [php symfony twcore:upgrade admin|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array()) {
		// initialize the database connection
		$databaseManager = new sfDatabaseManager($this->configuration);
		$connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

		$query = 'SELECT value FROM %s WHERE %s = :name';
		$query = sprintf($query, twVersionPeer::TABLE_NAME, twVersionPeer::NAME);
		$statement = $connection->prepare($query);
		$statement->bindValue(':name', 'db.core.base', PDO::PARAM_STR);
		$statement->execute();
		$resultset = $statement->fetch(PDO::FETCH_ASSOC);
		$statement->closeCursor();
		$version = !empty($resultset['value']) ? $resultset['value'] : 2;
		if ($version < 2) {
			$version = 2;
		}

		if ($version == self::VERSION) {
			$this->logSection('asset', 'System allredy in version: '.self::VERSION, null, 'INFO');
			return;
		}

		$i = 0;
		while ($version != self::VERSION && $i < 10) {
			$version = $this->upgradePlugin($version, $connection);
			$query = "REPLACE INTO tw_version (name, value) VALUES ('db.core.base', :ver)";
			$statement = $connection->prepare($query);
			$statement->bindValue(':ver', $version, PDO::PARAM_INT);
			$statement->execute();
			$i++;
		}

		if ($version == self::VERSION) {
			$this->logSection('asset', 'Upgrade complete. New version is: '.self::VERSION, null, 'INFO');
		} else {
			$this->logSection('asset', 'Upgrade NOT! complete. Version: '.$version, null, 'INFO');
		}
		return;
	}


	protected function upgradePlugin($version, $connection) {
		$method = 'upgradeVersion'.$version;
		return $this->$method($connection);
	}

	protected function upgradeVersion2($connection) {
		$query = 'SELECT value FROM %s WHERE %s = :name';
		$query = sprintf($query, twVersionPeer::TABLE_NAME, twVersionPeer::NAME);
		$statement = $connection->prepare($query);
		$statement->bindValue(':name', 'db.core.basicCms', PDO::PARAM_STR);
		$statement->execute();
		$resultset = $statement->fetch(PDO::FETCH_ASSOC);
		$statement->closeCursor();
		$cms_version = !empty($resultset['value']) ? $resultset['value'] : null;
		if ($cms_version < 2 and !is_null($cms_version)) {
			$this->logSection('asset', 'First upgrade twBasicCmsPlugin!', null, 'INFO');
			exit;
		}

		$query = 'SELECT value FROM %s WHERE %s = :name';
		$query = sprintf($query, twVersionPeer::TABLE_NAME, twVersionPeer::NAME);
		$statement = $connection->prepare($query);
		$statement->bindValue(':name', 'db.core.newsletter', PDO::PARAM_STR);
		$statement->execute();
		$resultset = $statement->fetch(PDO::FETCH_ASSOC);
		$statement->closeCursor();
		$newsletter_version = !empty($resultset['value']) ? $resultset['value'] : null;

		// TODO: transakcje chyba nie działają sprawdzić
		$connection->beginTransaction();

		// Modify sf_guard_user_profile to use twAsset
		$query = 'ALTER TABLE `sf_guard_user_profile` DROP `photo` ';
		$statement = $connection->prepare($query);
		$statement->execute();
		$query = 'ALTER TABLE `sf_guard_user_profile` ADD `asset_id` INT NULL DEFAULT NULL AFTER `user_id`';
		$statement = $connection->prepare($query);
		$statement->execute();
		$query = 'ALTER TABLE `sf_guard_user_profile` ADD CONSTRAINT `sf_guard_user_profile_FK_2` FOREIGN KEY (`asset_id`) REFERENCES `tw_asset` (`id`) ON DELETE SET NULL;';
		$statement = $connection->prepare($query);
		$statement->execute();

		// Modify position system
		$query = 'SELECT MIN(pos) AS min FROM `tw_plugin`';
		$statement = $connection->prepare($query);
		$statement->execute();
		$tw_plugin_array = $statement->fetch();
		$statement->closeCursor();
		if ($tw_plugin_array['min'] == 0) {
			$query = 'UPDATE `tw_plugin` SET pos = pos + 1';
			$statement = $connection->prepare($query);
			$statement->execute();
		}

		// Delete obsolete section
		$c = new Criteria();
		$c->add(twPluginPeer::CODE, 'core.basic.webgen');
		$plugin = twPluginPeer::doSelectOne($c);
		if ($plugin instanceof twPlugin) {
			$plugin->delete($connection);
		}

		// Modify position system
		$query = 'SELECT id FROM `tw_plugin`';
		$statement = $connection->prepare($query);
		$statement->execute();
		$plugins = $statement->fetchAll();
		foreach ($plugins as $plugin) {
			$query = 'SELECT MIN(pos) AS min FROM `tw_plugin_module` WHERE plugin_id = :plugin_id';
			$statement = $connection->prepare($query);
			$statement->bindValue(':plugin_id', $plugin['id'], PDO::PARAM_INT);
			$statement->execute();
			$tw_plugin_module_array = $statement->fetch();
			$statement->closeCursor();
			if ($tw_plugin_module_array['min'] == 0) {
				$query = 'UPDATE `tw_plugin_module` SET pos = pos + 1 WHERE plugin_id = :plugin_id';
				$statement = $connection->prepare($query);
				$statement->bindValue(':plugin_id', $plugin['id'], PDO::PARAM_INT);
				$statement->execute();
			}
		}

		$query = 'ALTER TABLE `tw_plugin_module` CHANGE `code` `code` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ';
		$statement = $connection->prepare($query);
		$statement->execute();

		$criteria = new Criteria();
		$criteria->add(twPluginStatusPeer::CODE, 'activated');
		$status = twPluginStatusPeer::doSelectOne($criteria, $connection);

		$criteria = new Criteria();
		$criteria->add(twPluginPeer::CODE, 'core.admin');
		$plugin = twPluginPeer::doSelectOne($criteria, $connection);

		$plugin_module = new twPluginModule();
		$plugin_module->settwPluginStatus($status);
		$plugin_module->setPluginId($plugin->getId());
		$plugin_module->setCode('core.media.plugins');
		$plugin_module->setCredentials('admin');
		$plugin_module->setRoute('@tw_media');

		$plugin_module_i18n_en = new twPluginModuleI18n();
		$plugin_module_i18n_en->settwPluginModule($plugin_module);
		$plugin_module_i18n_en->setCulture('en');
		$plugin_module_i18n_en->setName('Media');

		$plugin_module_i18n_pl = new twPluginModuleI18n();
		$plugin_module_i18n_pl->settwPluginModule($plugin_module);
		$plugin_module_i18n_pl->setCulture('pl');
		$plugin_module_i18n_pl->setName('Pliki');

		$plugin_module->save($connection);

		$query = "UPDATE tw_plugin_module SET code = 'core.user.sfpermission' WHERE route = '@sf_guard_permission'";
		$statement = $connection->prepare($query);
		$statement->execute();

		$query = "UPDATE tw_plugin_module SET code = 'core.basic.cms.content' WHERE route = '@tw_basic_cms_content'";
		$statement = $connection->prepare($query);
		$statement->execute();

		$query = "UPDATE tw_plugin_module SET code = 'core.basic.cms.partial' WHERE route = '@tw_basic_cms_partial'";
		$statement = $connection->prepare($query);
		$statement->execute();


		if ($cms_version == 2) {
			$criteria = new Criteria();
			$criteria->add(twPluginPeer::CODE, 'core.basic.cms');
			$plugin = twPluginPeer::doSelectOne($criteria, $connection);

			$plugin_module = new twPluginModule();
			$plugin_module->settwPluginStatus($status);
			$plugin_module->setPluginId($plugin->getId());
			$plugin_module->setCode('core.basic.cms.template');
			$plugin_module->setCredentials('webdeveloper');
			$plugin_module->setRoute('@tw_basic_cms_template');

			$plugin_module_i18n_en = new twPluginModuleI18n();
			$plugin_module_i18n_en->settwPluginModule($plugin_module);
			$plugin_module_i18n_en->setCulture('en');
			$plugin_module_i18n_en->setName('Templates');

			$plugin_module_i18n_pl = new twPluginModuleI18n();
			$plugin_module_i18n_pl->settwPluginModule($plugin_module);
			$plugin_module_i18n_pl->setCulture('pl');
			$plugin_module_i18n_pl->setName('Szablony');

			$plugin_module->save($connection);
			$plugin_module->moveUp();

			$plugin_module = new twPluginModule();
			$plugin_module->settwPluginStatus($status);
			$plugin_module->setPluginId($plugin->getId());
			$plugin_module->setCode('core.basic.cms.settings');
			$plugin_module->setCredentials('admin');
			$plugin_module->setRoute('@tw_settings');

			$plugin_module_i18n_en = new twPluginModuleI18n();
			$plugin_module_i18n_en->settwPluginModule($plugin_module);
			$plugin_module_i18n_en->setCulture('en');
			$plugin_module_i18n_en->setName('Settings');

			$plugin_module_i18n_pl = new twPluginModuleI18n();
			$plugin_module_i18n_pl->settwPluginModule($plugin_module);
			$plugin_module_i18n_pl->setCulture('pl');
			$plugin_module_i18n_pl->setName('Ustawienia');

			$plugin_module->save($connection);
		}

		if ($newsletter_version == 1) {
			$criteria = new Criteria();
			$criteria->add(twPluginPeer::CODE, 'core.news');
			$plugin = twPluginPeer::doSelectOne($criteria, $connection);

			$plugin_module = new twPluginModule();
			$plugin_module->settwPluginStatus($status);
			$plugin_module->setPluginId($plugin->getId());
			$plugin_module->setCode('core.news.settings');
			$plugin_module->setCredentials('admin');
			$plugin_module->setRoute('@tw_settings');

			$plugin_module_i18n_en = new twPluginModuleI18n();
			$plugin_module_i18n_en->settwPluginModule($plugin_module);
			$plugin_module_i18n_en->setCulture('en');
			$plugin_module_i18n_en->setName('Settings');

			$plugin_module_i18n_pl = new twPluginModuleI18n();
			$plugin_module_i18n_pl->settwPluginModule($plugin_module);
			$plugin_module_i18n_pl->setCulture('pl');
			$plugin_module_i18n_pl->setName('Ustawienia');

			$plugin_module->save($connection);
		}

		$connection->commit();
		return 3;
	}
}

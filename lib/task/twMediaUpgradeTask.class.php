<?php

class twMediaUpgradeTask extends sfBaseTask
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

		$this->namespace = 'twmedia';
		$this->name = 'upgrade';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
The [twcore:upgrade|INFO] task upgrades twCorePlugin [MEDIA] to the newest version.
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
		$statement->bindValue(':name', 'db.core.media', PDO::PARAM_STR);
		$statement->execute();
		$resultset = $statement->fetch(PDO::FETCH_ASSOC);
		$statement->closeCursor();
		$version = !empty($resultset['value']) ? $resultset['value'] : 2;
		if ($version < 2) {
			$version = 2;
		}

		if ($version == self::VERSION) {
			$this->logSection('media', 'System allredy in version: '.self::VERSION, null, 'INFO');
			return;
		}

		$i = 0;
		while ($version != self::VERSION && $i < 10) {
			$version = $this->upgradePlugin($version, $connection);
			$query = "REPLACE INTO tw_version (name, value) VALUES ('db.core.media', :ver)";
			$statement = $connection->prepare($query);
			$statement->bindValue(':ver', $version, PDO::PARAM_INT);
			$statement->execute();
			$i++;
		}

		if ($version == self::VERSION) {
			$this->logSection('media', 'Upgrade complete. New version is: '.self::VERSION, null, 'INFO');
		} else {
			$this->logSection('media', 'Upgrade NOT! complete. Version: '.$version, null, 'INFO');
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
		$statement->bindValue(':name', 'db.core.base', PDO::PARAM_STR);
		$statement->execute();
		$resultset = $statement->fetch(PDO::FETCH_ASSOC);
		$statement->closeCursor();
		$cms_version = !empty($resultset['value']) ? $resultset['value'] : null;
		if ($cms_version < 4 and !is_null($cms_version)) {
			$this->logSection('media', 'First upgrade twCorePlugin!', null, 'INFO');
			exit;
		}
		
		// TODO: transakcje chyba nie działają sprawdzić
		$connection->beginTransaction();
		
		$query = 'RENAME TABLE `tw_asset`  TO `sf_asset`';
		$statement = $connection->prepare($query);
		$statement->execute();

		$query = 'RENAME TABLE `tw_asset_folder`  TO `sf_asset_folder`';
		$statement = $connection->prepare($query);
		$statement->execute();

		$connection->commit();
		return 3;
	}
}

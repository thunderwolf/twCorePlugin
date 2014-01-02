<?php

class twCoreUpgradeTask extends sfBaseTask
{
    const VERSION = 5;

    protected function configure()
    {
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

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
        try {
            $tw_version = twVersionQuery::create()->findOneByName('db.core.base');
        } catch (Exception $e) {
            $this->installPlugin($connection);
            $this->logSection('core', 'Install complete. Your version is: ' . self::VERSION, null, 'INFO');
            return true;
        }

        $version = !is_null($tw_version) ? $tw_version->getValue() : $this->setActualVersion($connection);
        if ($version < 4) {
            throw new sfCommandException(sprintf('This %d twcore version is to old for upgrade by twCorePlugin v0.4.x. Minimum version 4 of database is required', $version));
        }

        if ($version == self::VERSION) {
            $this->logSection('core', 'System allredy in version: ' . self::VERSION, null, 'INFO');
            return true;
        }

        $i = 0;
        while ($version != self::VERSION && $i < 10) {
            $method = 'upgradeVersion' . $version;
            $version = $this->$method($connection);
            $tw_version->setValue($version);
            $tw_version->save($connection);
            $i++;
        }

        if ($version == self::VERSION) {
            $this->logSection('core', 'Upgrade complete. New version is: ' . self::VERSION, null, 'INFO');
        } else {
            $this->logSection('core', 'Upgrade NOT! complete. Version: ' . $version, null, 'INFO');
        }
        return true;
    }

    protected function upgradeVersion4(PropelPDO $connection)
    {
        $connection->exec('SET FOREIGN_KEY_CHECKS = 0;');

        $connection->exec('ALTER TABLE `tw_routing` DROP FOREIGN KEY `tw_routing_FK_1` ;');

        $connection->exec('ALTER TABLE `tw_routing` DROP INDEX `tw_routing_FI_1`');

        $connection->exec('ALTER TABLE `tw_routing` DROP `plugin_id`');

        $connection->exec('
			ALTER TABLE `tw_routing` ADD `module` VARCHAR( 250 ) NOT NULL AFTER `name` ,
			ADD INDEX ( `module` )
		');
        $tw_version = new twVersion();
        $tw_version->setName('db.core.routing');
        $tw_version->setValue(1);
        $tw_version->save($connection);

        $connection->exec('DROP TABLE IF EXISTS `tw_plugin_i18n`;');

        $connection->exec('DROP TABLE IF EXISTS `tw_plugin`;');

        $connection->exec('DROP TABLE IF EXISTS `tw_plugin_status_i18n`;');

        $connection->exec('DROP TABLE IF EXISTS `tw_plugin_status`;');

        $connection->exec('DROP TABLE IF EXISTS `tw_language`;');

        $connection->exec('DROP TABLE IF EXISTS `tw_settings`;');

        $connection->exec('SET FOREIGN_KEY_CHECKS = 1;');
        return 5;
    }

    protected function installPlugin(PropelPDO $connection)
    {
        $connection->exec('SET FOREIGN_KEY_CHECKS = 0;');

        $connection->exec('DROP TABLE IF EXISTS `tw_version`;');

        $connection->exec('
			CREATE TABLE `tw_version`
			(
				`id` INTEGER  NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(20)  NOT NULL,
				`value` TEXT  NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `code` (`name`)
			) ENGINE=InnoDB;
		');

        $connection->exec('SET FOREIGN_KEY_CHECKS = 1;');

        $this->setActualVersion($connection);
    }

    protected function setActualVersion(PropelPDO $connection)
    {
        $tw_version = new twVersion();
        $tw_version->setName('db.core.base');
        $tw_version->setValue(self::VERSION);
        $tw_version->save($connection);

        return self::VERSION;
    }
}

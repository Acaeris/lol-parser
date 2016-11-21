CREATE TABLE IF NOT EXISTS `version` (
  `fullVersion` varchar(16) NOT NULL,
  `season` int(11) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `hotfix` int(11) DEFAULT NULL,
  PRIMARY KEY (`fullVersion`),
  KEY `version` (`season`,`version`,`hotfix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `champion` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `attackRange` float DEFAULT NULL,
  `moveSpeed` float DEFAULT NULL,
  `hp` float DEFAULT NULL,
  `hpPerLevel` float DEFAULT NULL,
  `hpRegen` float DEFAULT NULL,
  `hpRegenPerLevel` float DEFAULT NULL,
  `resourceType` varchar(20) DEFAULT NULL,
  `mp` float DEFAULT NULL,
  `mpPerLevel` float DEFAULT NULL,
  `mpRegen` float DEFAULT NULL,
  `mpRegenPerLevel` float DEFAULT NULL,
  `attackDamage` float DEFAULT NULL,
  `attackDamagePerLevel` float DEFAULT NULL,
  `attackSpeedOffset` float DEFAULT NULL,
  `attackSpeedPerLevel` float DEFAULT NULL,
  `crit` float DEFAULT NULL,
  `critPerLevel` float DEFAULT NULL,
  `armor` float DEFAULT NULL,
  `armorPerLevel` float DEFAULT NULL,
  `spellBlock` float DEFAULT NULL,
  `spellBlockPerLevel` float DEFAULT NULL,
  `version` varchar(8) NOT NULL,
  PRIMARY KEY (`id`,`version`),
  KEY `champName` (`name`),
  KEY `champVersion` (`version`),
  CONSTRAINT `champVersion` FOREIGN KEY (`version`) REFERENCES `version` (`fullVersion`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `description` mediumtext NULL,
  `image` varchar(45) NULL,
  `version` varchar(8) NOT NULL,
  PRIMARY KEY (`id`, `version`),
  KEY `itemName` (`name`),
  KEY `itemVersion` (`version`),
  CONSTRAINT `itemVersion` FOREIGN KEY (`version`) REFERENCES `version` (`fullVersion`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
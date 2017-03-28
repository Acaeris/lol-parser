CREATE TABLE IF NOT EXISTS realms (
    cdn varchar(128) NOT NULL,
    version varchar(16) NOT NULL,
    region varchar(8) NOT NULL,
    PRIMARY KEY(region, version)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS versions (
  fullVersion varchar(16) NOT NULL,
  season int(11) DEFAULT NULL,
  version int(11) DEFAULT NULL,
  hotfix int(11) DEFAULT NULL,
  PRIMARY KEY (fullVersion),
  KEY version (season,version,hotfix)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS champions (
  champion_id int(11) NOT NULL,
  champion_name varchar(45) DEFAULT NULL,
  title varchar(45) DEFAULT NULL,
  tags varchar(45) DEFAULT NULL,
  resourceType varchar(20) DEFAULT NULL,
  version varchar(16) NOT NULL,
  PRIMARY KEY (champion_id, version),
  KEY champ_name (champion_name),
  KEY champ_version (version)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS champion_stats (
  champion_id int(11) NOT NULL,
  stat_name varchar(45) NOT NULL,
  stat_value float DEFAULT NULL,
  version varchar(16) NOT NULL,
  PRIMARY KEY (champion_id, version, stat_name),
  KEY stat_names (stat_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS items (
  item_id int(11) NOT NULL,
  item_name varchar(45) DEFAULT NULL,
  description mediumtext DEFAULT NULL,
  purchaseValue int(8) DEFAULT NULL,
  saleValue int(8) DEFAULT NULL,
  image varchar(45) DEFAULT NULL,
  version varchar(16) NOT NULL,
  PRIMARY KEY (item_id, version),
  KEY itemName (item_name),
  KEY itemVersion (version)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS item_stats (
  item_id int(11) NOT NULL,
  stat_name varchar(45) NOT NULL,
  stat_value float DEFAULT NULL,
  version varchar(16) NOT NULL,
  PRIMARY KEY (item_id, version, stat_name),
  KEY stat_names (stat_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
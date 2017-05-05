CREATE TABLE IF NOT EXISTS realms (
    PRIMARY KEY(version),
    cdn     varchar(128) NOT NULL,
    version varchar(16)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS versions (
  PRIMARY KEY (full_version),
          KEY version (season,version,hotfix),
  full_version varchar(16) NOT NULL,
  season       int         DEFAULT NULL,
  version      int         DEFAULT NULL,
  hotfix       int         DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS champions (
  PRIMARY KEY (champion_id, version),
          KEY champ_names (champion_name),
          KEY champ_versions (version),
  champion_id   int         NOT NULL,
  champion_name varchar(45) DEFAULT NULL,
  title         varchar(45) DEFAULT NULL,
  tags          varchar(45) DEFAULT NULL,
  resource_type varchar(20) DEFAULT NULL,
  image_name    varchar(45) DEFAULT NULL,
  version       varchar(16) NOT NULL,
  region        varchar(8)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS champion_stats (
  PRIMARY KEY (champion_id, version, stat_name),
          KEY stat_names (stat_name),
  champion_id int         NOT NULL,
  stat_name   varchar(45) NOT NULL,
  stat_value  float       DEFAULT NULL,
  version     varchar(16) NOT NULL,
  region      varchar(8)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS items (
  PRIMARY KEY (item_id, version),
          KEY item_names (item_name),
          KEY item_versions (version),
  item_id        int         NOT NULL,
  item_name      varchar(45) DEFAULT NULL,
  description    mediumtext  DEFAULT NULL,
  purchase_value int         DEFAULT NULL,
  sale_value     int         DEFAULT NULL,
  image          varchar(45) DEFAULT NULL,
  version        varchar(16) NOT NULL,
  region         varchar(8)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS item_stats (
  PRIMARY KEY (item_id, version, stat_name),
          KEY stat_names (stat_name),
  item_id    int         NOT NULL,
  stat_name  varchar(45) NOT NULL,
  stat_value float       DEFAULT NULL,
  version    varchar(16) NOT NULL,
  region     varchar(8)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
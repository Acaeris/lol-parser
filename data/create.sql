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
  champion_name varchar(45) NOT NULL,
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

CREATE TABLE IF NOT EXISTS champion_spells (
  PRIMARY KEY (champion_id, version, spell_name),
          KEY spell_names (spell_name),
  champion_id int         NOT NULL,
  spell_name  varchar(45) NOT NULL,
  spell_key   varchar(1)  NOT NULL,
  image_name  varchar(45) NOT NULL,
  max_rank    int         NOT NULL,
  description mediumtext  DEFAULT NULL,
  tooltip     mediumtext  DEFAULT NULL,
  cooldowns   json        DEFAULT NULL,
  ranges      json        DEFAULT NULL,
  effects     json        DEFAULT NULL,
  variables   json        DEFAULT NULL,
  resource    json        DEFAULT NULL,
  version     varchar(16) NOT NULL,
  region      varchar(8)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS champion_passives (
  PRIMARY KEY (champion_id, version, passive_name),
          KEY passive_names (passive_name),
  champion_id  int         NOT NULL,
  passive_name varchar(45) NOT NULL,
  image_name   varchar(45) NOT NULL,
  description  mediumtext  DEFAULT NULL,
  version      varchar(16) NOT NULL,
  region       varchar(8)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS items (
  PRIMARY KEY (item_id, version),
          KEY item_names (item_name),
          KEY item_versions (version),
  item_id        int         NOT NULL,
  item_name      varchar(45) NOT NULL,
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

CREATE TABLE IF NOT EXISTS runes (
  PRIMARY KEY (rune_id, version),
          KEY rune_names (rune_name),
          KEY rune_versions (version),
  rune_id     int         NOT NULL,
  rune_name   varchar(60) NOT NULL,
  description mediumtext  DEFAULT NULL,
  image_name  varchar(45) DEFAULT NULL,
  tags        varchar(45) DEFAULT NULL,
  version     varchar(16) NOT NULL,
  region      varchar(8)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rune_stats (
  PRIMARY KEY (rune_id, version, stat_name),
          KEY stat_names (stat_name),
  rune_id    int         NOT NULL,
  stat_name  varchar(45) NOT NULL,
  stat_value float       DEFAULT NULL,
  version    varchar(16) NOT NULL,
  region     varchar(8)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS masteries (
  PRIMARY KEY (mastery_id, version),
          KEY mastery_names (mastery_name),
          KEY mastery_versions (version),
  mastery_id   int         NOT NULL,
  mastery_name varchar(60) NOT NULL,
  description  mediumtext  DEFAULT NULL,
  ranks        int         NOT NULL,
  image_name   varchar(45) DEFAULT NULL,
  mastery_tree varchar(45) DEFAULT NULL,
  version      varchar(16) NOT NULL,
  region       varchar(8)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

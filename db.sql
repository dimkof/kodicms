CREATE TABLE `datasources` (
  `ds_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ds_type` varchar(64) NOT NULL,
  `docs` int(6) unsigned NOT NULL DEFAULT '0',
  `indexed` int(1) NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `internal` int(1) unsigned DEFAULT '0',
  `locks` int(3) unsigned NOT NULL DEFAULT '0',
  `code` text,
  PRIMARY KEY (`ds_id`),
  KEY `intl` (`internal`),
  KEY `ds_type` (`ds_type`,`internal`),
  KEY `docs` (`docs`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dshfields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ds_id` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL DEFAULT '',
  `family` varchar(32) NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT '',
  `header` varchar(255) NOT NULL DEFAULT '',
  `from_ds` int(11) unsigned DEFAULT '0',
  `props` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ds_id` (`ds_id`),
  KEY `family` (`family`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `dshybrid` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ds_id` int(11) unsigned NOT NULL DEFAULT '0',
  `published` int(1) unsigned DEFAULT '0',
  `header` varchar(255) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `ds_id` (`ds_id`,`published`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `hybriddatasources` (
  `ds_id` int(11) unsigned NOT NULL DEFAULT '0',
  `parent` int(11) unsigned NOT NULL DEFAULT '0',
  `ds_key` varchar(128) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`ds_id`),
  UNIQUE KEY `ds_key` (`ds_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `objects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ds_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ds_type` varchar(32) NOT NULL DEFAULT '',
  `obj_type` varchar(32) NOT NULL DEFAULT '',
  `tpl` int(10) unsigned DEFAULT NULL,
  `name` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  `code` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `siteobjects` (
  `page_id` int(10) unsigned NOT NULL DEFAULT '0',
  `obj_id` int(10) unsigned NOT NULL DEFAULT '0',
  `block` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`page_id`,`obj_id`),
  KEY `page_block` (`page_id`,`block`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
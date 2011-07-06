CREATE TABLE IF NOT EXISTS `albums` (
  `index` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  KEY `index` (`index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `config` (
  `index` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  `pretty_name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `type` varchar(255) NOT NULL default '',
  `category` int(11) NOT NULL default '0',
  `changeable` char(2) NOT NULL default '',
  PRIMARY KEY  (`index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `config_category` (
  `index` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  KEY `index` (`index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `events` (
  `index` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `venue` varchar(255) NOT NULL default '',
  `where` varchar(255) NOT NULL default '',
  `when` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  KEY `index` (`index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `gallery` (
  `index` int(8) NOT NULL auto_increment,
  `filename` varchar(255) NOT NULL default '',
  `height` int(8) NOT NULL default '0',
  KEY `index` (`index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `links` (
  `index` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `linkurl` varchar(255) NOT NULL default '',
  `category` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  KEY `index` (`index`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `links_category` (
  `index` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `news` (
  `index` int(8) NOT NULL auto_increment,
  `topic` varchar(255) NOT NULL default '',
  `title` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `body` text NOT NULL,
  `timestamp` int(11) NOT NULL default '0',
  KEY `index` (`index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `pages` (
  `index` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `menu_title` varchar(255) NOT NULL default '',
  `body` mediumtext NOT NULL,
  `url` varchar(255) NOT NULL default '',
  `type` varchar(4) NOT NULL default '',
  `hide` char(2) NOT NULL default '',
  UNIQUE KEY `menu_title` (`menu_title`),
  KEY `index` (`index`),
  FULLTEXT KEY `body` (`body`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `topics` (
  `index` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tracks` (
  `index` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `album` int(8) NOT NULL default '0',
  `track` int(4) NOT NULL default '0',
  `lyrics` text NOT NULL,
  KEY `index` (`index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users` (
  `index` int(8) NOT NULL auto_increment,
  `username` varchar(128) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `type` varchar(4) NOT NULL default '',
  UNIQUE KEY `username` (`username`),
  KEY `index` (`index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

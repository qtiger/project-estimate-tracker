 CREATE DATABASE `pet` /*!40100 DEFAULT CHARACTER SET latin1 */;

DROP TABLE IF EXISTS `pet`.`holiday`;
CREATE TABLE  `pet`.`holiday` (
  `HolidayID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `DeveloperID` int(10) unsigned NOT NULL,
  `StartDate` datetime NOT NULL,
  `EndDate` datetime NOT NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`HolidayID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pet`.`project`;
CREATE TABLE  `pet`.`project` (
  `ProjectID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ProjectName` varchar(45) NOT NULL,
  `Live` char(1) NOT NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ProjectID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pet`.`status`;
CREATE TABLE  `pet`.`status` (
  `StatusID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Status` varchar(12) NOT NULL,
  PRIMARY KEY (`StatusID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pet`.`task`;
CREATE TABLE  `pet`.`task` (
  `TaskID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ProjectID` int(10) unsigned NOT NULL,
  `TaskName` varchar(45) NOT NULL,
  `DeveloperID` int(10) unsigned NOT NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Tracked` char(1) NOT NULL DEFAULT 'Y',
  `Minutes` int(10) unsigned NOT NULL,
  PRIMARY KEY (`TaskID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pet`.`taskcompletion`;
CREATE TABLE  `pet`.`taskcompletion` (
  `CompletionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CompletionDate` datetime NOT NULL,
  `StatusID` int(10) unsigned NOT NULL,
  `Comment` varchar(100) NOT NULL,
  `TaskID` int(10) unsigned NOT NULL,
  `CommenceDate` datetime NOT NULL,
  `CreatedDate` datetime NOT NULL,
  PRIMARY KEY (`CompletionID`),
  KEY `tcdate` (`CompletionDate`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pet`.`users`;
CREATE TABLE  `pet`.`users` (
  `UserID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserName` varchar(3) NOT NULL,
  `Name` varchar(10) NOT NULL,
  `Team` varchar(20) NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pet`.`time`;
CREATE TABLE  `pet`.`time` (
  `timeid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `taskid` int(10) unsigned NOT NULL,
  `developerid` int(10) unsigned NOT NULL,
  `minutes` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `starttime` datetime NOT NULL,
  `description` varchar(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`timeid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `pet`.`users` (`UserID`,`UserName`,`Name`) VALUES
  (0,'zzz','General');

UPDATE `pet`.`users` set `UserID` = 0 where `UserID` = 1;

INSERT INTO `pet`.`status` (`StatusID`,`Status`) VALUES 
 (1,'Not started'),
 (2,'In Progress'),
 (3,'On Hold'),
 (4,'Blocked'),
 (5,'Completed');
RCSA Rooms Database
===================

This site holds various statistics on all rooms in college, as well as allocations since 2012, and a projector-suitable page with which rooms are currently available.

It's designed to run on an SRCF web server, but should be easily deployed elsewhere too.


Database setup
--------------

Run this SQL to create the required tables.  If you add prefixes to table names (such as `rooms__allocations`), you can set a table prefix in **conn.php** using `$db->setPrefix("rooms__")`.

```sql
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room` (`room`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `desc` varchar(2000) NOT NULL,
  `location` enum('Herschel Court','Front Court','Long Court','High Court','Adams Road','Sylvester Road') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `desc` varchar(4000) NOT NULL,
  `type` enum('Value','Standard','Standard Plus','Best','Unavailable') NOT NULL,
  `special` enum('None','Disability Accessible','Disability Carer','Piano','Fellow''s Room','Guest Room','Function Room') NOT NULL,
  `floor` enum('B','G','1','2','3','4') NOT NULL,
  `bathroom` enum('Ensuite','Between 2','Between 3') DEFAULT NULL,
  `sink` tinyint(1) DEFAULT NULL,
  `storage` enum('None','Small','Large') DEFAULT NULL,
  `wifi` enum('None','Weak','Average','Strong') DEFAULT NULL,
  `sockets` enum('None','Few','Some','Lots') DEFAULT NULL,
  `view` enum('College','Gardens','Roadside') DEFAULT NULL,
  `facing` enum('N','NE','E','SE','S','SW','W','NW') DEFAULT NULL,
  `balcony` enum('None','Open','Between 2','Private') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group` (`group`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


ALTER TABLE `allocations`
  ADD CONSTRAINT `allocations_ibfk_1` FOREIGN KEY (`room`) REFERENCES `rooms` (`id`);

ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`group`) REFERENCES `groups` (`id`);
```

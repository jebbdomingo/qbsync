# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: nucleonplus-db-instance.cjitsulcoyi6.us-west-2.rds.amazonaws.com (MySQL 5.6.27-log)
# Database: nucleonplus
# Generation Time: 2017-04-09 10:06:08 +0000
# ************************************************************


# Dump of table #__qbsync_items
# ------------------------------------------------------------

CREATE TABLE `#__qbsync_items` (
  `qbsync_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `ItemRef` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `fulltext` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `Active` tinyint(4) NOT NULL,
  `Taxable` tinyint(4) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL,
  `PurchaseCost` decimal(10,2) NOT NULL,
  `QtyOnHand` int(11) NOT NULL,
  `quantity_purchased` int(11) NOT NULL,
  `charges` decimal(10,2) NOT NULL,
  `rebates` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `drpv` decimal(10,2) NOT NULL,
  `irpv` decimal(10,2) NOT NULL,
  `stockist` decimal(10,2) NOT NULL,
  `weight` int(11) NOT NULL COMMENT 'Grams',
  `image` varchar(255) NOT NULL,
  `shipping_type` varchar(50) NOT NULL,
  `last_synced_on` datetime NOT NULL,
  `last_synced_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_on` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`qbsync_item_id`),
  UNIQUE KEY `ItemRef` (`ItemRef`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

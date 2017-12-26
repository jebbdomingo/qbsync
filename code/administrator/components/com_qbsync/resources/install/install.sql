
# Dump of table #__qbsync_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `#__qbsync_items`;

CREATE TABLE `#__qbsync_items` (
  `qbsync_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `app` varchar(255) DEFAULT NULL,
  `app_entity` varchar(255) DEFAULT NULL,
  `ItemRef` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `fulltext` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT '',
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
  PRIMARY KEY (`qbsync_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Dump of table #__qbsync_configs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `#__qbsync_configs`;

CREATE TABLE `#__qbsync_configs` (
  `qbsync_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(255) NOT NULL COMMENT 'Configuration item',
  `value` longtext NOT NULL COMMENT 'Configuration item value',
  PRIMARY KEY (`qbsync_config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `#__qbsync_configs` WRITE;
/*!40000 ALTER TABLE `#__qbsync_configs` DISABLE KEYS */;

INSERT INTO `#__qbsync_configs` (`qbsync_config_id`, `item`, `value`)
VALUES
  (1,'qbo_local','{}'),
  (2,'qbo_staging','{}'),
  (3,'qbo_production','{}');

/*!40000 ALTER TABLE `#__qbsync_configs` ENABLE KEYS */;
UNLOCK TABLES;

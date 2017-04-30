# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: nucleonplus-db-instance.cjitsulcoyi6.us-west-2.rds.amazonaws.com (MySQL 5.6.27-log)
# Database: nucleonplus
# Generation Time: 2017-04-09 10:08:55 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table nuc_qbsync_itemgroups
# ------------------------------------------------------------

LOCK TABLES `nuc_qbsync_itemgroups` WRITE;
/*!40000 ALTER TABLE `nuc_qbsync_itemgroups` DISABLE KEYS */;

INSERT INTO `nuc_qbsync_itemgroups` (`qbsync_itemgroup_id`, `parent_id`, `ItemRef`, `quantity`)
VALUES
	(125,25,3,6),
	(126,25,24,1);

/*!40000 ALTER TABLE `nuc_qbsync_itemgroups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nuc_qbsync_items
# ------------------------------------------------------------

LOCK TABLES `nuc_qbsync_items` WRITE;
/*!40000 ALTER TABLE `nuc_qbsync_items` DISABLE KEYS */;

INSERT INTO `nuc_qbsync_items` (`qbsync_item_id`, `ItemRef`, `Name`, `Description`, `Active`, `Taxable`, `Type`, `UnitPrice`, `PurchaseCost`, `QtyOnHand`, `quantity_purchased`, `slots`, `charges`, `rebates`, `cost`, `profit`, `prpv`, `drpv`, `irpv`, `weight`, `image`, `shipping_type`, `last_synced_on`, `last_synced_by`, `fulltext`)
VALUES
	(4,25,'TUM Business Package','6 Boxes of TUM 7-in-1 Chocolate Drink | Commission | PHP 30.00 Rebates',0,0,'Group',1030.00,0.00,0,0,1,30.00,30.00,600.00,90.00,100.00,90.00,4.50,1440,'tum.jpg','','0000-00-00 00:00:00',0,'<p><strong>TUM 7 in 1 Choco Drink</strong></p>\r\n\r\n<ol>\r\n	<li>Non-dairy Creamer</li>\r\n	<li>Native Cacao</li>\r\n	<li>Agarics Mushroom</li>\r\n	<li>Mangosteen</li>\r\n	<li>Stevia</li>\r\n	<li>Lemongrass</li>\r\n	<li>Malunggay &nbsp;</li>\r\n</ol>\r\n\r\n<p><strong>7 Reasons why Tum</strong></p>\r\n\r\n<ol>\r\n	<li>\r\n	<p>Stress and Tension. Because of the effects of Cacao and Malunggay. Malunggay from bark or leaves is used to treat insomnia and restlessness (<a href=\"http://www.medicalhealthguide.com\" target=\"_blank\">www.medicalhealthguide.com</a>)</p>\r\n	</li>\r\n	<li>\r\n	<p>Insomnia. Tum is hot drink and with matching herbs, it can contribute to sleepiness.</p>\r\n	</li>\r\n	<li>\r\n	<p>Constipation. Hot Tum with herbs can stimulate intestinal peristalsis.</p>\r\n	</li>\r\n	<li>\r\n	<p>Diabetes. 1 sachet of Tum a day is the ideal prescription for diabetes. Stevia is a sweetener for diabetic (Herbs and Spices. Ruiz &amp; Claudio)</p>\r\n	</li>\r\n	<li>\r\n	<p>Immune System. Herbs mixed with native cacao complement a high antioxidants properties.</p>\r\n	</li>\r\n	<li>\r\n	<p>Junk Food. Tum is not a junk food due to its herbal mixture.</p>\r\n	</li>\r\n	<li>Enjoyment. Tum taste so delicious. It is formulated for real enjoyment.</li>\r\n</ol>'),
	(5,24,'[CHARGE] TUM Business Package Charge','Business charge for TUM Package',0,0,'Service',70.00,0.00,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0,'','','0000-00-00 00:00:00',0,''),
	(6,3,'TUM 7-in-1 Chocolate Drink','TUM 7-in-1 Chocolate Drink | PHP 5.00 Rebates',0,0,'Inventory',160.00,100.00,569,87,1,5.00,5.00,100.00,15.00,0.00,15.00,1.00,240,'tum.jpg','xend','0000-00-00 00:00:00',0,'<p><strong>TUM 7 in 1 Choco Drink</strong></p>\r\n\r\n<ol>\r\n	<li>Non-dairy Creamer</li>\r\n	<li>Native Cacao</li>\r\n	<li>Agarics Mushroom</li>\r\n	<li>Mangosteen</li>\r\n	<li>Stevia</li>\r\n	<li>Lemongrass</li>\r\n	<li>Malunggay &nbsp;</li>\r\n</ol>\r\n\r\n<p><strong>7 Reasons why Tum</strong></p>\r\n\r\n<ol>\r\n	<li>\r\n	<p>Stress and Tension. Because of the effects of Cacao and Malunggay. Malunggay from bark or leaves is used to treat insomnia and restlessness (<a href=\"http://www.medicalhealthguide.com\" target=\"_blank\">www.medicalhealthguide.com</a>)</p>\r\n	</li>\r\n	<li>\r\n	<p>Insomnia. Tum is hot drink and with matching herbs, it can contribute to sleepiness.</p>\r\n	</li>\r\n	<li>\r\n	<p>Constipation. Hot Tum with herbs can stimulate intestinal peristalsis.</p>\r\n	</li>\r\n	<li>\r\n	<p>Diabetes. 1 sachet of Tum a day is the ideal prescription for diabetes. Stevia is a sweetener for diabetic (Herbs and Spices. Ruiz &amp; Claudio)</p>\r\n	</li>\r\n	<li>\r\n	<p>Immune System. Herbs mixed with native cacao complement a high antioxidants properties.</p>\r\n	</li>\r\n	<li>\r\n	<p>Junk Food. Tum is not a junk food due to its herbal mixture.</p>\r\n	</li>\r\n	<li>Enjoyment. Tum taste so delicious. It is formulated for real enjoyment.</li>\r\n</ol>'),
	(13,27,'Health & Home','Health & Home Magazine | PHP 5.00 Rebates',0,0,'Inventory',162.00,102.00,50,2,1,5.00,5.00,102.00,15.00,0.00,15.00,1.00,100,'hh.jpg','phlpost','0000-00-00 00:00:00',0,'');

/*!40000 ALTER TABLE `nuc_qbsync_items` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

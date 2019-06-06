CREATE TABLE IF NOT EXISTS `#__mdimpressum_pro` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`firma` VARCHAR(255)  NOT NULL ,
`vrnummer` VARCHAR(255)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`vorname` VARCHAR(255)  NOT NULL ,
`str1` VARCHAR(255)  NOT NULL ,
`plz1` VARCHAR(255)  NOT NULL ,
`ort1` VARCHAR(255)  NOT NULL ,
`vnamen` TEXT NOT NULL ,
`tname` VARCHAR(255)  NOT NULL ,
`tvorname` VARCHAR(255)  NOT NULL ,
`inname` VARCHAR(255)  NOT NULL ,
`invorname` VARCHAR(255)  NOT NULL ,
`bankinhaber` VARCHAR(255)  NOT NULL ,
`bankname` VARCHAR(255)  NOT NULL ,
`bankiban` VARCHAR(255)  NOT NULL ,
`bankswift` VARCHAR(255)  NOT NULL ,
`templname` VARCHAR(255)  NOT NULL ,
`templersteller` VARCHAR(255)  NOT NULL ,
`templwebsite` VARCHAR(255)  NOT NULL ,
`copyfooter` VARCHAR(255)  NOT NULL ,
`werbungfooter` VARCHAR(255)  NOT NULL ,
`frei1` VARCHAR(255)  NOT NULL ,
`frei2` VARCHAR(255)  NOT NULL ,
`frei3` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__mdimpressum_pro_dashboard` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__mdimpressum_pro_law` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`rechte` VARCHAR(255)  NOT NULL ,
`haftung` TEXT NOT NULL ,
`urheberrecht` TEXT NOT NULL ,
`bildquellen` TEXT NOT NULL ,
`bildrechte` TEXT NOT NULL ,
`sonstige` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__mdimpressum_pro_daten` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`uberschrift` VARCHAR(255)  NOT NULL ,
`datenschutz` TEXT NOT NULL ,
`amazon` TEXT NOT NULL ,
`etracker` TEXT NOT NULL ,
`facebook` TEXT NOT NULL ,
`google_adsense` TEXT NOT NULL ,
`google_analytics` TEXT NOT NULL ,
`googleplus` TEXT NOT NULL ,
`googleanalyticsremark` TEXT NOT NULL ,
`instagram` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__mdimpressum_pro_daten2` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`uberschrift2` VARCHAR(255)  NOT NULL ,
`linkedin` TEXT NOT NULL ,
`pinterest` TEXT NOT NULL ,
`piwik` TEXT NOT NULL ,
`tumblr` TEXT NOT NULL ,
`twitter` TEXT NOT NULL ,
`xing` TEXT NOT NULL ,
`auslosperr` TEXT NOT NULL ,
`shop` TEXT NOT NULL ,
`datenubermi` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__mdimpressum_pro_daten3` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`uberschrift3` VARCHAR(255)  NOT NULL ,
`cookies` TEXT NOT NULL ,
`serverlog` TEXT NOT NULL ,
`kontform` TEXT NOT NULL ,
`werbemails` TEXT NOT NULL ,
`newsletterdaten` TEXT NOT NULL ,
`reg` TEXT NOT NULL ,
`verarbdaten` TEXT NOT NULL ,
`zusatz1` TEXT NOT NULL ,
`zusatz2` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'mdimpressum','com_mdimpressum_pro.mdimpressum','{"special":{"dbtable":"#__mdimpressum_pro","key":"id","type":"mdimpressum","prefix":"MD Impressum ProTable"}}', '{"formFile":"administrator\/components\/com_mdimpressum_pro\/models\/forms\/mdimpressum.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"vnamen2"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_mdimpressum_pro.mdimpressum')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'dashboard','com_mdimpressum_pro.','{"special":{"dbtable":"#__mdimpressum_pro_dashboard","key":"id","type":"dashboard","prefix":"MD Impressum ProTable"}}', '{"formFile":"administrator\/components\/com_mdimpressum_pro\/models\/forms\/.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_mdimpressum_pro.')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Law','com_mdimpressum_pro.law','{"special":{"dbtable":"#__mdimpressum_pro_law","key":"id","type":"Law","prefix":"MD Impressum ProTable"}}', '{"formFile":"administrator\/components\/com_mdimpressum_pro\/models\/forms\/law.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"sonstige"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_mdimpressum_pro.law')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Pro Datenschutz','com_mdimpressum_pro.prodatenschutz','{"special":{"dbtable":"#__mdimpressum_pro_daten","key":"id","type":"Pro Datenschutz","prefix":"MD Impressum ProTable"}}', '{"formFile":"administrator\/components\/com_mdimpressum_pro\/models\/forms\/prodatenschutz.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"instagram"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_mdimpressum_pro.prodatenschutz')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Pro Daten2','com_mdimpressum_pro.prodaten2','{"special":{"dbtable":"#__mdimpressum_pro_daten2","key":"id","type":"Pro Daten2","prefix":"MD Impressum ProTable"}}', '{"formFile":"administrator\/components\/com_mdimpressum_pro\/models\/forms\/prodaten2.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"datenubermi"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_mdimpressum_pro.prodaten2')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Pro Daten3','com_mdimpressum_pro.prodaten3','{"special":{"dbtable":"#__mdimpressum_pro_daten3","key":"id","type":"Pro Daten3","prefix":"MD Impressum ProTable"}}', '{"formFile":"administrator\/components\/com_mdimpressum_pro\/models\/forms\/prodaten3.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"zusatz2"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_mdimpressum_pro.prodaten3')
) LIMIT 1;

DROP TABLE IF EXISTS `#__mdimpressum_pro`;
DROP TABLE IF EXISTS `#__mdimpressum_pro_dashboard`;
DROP TABLE IF EXISTS `#__mdimpressum_pro_law`;
DROP TABLE IF EXISTS `#__mdimpressum_pro_daten`;
DROP TABLE IF EXISTS `#__mdimpressum_pro_daten2`;
DROP TABLE IF EXISTS `#__mdimpressum_pro_daten3`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_mdimpressum_pro.%');
ALTER TABLE `#__baforms_forms` ADD COLUMN `display_total` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `#__baforms_forms` ADD COLUMN `currency_code` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `currency_symbol` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `payment_methods` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `return_url` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `cancel_url` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `paypal_email` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `payment_environment` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `seller_id` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `skrill_email` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `webmoney_purse` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `payu_api_key` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `payu_merchant_id` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `payu_account_id` varchar(255) NOT NULL;
ALTER TABLE `#__baforms_forms` ADD COLUMN `check_ip` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `#__baforms_forms` ADD COLUMN `button_type` varchar(255) NOT NULL DEFAULT "button";
ALTER TABLE `#__baforms_forms` CHANGE `button_bg` `button_bg` VARCHAR(40);
ALTER TABLE `#__baforms_forms` CHANGE `button_color` `button_color` VARCHAR(40);
CREATE TABLE `#__baforms_reference` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `value` varchar(40) NOT NULL,
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
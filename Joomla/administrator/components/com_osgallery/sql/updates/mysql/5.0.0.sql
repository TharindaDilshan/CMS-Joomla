ALTER TABLE `#__os_gallery_img` ADD `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `ordering`;
ALTER TABLE `#__os_gallery_img` ADD `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `title`;
ALTER TABLE `#__os_gallery_img` ADD `upload_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `description`;
ALTER TABLE `#__os_gallery_img` ADD `publish` TINYINT NULL AFTER `description`;
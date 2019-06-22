SET FOREIGN_KEY_CHECKS = false;
DELETE FROM `admin_resources` WHERE `identifier` IN ('files', 'filecategories', 'filedownloads', 'usergroups_filecategories');
DELETE FROM `pages` WHERE `identifier` IN ('contact-success', 'contact-error');
DROP TABLE IF EXISTS `contact_forms`;
SET FOREIGN_KEY_CHECKS = true;

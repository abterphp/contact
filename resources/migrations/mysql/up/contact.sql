--
-- Table data for table `admin_resources`
--

INSERT INTO `admin_resources` (`id`, `identifier`)
VALUES (UUID(), 'contactforms');

--
-- Table data for table `pages`
--

INSERT INTO `pages` (`id`, `identifier`, `title`, `meta_description`, `meta_robots`, `meta_author`, `meta_copyright`,
                     `meta_keywords`, `meta_og_title`, `meta_og_image`, `meta_og_description`, `body`, `header`,
                     `footer`, `css_files`, `js_files`, `layout_id`, `layout`, `created_at`, `updated_at`, `deleted`)
VALUES (UUID(), 'contact-success', 'Contact Success', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '',
        NOW(), NOW(), 0),
       (UUID(), 'contact-error', 'Contact Error', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', NOW(),
        NOW(), 0);


--
-- Table structure and data for table `contact_forms`
--

CREATE TABLE `contact_forms`
(
    `id`         char(36)            NOT NULL,
    `name`       varchar(100)        NOT NULL,
    `identifier` varchar(160)        NOT NULL,
    `to_name`    varchar(100)        NOT NULL,
    `to_email`   varchar(127)        NOT NULL,
    `created_at` timestamp           NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp           NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted`    tinyint(1) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `identifier` (`identifier`),
    KEY `contact_forms_deleted_index` (`deleted`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- Provide admins access to all admin resources
INSERT IGNORE INTO `user_groups_admin_resources` (`id`, `user_group_id`, `admin_resource_id`)
SELECT UUID() AS `id`, `user_groups`.`id` AS `user_group_id`, `admin_resources`.`id` AS `admin_resource_id`
FROM user_groups
         INNER JOIN admin_resources ON 1
WHERE user_groups.identifier = 'admin';


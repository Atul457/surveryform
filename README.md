survey form pvt lmt

ALTER TABLE `user_form_links` ADD `sample_size` TEXT NOT NULL AFTER `area_ref`;

ALTER TABLE `survey_forms` ADD `is_copied` TINYINT NOT NULL DEFAULT '0' AFTER `form_name`;

toask : if multiple forms are select then will the same prod size will be given to all??

composer require dompdf/dompdf
added package composer require dompdf/dompdf

ALTER TABLE `user_form_links` ADD `is_admin_completed` TINYINT NOT NULL AFTER `sample_size`;
ALTER TABLE `user_form_links` CHANGE `is_admin_completed` `is_admin_completed` TINYINT(4) NOT NULL DEFAULT '0';
ALTER TABLE `user_form_links` ADD `reason` TEXT NOT NULL AFTER `sample_size`;
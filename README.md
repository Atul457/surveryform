survey form pvt lmt

ALTER TABLE `user_form_links` ADD `sample_size` TEXT NOT NULL AFTER `area_ref`;

ALTER TABLE `survey_forms` ADD `is_copied` TINYINT NOT NULL DEFAULT '0' AFTER `form_name`;

toask : if multiple forms are select then will the same prod size will be given to all??
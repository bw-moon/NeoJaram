ALTER TABLE `jaram_users` ADD `user_job_tag_id` INT( 8 ) NOT NULL AFTER `user_name` ;

ALTER TABLE `jaram_users` ADD INDEX ( `user_job_tag_id` ) ;

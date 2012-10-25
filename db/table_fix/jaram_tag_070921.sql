CREATE TABLE `jaram_tags` (
`tag_id` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`tag_type` VARCHAR( 20 ) NOT NULL ,
`tag_name` VARCHAR( 50 ) NOT NULL ,
`tag_reg_date` DATETIME NOT NULL ,
`tag_reg_uid` INT( 4 ) NOT NULL
) ENGINE = MYISAM ;


CREATE TABLE `jaram_tag_use` (
`tag_use_id` INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`tag_id` INT NOT NULL ,
`tag_use_field` VARCHAR( 30 ) NOT NULL ,
`uid` INT( 4 ) NOT NULL ,
INDEX ( `tag_id` , `tag_use_field` )
) ENGINE = MYISAM ;
ALTER TABLE `jaram_seminar` ADD `seminar_topics_type` ENUM( 'wakka', 'xhtml', 'moni' ) NOT NULL AFTER `seminar_topic` ;
update jaram_seminar set seminar_topics_type='wakka';
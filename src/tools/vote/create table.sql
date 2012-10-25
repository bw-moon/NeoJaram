CREATE TABLE "vote" (
	vote_id int(4) UNSIGNED NOT NULL auto_increment,
	topic_text varchar(255) DEFAULT '0' NOT NULL,
	vote_start int(11) DEFAULT '0' NOT NULL,
	vote_limit int(11) DEFAULT '0' NOT NULL,
	user_uid mediumint(4) DEFAULT '0' NOT NULL,
	
	PRIMARY key(vote_id)
);

CREATE TABLE "vote_option" (
	vote_option_id int(4) UNSIGNED NOT NULL auto_increment,
	vote_id int(4) DEFAULT '0' NOT NULL,
	option_text varchar(255) DEFAULT '0' NOT NULL,
	option_result int(4) DEFAULT '0' NOT NULL,

	PRIMARY key(vote_option_id)
);

CREATE TABLE "voted_result" (
	result_id int(4) unsigned not null auto_increment,
	vote_id int(4) NOT NULL,
	vote_option_id int(4) NOT NULL,
	user_uid mediumint(4) DEFAULT '0' NOT NULL,
	user_ip varchar(15) DEFAULT '0' NOT NULL,
	
	PRIMARY key(result_id)
);

CREATE TABLE "vote_comment" (
	comment_id int(4) unsigned not null auto_increment,
	vote_id int(4) NOT NULL,
	comment varchar(255) NOT NULL,
	user_uid mediumint(4) DEFAULT '0' NOT NULL,
	user_ip varchar(15) DEFAULT '0' NOT NULL,
	signdate varchar(20) DEFAULT NOW NOT NULL

	PRIMARY key(comment_id)
);

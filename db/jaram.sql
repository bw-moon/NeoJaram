-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- 호스트: localhost
-- 처리한 시간: 07-09-27 06:56 
-- 서버 버전: 5.0.45
-- PHP 버전: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 데이터베이스: `jaram`
-- 

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_auth_access`
-- 

CREATE TABLE `jaram_auth_access` (
  `gid` mediumint(4) NOT NULL default '0',
  `pid` mediumint(4) NOT NULL default '0',
  `bid` varchar(20) default NULL,
  `auth_view` tinyint(1) default '0',
  `auth_read` tinyint(1) default '0',
  `auth_post` tinyint(1) default '0',
  `auth_comment` tinyint(1) default '0',
  `auth_edit` tinyint(1) default '0',
  `auth_delete` tinyint(1) default '0',
  `auth_announce` tinyint(1) default '0',
  `auth_vote` tinyint(1) default '0',
  `auth_upload` tinyint(1) default '0',
  KEY `gid` (`gid`),
  KEY `pid` (`pid`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_bbs_monitor`
-- 

CREATE TABLE `jaram_bbs_monitor` (
  `id` mediumint(6) NOT NULL auto_increment,
  `pid` mediumint(4) NOT NULL default '0',
  `bid` varchar(60) default NULL,
  `uid` mediumint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `uid` (`uid`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=122 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_board`
-- 

CREATE TABLE `jaram_board` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `old_id` int(10) unsigned NOT NULL,
  `bid` varchar(20) NOT NULL,
  `title` varchar(120) default NULL,
  `category` int(3) unsigned default NULL,
  `name` varchar(30) default NULL,
  `usrid` varchar(10) default NULL,
  `password` varchar(16) default NULL,
  `email` varchar(60) default NULL,
  `homepage` varchar(60) default NULL,
  `note` text,
  `file_number` int(2) unsigned default NULL,
  `file_size` int(10) unsigned default NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `host` varchar(16) default NULL,
  `count` int(7) unsigned NOT NULL default '0',
  `locate` int(7) unsigned NOT NULL default '0',
  `depth` int(4) default '0',
  `sortno` int(7) unsigned NOT NULL default '0',
  `comment_count` int(4) NOT NULL,
  `extend1` varchar(255) default NULL,
  `extend2` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `locate` (`locate`),
  KEY `depth` (`depth`),
  KEY `sortno` (`sortno`),
  KEY `catalog` (`category`),
  KEY `uid` (`usrid`),
  KEY `email` (`email`),
  KEY `bid` (`bid`),
  KEY `old_id` (`old_id`),
  FULLTEXT KEY `note` (`note`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=25303 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_board_admin`
-- 

CREATE TABLE `jaram_board_admin` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `template` varchar(50) NOT NULL default 'basic',
  `name` varchar(20) default NULL,
  `title` varchar(30) default NULL,
  `view_scale` int(10) unsigned NOT NULL default '0',
  `view_page_scale` int(10) unsigned NOT NULL default '0',
  `using_category` enum('false','true') character set latin1 NOT NULL default 'false',
  `using_file` enum('false','true') character set latin1 NOT NULL default 'false',
  `using_comment` enum('false','true') character set latin1 NOT NULL default 'false',
  `using_comment_sort` enum('false','true') character set latin1 NOT NULL default 'false',
  `using_reply` enum('false','true') character set latin1 NOT NULL default 'false',
  `using_direct_counter` enum('false','true') character set latin1 NOT NULL default 'false',
  `using_direct_comment` enum('false','true') character set latin1 NOT NULL default 'false',
  `using_display_list` enum('false','true') character set latin1 NOT NULL default 'false',
  `using_display_img` enum('false','true') character set latin1 NOT NULL default 'false',
  `using_preview_img` enum('false','true') character set latin1 NOT NULL default 'false',
  `preview_img_x` int(5) unsigned default NULL,
  `preview_img_y` int(5) unsigned default NULL,
  `session_id` varchar(60) default NULL,
  `session_name` varchar(60) default NULL,
  `session_email` varchar(60) default NULL,
  `session_homepage` varchar(60) default NULL,
  `group` int(3) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `group` (`group`),
  KEY `template` (`template`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_board_category`
-- 

CREATE TABLE `jaram_board_category` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `bid` varchar(20) NOT NULL,
  `category_name` varchar(60) default NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`category_name`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_board_comment`
-- 

CREATE TABLE `jaram_board_comment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(30) default NULL,
  `usrid` varchar(10) default NULL,
  `note` text,
  `password` varchar(32) default NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `subid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `subid` (`subid`),
  KEY `usrid` (`usrid`),
  FULLTEXT KEY `note` (`note`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=70812 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_board_draft`
-- 

CREATE TABLE `jaram_board_draft` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `old_id` int(10) unsigned NOT NULL,
  `bid` varchar(20) NOT NULL,
  `title` varchar(120) default NULL,
  `category` int(3) unsigned default NULL,
  `name` varchar(30) default NULL,
  `usrid` varchar(10) default NULL,
  `password` varchar(16) default NULL,
  `email` varchar(60) default NULL,
  `homepage` varchar(60) default NULL,
  `note` text,
  `file_number` int(2) unsigned default NULL,
  `file_size` int(10) unsigned default NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `host` varchar(16) default NULL,
  `count` int(7) unsigned NOT NULL default '0',
  `locate` int(7) unsigned NOT NULL default '0',
  `depth` int(4) default '0',
  `sortno` int(7) unsigned NOT NULL default '0',
  `comment_count` int(4) NOT NULL,
  `extend1` varchar(255) default NULL,
  `extend2` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `locate` (`locate`),
  KEY `depth` (`depth`),
  KEY `sortno` (`sortno`),
  KEY `catalog` (`category`),
  KEY `uid` (`usrid`),
  KEY `email` (`email`),
  KEY `bid` (`bid`),
  KEY `old_id` (`old_id`),
  FULLTEXT KEY `note` (`note`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_board_file`
-- 

CREATE TABLE `jaram_board_file` (
  `file_id` int(10) unsigned NOT NULL auto_increment,
  `file_name` varchar(255) default NULL,
  `file_link` varchar(255) default NULL,
  `file_size` int(12) unsigned NOT NULL default '0',
  `sub_id` int(10) unsigned NOT NULL default '0',
  `file_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `file_count` int(7) unsigned default '0',
  PRIMARY KEY  (`file_id`),
  KEY `sub_id` (`sub_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3910 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_bookmark`
-- 

CREATE TABLE `jaram_bookmark` (
  `id` int(6) NOT NULL auto_increment,
  `uid` int(4) NOT NULL default '0',
  `bookmark_url` varchar(200) default NULL,
  `bookmark_title` varchar(100) default NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=486 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_custom_menu`
-- 

CREATE TABLE `jaram_custom_menu` (
  `id` mediumint(6) NOT NULL auto_increment,
  `uid` mediumint(4) NOT NULL default '0',
  `pid` mediumint(4) NOT NULL default '0',
  `bid` varchar(50) default NULL,
  `order_num` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `pid` (`pid`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1541 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_freshman`
-- 

CREATE TABLE `jaram_freshman` (
  `uid` mediumint(4) unsigned NOT NULL auto_increment,
  `user_id` varchar(25) default NULL,
  `user_name` varchar(25) default NULL,
  `user_password` varchar(32) default NULL,
  `user_email` varchar(255) default NULL,
  `user_homepage` varchar(255) default NULL,
  `user_phone1` varchar(100) default NULL,
  `user_phone2` varchar(100) default NULL,
  `user_icq` varchar(255) default NULL,
  `user_msn` varchar(255) default NULL,
  `user_yim` varchar(255) default NULL,
  `user_sign` text,
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `user_id` (`user_id`,`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=94 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_groups`
-- 

CREATE TABLE `jaram_groups` (
  `gid` mediumint(4) unsigned NOT NULL default '0',
  `group_name` varchar(40) default NULL,
  `group_description` varchar(255) default NULL,
  PRIMARY KEY  (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_group_join_wait`
-- 

CREATE TABLE `jaram_group_join_wait` (
  `id` int(4) unsigned NOT NULL auto_increment,
  `uid` mediumint(4) unsigned NOT NULL default '0',
  `gid` mediumint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uid_gid_key` (`uid`,`gid`),
  KEY `uid` (`uid`,`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=133 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_group_pool`
-- 

CREATE TABLE `jaram_group_pool` (
  `id` int(5) NOT NULL auto_increment,
  `gid` int(5) NOT NULL default '0',
  `flag` enum('o','x') NOT NULL default 'x',
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`),
  KEY `flag` (`flag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9001 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_history`
-- 

CREATE TABLE `jaram_history` (
  `year` int(4) NOT NULL default '0',
  `month` int(2) NOT NULL default '0',
  `contents` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_library_books`
-- 

CREATE TABLE `jaram_library_books` (
  `book_id` int(7) NOT NULL auto_increment,
  `subject` varchar(255) default NULL,
  `subject_org` varchar(255) default NULL,
  `author` varchar(255) default NULL,
  `translator` varchar(255) default NULL,
  `publisher` varchar(60) default NULL,
  `price` double NOT NULL default '0',
  `price_unit` varchar(10) default NULL,
  `isbn` varchar(30) default NULL,
  `publish_date` varchar(30) default NULL,
  `page` int(10) NOT NULL default '0',
  `intro` text,
  `etc1` varchar(255) default NULL,
  `etc2` text,
  PRIMARY KEY  (`book_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=163 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_library_book_instance`
-- 

CREATE TABLE `jaram_library_book_instance` (
  `bid` int(10) NOT NULL auto_increment,
  `book_id` int(10) NOT NULL default '0',
  `bsid` int(10) NOT NULL default '0',
  `count` int(5) NOT NULL default '0',
  PRIMARY KEY  (`bid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_library_book_shelf`
-- 

CREATE TABLE `jaram_library_book_shelf` (
  `bsid` int(10) NOT NULL auto_increment,
  `bs_title` varchar(100) default NULL,
  `uid` int(10) NOT NULL default '0',
  `bs_date` varchar(14) default NULL,
  `bs_state` varchar(20) default NULL,
  `etc1` varchar(255) default NULL,
  `etc2` text,
  PRIMARY KEY  (`bsid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_library_book_status`
-- 

CREATE TABLE `jaram_library_book_status` (
  `id` int(10) NOT NULL auto_increment,
  `bid` int(10) NOT NULL default '0',
  `status` varchar(20) default NULL,
  `uid` int(10) NOT NULL default '0',
  `date` varchar(15) default NULL,
  `tag` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=326 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_library_book_url`
-- 

CREATE TABLE `jaram_library_book_url` (
  `bid` int(10) NOT NULL default '0',
  `url` varchar(255) default NULL,
  `url_type` varchar(10) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_library_book_wiki`
-- 

CREATE TABLE `jaram_library_book_wiki` (
  `wiki_id` int(8) NOT NULL auto_increment,
  `book_id` int(8) NOT NULL default '0',
  `text` text,
  `time` int(11) NOT NULL default '0',
  `etc` varchar(255) default NULL,
  PRIMARY KEY  (`wiki_id`),
  KEY `book_id` (`book_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_one_line`
-- 

CREATE TABLE `jaram_one_line` (
  `msg_id` int(11) NOT NULL auto_increment,
  `msg_text` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `target_gid` int(11) NOT NULL,
  `target_pid` int(11) NOT NULL,
  `msg_type` enum('info','error','warn','die') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `reg_date` datetime NOT NULL,
  PRIMARY KEY  (`msg_id`),
  KEY `uid` (`uid`),
  KEY `target_gid` (`target_gid`),
  KEY `target_pid` (`target_pid`),
  KEY `msg_type` (`msg_type`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_one_line_user`
-- 

CREATE TABLE `jaram_one_line_user` (
  `msg_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `read_count` int(2) NOT NULL,
  `status` varchar(8) NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY  (`msg_id`,`uid`),
  KEY `update_date` (`update_date`),
  KEY `status` (`status`),
  KEY `read_count` (`read_count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_page_load_time`
-- 

CREATE TABLE `jaram_page_load_time` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `uid` mediumint(4) default NULL,
  `pid` mediumint(4) unsigned default NULL,
  `bid` varchar(255) character set latin1 default NULL,
  `timestemp` int(11) unsigned default NULL,
  `page` varchar(255) character set latin1 NOT NULL default '',
  `referer_page` varchar(255) character set latin1 NOT NULL default '',
  `ip` varchar(15) character set latin1 NOT NULL default '',
  `second` varchar(30) character set latin1 NOT NULL default '',
  `cached` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `timestemp` (`timestemp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_programs`
-- 

CREATE TABLE `jaram_programs` (
  `program_id` int(11) NOT NULL auto_increment,
  `pid` mediumint(4) NOT NULL default '0',
  `bid` varchar(30) default NULL,
  `main_menu` varchar(30) default NULL,
  `sub_menu` varchar(40) default NULL,
  `dir` varchar(100) default NULL,
  `order_num` smallint(3) NOT NULL default '10',
  PRIMARY KEY  (`program_id`),
  KEY `pid` (`pid`),
  KEY `bid` (`bid`),
  KEY `main_menu` (`main_menu`),
  KEY `sub_menu` (`sub_menu`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_schedule`
-- 

CREATE TABLE `jaram_schedule` (
  `schedule_id` mediumint(4) unsigned NOT NULL auto_increment,
  `uid` mediumint(4) unsigned NOT NULL default '0',
  `schedule_subject` varchar(100) default NULL,
  `schedule_text` text,
  `schedule_start` int(8) unsigned NOT NULL default '0',
  `schedule_period` int(6) unsigned NOT NULL default '0',
  `schedule_mdate` int(11) unsigned NOT NULL default '0',
  `schedule_dday` mediumint(2) unsigned NOT NULL default '0',
  `mailing` mediumint(2) unsigned NOT NULL default '0',
  `group_id` int(4) unsigned NOT NULL default '0',
  `seminar_id` mediumint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`schedule_id`),
  KEY `user_id` (`uid`),
  KEY `seminar_id` (`seminar_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='�ڶ� ������ �⺻ ���̺�' AUTO_INCREMENT=652 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_seminar`
-- 

CREATE TABLE `jaram_seminar` (
  `seminar_id` mediumint(4) unsigned NOT NULL auto_increment,
  `seminar_topic` varchar(100) default NULL,
  `seminar_topics_type` enum('wakka','xhtml','moni') NOT NULL,
  `seminar_desc` text,
  `seminar_topics` text,
  `seminar_benefit` text,
  `seminar_file` varchar(120) default NULL,
  `seminar_group_id` mediumint(4) unsigned NOT NULL default '0',
  `seminar_group_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`seminar_id`),
  KEY `seminar_group_id` (`seminar_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='�����ٿ��� ���̳��� ���� ��, �̰��� �����Է�' AUTO_INCREMENT=104 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_seminar_comment`
-- 

CREATE TABLE `jaram_seminar_comment` (
  `comment_id` int(6) NOT NULL auto_increment,
  `seminar_id` mediumint(4) unsigned NOT NULL default '0',
  `user_id` mediumint(4) unsigned NOT NULL default '0',
  `name` varchar(40) default NULL,
  `text` text,
  `reg_date` int(11) unsigned NOT NULL default '0',
  `ip` varchar(16) default NULL,
  PRIMARY KEY  (`comment_id`),
  KEY `seminar_id` (`seminar_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 COMMENT='½ºÆè : WHERE text LIKE ''%http://%'' AND user_id=0' AUTO_INCREMENT=6936 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_seminar_file`
-- 

CREATE TABLE `jaram_seminar_file` (
  `seminar_file_id` int(4) NOT NULL auto_increment,
  `seminar_id` int(11) NOT NULL default '0',
  `filename` varchar(255) default NULL,
  `file_desc` text,
  `file_flag` varchar(255) default NULL,
  `file_flag2` varchar(255) default NULL,
  PRIMARY KEY  (`seminar_file_id`),
  KEY `seminar_file_id` (`seminar_file_id`),
  KEY `seminar_id` (`seminar_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=125 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_seminar_reader`
-- 

CREATE TABLE `jaram_seminar_reader` (
  `seminar_id` mediumint(4) unsigned NOT NULL default '0',
  `user_id` mediumint(4) unsigned NOT NULL default '0',
  `seminar_reader_date` int(11) unsigned NOT NULL default '0',
  KEY `seminar_id` (`seminar_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='���̳� �Խù��� ���� ����� �Է�';

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_solar_to_lunar`
-- 

CREATE TABLE `jaram_solar_to_lunar` (
  `num` int(11) NOT NULL auto_increment,
  `lunar_date` date NOT NULL default '0000-00-00',
  `solar_date` date NOT NULL default '0000-00-00',
  `yun` tinyint(1) NOT NULL default '0',
  `ganji` char(5) character set latin1 collate latin1_bin NOT NULL default '',
  PRIMARY KEY  (`num`),
  KEY `lunar_date` (`lunar_date`),
  KEY `solar_date` (`solar_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='���°� ����� 2200����� ������ ����' AUTO_INCREMENT=109939 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_spam_list`
-- 

CREATE TABLE `jaram_spam_list` (
  `ip` varchar(16) character set latin1 NOT NULL default '',
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_tags`
-- 

CREATE TABLE `jaram_tags` (
  `tag_id` int(8) NOT NULL auto_increment,
  `tag_type` varchar(20) character set utf8 NOT NULL,
  `tag_name` varchar(50) character set utf8 NOT NULL,
  `tag_reg_date` datetime NOT NULL,
  `tag_reg_uid` int(4) NOT NULL,
  PRIMARY KEY  (`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_tag_use`
-- 

CREATE TABLE `jaram_tag_use` (
  `tag_use_id` int(9) NOT NULL auto_increment,
  `tag_id` int(11) NOT NULL,
  `tag_use_field` varchar(30) character set utf8 NOT NULL,
  `uid` int(4) NOT NULL,
  PRIMARY KEY  (`tag_use_id`),
  KEY `tag_id` (`tag_id`,`tag_use_field`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_users`
-- 

CREATE TABLE `jaram_users` (
  `uid` mediumint(4) unsigned NOT NULL default '0',
  `user_id` varchar(25) default NULL,
  `user_name` varchar(25) default NULL,
  `user_job_tag_id` int(8) NOT NULL,
  `user_birthday` date NOT NULL,
  `user_password` varchar(32) default NULL,
  `user_number` tinyint(1) default NULL,
  `user_email` varchar(255) default NULL,
  `user_homepage` varchar(255) default NULL,
  `user_phone1` varchar(100) default NULL,
  `user_phone2` varchar(100) default NULL,
  `user_icq` varchar(255) default NULL,
  `user_msn` varchar(255) default NULL,
  `user_yim` varchar(255) default NULL,
  `user_msgr_type` enum('nate','gtalk','msn') NOT NULL,
  `user_msgr_id` varchar(100) NOT NULL,
  `user_sign` text,
  `user_having_image1` enum('false','true') character set latin1 NOT NULL default 'false',
  `user_having_image2` enum('false','true') character set latin1 NOT NULL default 'false',
  `user_last_login_ip` varchar(23) default NULL,
  `user_last_login_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `user_id` (`user_id`,`uid`),
  KEY `user_job_tag_id` (`user_job_tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_user_group`
-- 

CREATE TABLE `jaram_user_group` (
  `id` mediumint(4) NOT NULL auto_increment,
  `gid` mediumint(4) unsigned NOT NULL default '0',
  `uid` mediumint(4) unsigned NOT NULL default '0',
  `status` enum('','o') NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user_group_key` (`gid`,`uid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='status �ʵ��� flag ���� : o = �׷� ����, ��' AUTO_INCREMENT=689 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_vote`
-- 

CREATE TABLE `jaram_vote` (
  `vote_id` int(4) unsigned NOT NULL auto_increment,
  `topic_text` varchar(255) default NULL,
  `topic_comment` text,
  `vote_start` int(11) NOT NULL default '0',
  `vote_limit` int(11) NOT NULL default '0',
  `user_uid` mediumint(4) NOT NULL default '0',
  `is_open` char(2) default NULL,
  PRIMARY KEY  (`vote_id`),
  KEY `vote_id` (`vote_id`),
  KEY `user_uid` (`user_uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_vote_comment`
-- 

CREATE TABLE `jaram_vote_comment` (
  `comment_id` int(4) unsigned NOT NULL auto_increment,
  `vote_id` int(4) NOT NULL default '0',
  `comment` varchar(255) default NULL,
  `user_uid` mediumint(4) NOT NULL default '0',
  `user_ip` varchar(15) default NULL,
  `signdate` varchar(20) default NULL,
  PRIMARY KEY  (`comment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=320 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_vote_option`
-- 

CREATE TABLE `jaram_vote_option` (
  `vote_option_id` int(4) unsigned NOT NULL auto_increment,
  `vote_id` int(4) NOT NULL default '0',
  `option_text` varchar(255) default NULL,
  `option_result` int(4) NOT NULL default '0',
  PRIMARY KEY  (`vote_option_id`),
  KEY `vote_id` (`vote_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=173 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_vote_result`
-- 

CREATE TABLE `jaram_vote_result` (
  `result_id` int(4) unsigned NOT NULL auto_increment,
  `vote_id` int(4) NOT NULL default '0',
  `vote_option_id` int(4) NOT NULL default '0',
  `user_uid` varchar(10) default NULL,
  `user_ip` varchar(15) default NULL,
  PRIMARY KEY  (`result_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1071 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_web_message`
-- 

CREATE TABLE `jaram_web_message` (
  `id` mediumint(8) NOT NULL auto_increment,
  `message` text,
  `count` mediumint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1681 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_web_messenger`
-- 

CREATE TABLE `jaram_web_messenger` (
  `id` mediumint(8) NOT NULL auto_increment,
  `send` mediumint(4) unsigned NOT NULL default '0',
  `receive` mediumint(4) unsigned NOT NULL default '0',
  `send_time` char(10) default NULL,
  `read_time` char(10) default NULL,
  `is_read` enum('N','Y','F') character set latin1 NOT NULL default 'N',
  `message` mediumint(8) NOT NULL default '0',
  `delete_R` enum('N','Y') character set latin1 default 'N',
  `delete_S` enum('N','Y') character set latin1 default 'N',
  PRIMARY KEY  (`id`),
  KEY `message` (`message`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3142 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_widget`
-- 

CREATE TABLE `jaram_widget` (
  `widget_id` int(11) NOT NULL auto_increment,
  `widget_icon` varchar(100) NOT NULL,
  `widget_name` varchar(15) NOT NULL,
  `widget_location` varchar(200) NOT NULL,
  `widget_content` text NOT NULL,
  `widget_pref` varchar(255) NOT NULL,
  `widget_nickname` varchar(255) NOT NULL,
  `widget_desc` varchar(255) NOT NULL,
  `reg_date` datetime NOT NULL,
  `modify_date` datetime NOT NULL,
  `widget_author` int(11) NOT NULL,
  `widget_status` enum('block','allow','wait') NOT NULL default 'wait',
  `widget_type` enum('php','xml') NOT NULL default 'xml',
  PRIMARY KEY  (`widget_id`),
  UNIQUE KEY `widget_nickname` (`widget_nickname`),
  KEY `widget_type` (`widget_type`),
  KEY `widget_status` (`widget_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

-- 
-- 테이블 구조 `jaram_widget_user`
-- 

CREATE TABLE `jaram_widget_user` (
  `widget_user_id` int(11) NOT NULL auto_increment,
  `widget_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sort_order` tinyint(3) NOT NULL,
  `widget_hide` tinyint(1) NOT NULL,
  `widget_pref` varchar(255) NOT NULL,
  `widget_content` text NOT NULL,
  PRIMARY KEY  (`widget_user_id`),
  FULLTEXT KEY `widget_content` (`widget_content`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

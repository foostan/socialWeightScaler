<?php

/**
 * This model defined a user
 *
 */

/*

Needed database schema:

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_login` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `login_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_grade` int(11) NOT NULL,
  `region` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subregion` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(63) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timezone` int(11) NOT NULL,
  `about_me` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `profile_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_url_small` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_url_large` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_url_huge` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `age` int(11) NOT NULL,
  `blood_type` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `has_app` tinyint(4) NOT NULL,
  `user_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `modified_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8

*/

class Model_User extends Orm\Model{

 	protected static $_properties = array(
 		'id',
 		'last_login',
 		'login_hash',
 		'nickname',
 		'display_name',
 		'user_grade',
 		'region',
 		'subregion',
 		'language',
 		'timezone',
 		'about_me',
 		'birthday',
 		'profile_url',
 		'thumbnail_url_small',
 		'thumbnail_url_large',
 		'thumbnail_url_huge',
 		'gender',
 		'age',
 		'blood_type',
 		'has_app',
 		'user_hash',
 		'user_type',
 		'created_at',
 		'modified_at',
 	);

	protected static $_has_many = array(
		'wslogs' => array(
			'model_to' => 'Model_Wslog',
			'key_from' => 'id',
			'key_to' => 'user_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),

		'goods' => array(
		    'model_to' => 'Model_Good',
		    'key_from' => 'id',
		    'key_to' => 'user_id',
		    'cascade_save' => true,
		    'cascade_delete' => true,
		)
	);

}


<?php

/**
 * This model defined a weight scale's log
 *
 */

/*

Needed database schema:

CREATE TABLE IF NOT EXISTS `wslogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `measured_at` int(11) NOT NULL,
  `weight` float NOT NULL,
  `body_fat` float NOT NULL,
  `share_with_friends_is` tinyint(4) NOT NULL,
  `share_with_everyone_is` tinyint(4) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `modified_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

*/

class Model_Wslog extends Orm\Model{

 	protected static $_properties = array(
 		'id',
 		'user_id',
 		'measured_at',
 		'weight',
 		'body_fat',
 		'comments',
 		'share_with_friends_is',
 		'share_with_everyone_is',
 		'created_at',
 		'modified_at',
 	);

 	public static function validate($wslog){
 		$val = Validation::forge($wslog);
		$val->add('select-choice-year', 'Year')    ->add_rule('match_pattern', '/^[0-9]{4}$/');
		$val->add('select-choice-month', 'Month')  ->add_rule('match_pattern', '/^[0-9]{2}$/');
		$val->add('select-choice-day', 'Day')      ->add_rule('match_pattern', '/^[0-9]{2}$/');
		$val->add('select-choice-hour', 'Hour')    ->add_rule('match_pattern', '/^[0-9]{2}$/');
		$val->add('weight', 'Your weight')         ->add_rule('match_pattern', '/^[0-9]+(\.[0-9]+|)$/');
		$val->add('body_fat', 'Your body fat')     ->add_rule('match_pattern', '/^[0-9]+(\.[0-9]+|)$/');
		$val->add('share-with-friends-is', 'Share with friends') ->add_rule('match_pattern', '/^[01]$/');
		$val->add('share-with-everyone-is', 'Share with everyone') ->add_rule('match_pattern', '/^[01]$/');
		return $val;
 	}

}

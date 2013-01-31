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
  `weight_diff` float NOT NULL,
  `body_fat` float NOT NULL,
  `body_fat_diff` float NOT NULL,
  `share_with_friends_is` tinyint(4) NOT NULL,
  `share_with_everyone_is` tinyint(4) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `modified_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

*/

class Model_Wslog extends Orm\Model
{

 	protected static $_properties = array(
 		'id',
 		'user_id',
 		'measured_at',
 		'weight',
 		'weight_diff',
 		'body_fat',
 		'body_fat_diff',
 		'comments',
 		'share_with_friends_is',
 		'share_with_everyone_is',
 		'created_at',
 		'modified_at',
 	);

	protected static $_has_one = array('user' => array(
	    'model_to' => 'Model_User',
	    'key_from' => 'user_id',
	    'key_to' => 'id',
	    'cascade_save' => false,
	    'cascade_delete' => false,
	));

	protected static $_has_many = array('goods' => array(
	    'model_to' => 'Model_Good',
	    'key_from' => 'id',
	    'key_to' => 'wslog_id',
	    'cascade_save' => true,
	    'cascade_delete' => true,
	));

 	public static function validate($wslog)
	{
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

	public static function generate_diff_msg($value, $sign)
	{
		if( $value > 0 )     return "<span class=\"fontawesome-arrow-up\"></span> $value $sign";
		elseif( $value < 0 ) return "<span class=\"fontawesome-arrow-down\"></span> $value $sign";
		else                 return "<span class=\"fontawesome-minus\"></span>";
	}


	public function how_many_good($type = 'good')
	{

		$column_name = $type==='good' ? 'good_is' : 'nogood_is';
		$goods = Model_Good::find('all',array(
			'where' => array(
				array('wslog_id',$this->id),
				array($column_name,true),
			),
		));

		return count($goods);
	}

	public function has_good($type = 'good', $user_id = null)
	{
		$column_name = $type==='good' ? 'good_is' : 'nogood_is';
		$good = Model_Good::find('first',array(
			'where' => array(
				array('user_id',$user_id),
				array('wslog_id',$this->id),
				array($column_name,true),
			),
		));

		return $good ? true : false;
	}
}

<?php

/**
 * This model defined a weight scale's log
 *
 */

class Model_Wslog extends Orm\Model{

 	protected static $_properties = array(
 		'id',
 		'username',
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

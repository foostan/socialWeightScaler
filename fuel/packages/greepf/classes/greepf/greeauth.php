<?php
/**
 * Conection GREE Platform solution for FuelPHP
 *
 * @package		Greepf	
 * @version		1.0
 * @useror		foostan	
 * @license		MIT License
 */
 
/*

Needed database schema:

CREATE TABLE IF NOT EXISTS `greeusers` (
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

namespace Greepf;

class Greepf_Greeauth extends \Greepf_Driver {

	/**
	 * @return string 
	 */
	public function get_user_id()
	{
		return \Session::get('user_id');
	}

	/**
	 * @return string 
	 */
	public function get_user_difftime()
	{
		return \Session::get('timezone')*60;
	}

	/**
	 * Perform actural login check 
	 *
	 * @return bool
	 */
	public function _check()
	{
		$user_id    = \Session::get('user_id');
		$timezone    = \Session::get('timezone');
		$login_hash  = \Session::get('login_hash');
		if( !is_null($user_id) || !is_null($timezone) || !is_null($login_hash) )
		{
			$db_select_result = \DB::select('id')
				->where('id', '=', $user_id)
				->and_where('login_hash', '=', $login_hash)
				->from($this->config['db_table_name'])
				->execute()->current();

			if(isset($db_select_result['id']))
			{
				return true;
			}
		}

		\Session::delete('user_id');
		\Session::delete('timezone');
		\Session::delete('login_hash');
		return false;
	}

	/**
	 * Login method 
	 *
	 * @return bool whether login succeeded 
	 */
	public function _login()
	{
		$user_request = $this->validate_api_response();
		if(is_null($user_request)){
			return false;
		}

		// user opensocial_viewer_id as user_id
		$user_id = $user_request->get_parameter('opensocial_viewer_id');
		if(is_null($user_id)){
			return false;
		}
		\Session::set('user_id', $user_id);
		\Session::set('login_hash', $this->create_login_hash());

		return $this->update_attributes();
	}

	/**
	 * Update user attributes use by GREE Platform People API
	 *
	 * @return bool 
	 */
	public function update_attributes()
	{
		$user_id = $this->get_user_id();
		if(is_null($user_id)){ return false; } 

		$people = $this->people_api(array(
			'xoauth_requestor_id' => $user_id
		));

		if(isset($people['entry'][0]))
		{
			$people_attributes   = $people['entry'][0];
			$db_people_attributes = array( 
				'id'                  => $user_id,
				'nickname'            => array_key_exists('nickname',$people_attributes) ? $people_attributes['nickname'] : '',
				'display_name'        => array_key_exists('displayName',$people_attributes) ? $people_attributes['displayName'] : '',
				'user_grade'          => array_key_exists('userGrade',$people_attributes) ? $people_attributes['userGrade'] : '',
				'region'              => array_key_exists('region',$people_attributes) ? $people_attributes['region'] : '',
				'subregion'           => array_key_exists('subregion',$people_attributes) ? $people_attributes['subregion'] : '',
				'language'            => array_key_exists('language',$people_attributes) ? $people_attributes['language'] : '',
				'timezone'            => array_key_exists('timezone',$people_attributes) ? $people_attributes['timezone'] : '',
				'about_me'            => array_key_exists('abountMe',$people_attributes) ? $people_attributes['aboutMe'] : '',
				'birthday'            => array_key_exists('birthday',$people_attributes) ? $people_attributes['birthday'] : '',
				'profile_url'         => array_key_exists('profileUrl',$people_attributes) ? $people_attributes['profileUrl'] : '',
				'thumbnail_url_small' => array_key_exists('thumbnailUrlSmall',$people_attributes) ? $people_attributes['thumbnailUrlSmall'] : '',
				'thumbnail_url_large' => array_key_exists('thumbnailUrlLarge',$people_attributes) ? $people_attributes['thumbnailUrlLarge'] : '',
				'thumbnail_url_huge'  => array_key_exists('thumbnailUrlHuge', $people_attributes) ? $people_attributes['thumbnailUrlHuge'] : '',
				'gender'              => array_key_exists('gender',$people_attributes) ? $people_attributes['gender'] : '',
				'age'                 => array_key_exists('age',$people_attributes) ? $people_attributes['age'] : '',
				'blood_type'          => array_key_exists('bloodType',$people_attributes) ? $people_attributes['bloodType'] : '',
				'has_app'             => array_key_exists('hasApp',$people_attributes) ? $people_attributes['hasApp'] : '',
				'user_hash'           => array_key_exists('userHash',$people_attributes) ? $people_attributes['userHash'] : '',
				'user_type'           => array_key_exists('userType',$people_attributes) ? $people_attributes['userType'] : '',
			);

			$db_select_result = \DB::select('id')
				->where('id', '=', $user_id)
				->from($this->config['db_table_name'])
				->execute()->current();

			// create user and set attributes
			if(isset($db_select_result['id']))
			{
				$db_people_attributes['modified_at'] = \Date::forge()->get_timestamp();
				$db_update_result = \DB::update($this->config['db_table_name'])
					->set($db_people_attributes)
					->where('id', '=', $user_id)
					->execute();
			}
			// update attributes
			else
			{
				$db_people_attributes['created_at'] = \Date::forge()->get_timestamp();
				$db_people_attributes['modified_at'] = $db_people_attributes['created_at'];
				$db_update_result = \DB::insert($this->config['db_table_name'])
					->set($db_people_attributes)
					->execute();
			}

			\Session::set('timezone', $db_people_attributes['timezone']);

			return true;
		}

		return false;
	}

	/**
	 * Creates a temporary hash that will validate the current login
	 *
	 * @return  string
	 */
	public function create_login_hash()
	{
		$user_id = $this->get_user_id();
		if(is_null($user_id))
		{
			throw new \GreepfException('User not logged in, can\'t create login hash.', 1);
		}

		$last_login = \Date::forge()->get_timestamp();
		$login_hash = sha1($this->config['login_hash_salt'].$user_id.$last_login);

		$db_update_result = \DB::update($this->config['db_table_name'])
			->set(array('last_login' => $last_login, 'login_hash' => $login_hash))
			->where('id', '=', $user_id)
			->execute();

		return $login_hash;
	}

	/**
	 * Logout method 
	 */
	public function _logout()
	{
		\Session::delete('user_id');
		\Session::delete('timezone');
		\Session::delete('login_hash');
		return true;
	}
}

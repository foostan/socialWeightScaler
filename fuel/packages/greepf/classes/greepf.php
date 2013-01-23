<?php

namespace Greepf;


class Greepf 
{
	public static function _init(){
		require_once dirname(__FILE__).'/vendor/oauth.php';
		\Config::load('greepf');
	}

	/**
	 * @param   string event type(login|cycle)
	 * @return  bool|int 
	 */
	public static function setup($event_type = 'login'){
		$user_request         = OAuthRequest::from_request(null,null,null);
		$oauth_token          = $user_request->get_parameter('oauth_token');
		$oauth_token_secret   = $user_request->get_parameter('oauth_token_secret');
		$oauth_signature      = $user_request->get_parameter('oauth_signature');
		$signature_method     = new OAuthSignatureMethod_HMAC_SHA1();
		$oauth_consumer       = new OAuthConsumer(\Config::get('oauth.consumer_key'),\Config::get('oauth.consumer_secret'));
		$oauth_access_token   = new OAuthToken($oauth_token, $oauth_token_secret);

		$signature_valid = $signature_method->check_signature(
			$user_request,
			$oauth_consumer,
			$oauth_access_token,
			$oauth_signature
		);

		if($signature_valid){
			if($event_type === 'cycle'){
				$id = $user_request->get_parameter('id');

			}else{
				// login event
				$id = $user_request->get_parameter('opensocial_viewer_id');

				// get people attributes from GREE Platform 
				$people_array = self::people_api(array(
					'oauth_access_token' => $oauth_access_token,
					'xoauth_requestor_id' => $id
				));
				if(isset($people_array['entry'][0])){
					$people_attributes   = $people_array['entry'][0];
					$db_people_attributes = array( 
						'username'            => $id,
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

					// create|update gree platform attributes
					$db_select_result = \DB::select('id')
						->where('username', '=', $id)
						->from(\Config::get('people.table_name'))
						->execute(\Config::get('people.db_connection'))->current();

					if(isset($db_select_result['id'])){
						$db_update_result = \DB::update(\Config::get('people.table_name'))
							->set($db_people_attributes)
							->where('username', '=', $id)
							->execute(\Config::get('people.db_connection'));
					}else{
						$db_people_attributes['created_at'] = \Date::forge()->get_timestamp();
						$db_update_result = \DB::insert(\Config::get('people.table_name'))
							->set($db_people_attributes)
							->execute(\Config::get('people.db_connection'));
					}
				}else{
					// failed get people attributes from GREE Platform 
					// reset id 
					$id = null;
				}
			}

			if(isset($id)){
				\Session::set('oauth_access_token_key',$oauth_token);
				\Session::set('oauth_access_token_secret',$oauth_token_secret);
				return $id;
			}
		}

		\Session::delete('oauth_access_token_key');
		\Session::delete('oauth_access_token_secret');
		return false;
	}

	/**
	 * check already finished setup 
	 * 
	 * @return  bool 
	 */
	public static function check(){
		if( \Auth::check()
			&& (\Session::get('username') !== false )
			&& (\Session::get('oauth_access_token_key') !== false )
			&& (\Session::get('oauth_access_token_secret') !== false )
		){ 
			return true;
		}else{
			return false;
		}
	}


	public static function get_request_parameters(){
		if(static::check() === false){ return array(); }

		$user_request        = OAuthRequest::from_request(null,null,null);
		$oauth_signature     = $user_request->get_parameter('oauth_signature');
		$signature_method    = new OAuthSignatureMethod_HMAC_SHA1();
		$oauth_consumer      = new OAuthConsumer(\Config::get('oauth.consumer_key'),\Config::get('oauth.consumer_secret'));
		$oauth_access_token   = new OAuthToken(\Session::get('oauth_access_token_key'), \Session::get('oauth_access_token_secret'));
		$signature_valid = $signature_method->check_signature(
			$user_request,
			$oauth_consumer,
			$oauth_access_token,
			$oauth_signature
		);
		
		return $signature_valid ? $user_request->get_parameters() : array();
	}

	public static function people_api($parameters = array()){
		
		if((!isset($parameters['oauth_access_token'])) && (static::check() === false)){
			 return array();
		}

		$template_parameters['guid']     = array_key_exists('guid',$parameters)     ? $parameters['guid']     : '@me';
		$template_parameters['selector'] = array_key_exists('selector',$parameters) ? $parameters['selector'] : '@self';
		$template_parameters['pid']      = array_key_exists('pid',$parameters)      ? $parameters['pid']      : '';
		
		$query_parameters['fields']      = array_key_exists('fields',$parameters)      ? $parameters['fields']      : '';
		$query_parameters['count']       = array_key_exists('count',$parameters)       ? $parameters['count']       : '';
		$query_parameters['startIndex']  = array_key_exists('startIndex',$parameters)  ? $parameters['startIndex']  : '';
		$query_parameters['filterBy']    = array_key_exists('filterBy',$parameters)    ? $parameters['filterBy']    : '';
		$query_parameters['filterOp']    = array_key_exists('filterOp',$parameters)    ? $parameters['filterOp']    : '';
		$query_parameters['filterValue'] = array_key_exists('filterValue',$parameters) ? $parameters['filterValue'] : '';
		$query_parameters['xoauth_requestor_id'] = array_key_exists('xoauth_requestor_id',$parameters) ? $parameters['xoauth_requestor_id'] : \Session::get('username');

 
		$signature_method    = new OAuthSignatureMethod_HMAC_SHA1();
		$oauth_consumer      = new OAuthConsumer(\Config::get('oauth.consumer_key'),\Config::get('oauth.consumer_secret'));
		$oauth_access_token  = isset($parameters['oauth_access_token']) ? $parameters['oauth_access_token']
			: new OAuthToken(\Session::get('oauth_access_token_key'), \Session::get('oauth_access_token_secret'));
		$http_method         = 'GET';
		$endpoint_url        = \Config::get('oauth.endpoint_url') . '/people/'.$template_parameters['guid'].'/'.$template_parameters['selector'].'/'.$template_parameters['pid'];
		$oauth_request = OAuthRequest::from_consumer_and_token(
		    $oauth_consumer, 
		    $oauth_access_token, 
		    $http_method, 
		    $endpoint_url, 
		    $query_parameters
		);

		$oauth_request->sign_request($signature_method, $oauth_consumer, $oauth_access_token);

		$authorization_header_string = $oauth_request->to_header();
		$authorization_header = substr($authorization_header_string, strlen('Authorization:'));

		try
		{
			$result = \Request::forge($oauth_request,'curl')
				->set_method('GET')
				->set_option('TIMEOUT','10')
				->set_mime_type('json')
				->set_header('Authorization',$authorization_header)
				->execute()->response()->body();

			return $result;
		}catch (\HttpNotFoundException $e){
			return array();
		}
	}

	public static function test(){
			echo \Session::get('username').'<br/>';
			echo \Session::get('oauth_access_token_key').'<br/>';
			echo \Session::get('oauth_access_token_secret').'<br/>';
	}

}


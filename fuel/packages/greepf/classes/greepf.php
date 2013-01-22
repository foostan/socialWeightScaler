<?php

namespace Greepf;


class Greepf 
{
	public static function _init(){
		require_once dirname(__FILE__).'/vendor/oauth.php';
		\Config::load('greepf');
	}

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
			\Session::set('oauth_access_token_key',$oauth_token);
			\Session::set('oauth_access_token_secret',$oauth_token_secret);
			if($event_type === 'cycle'){
				$id = $user_request->get_parameter('id');
			}else{
				$id = $user_request->get_parameter('opensocial_viewer_id');
			}
			return is_null($id) ? false : $id; 
		}else{
			\Session::delete('oauth_access_token_key');
			\Session::delete('oauth_access_token_secret');
			return false;
		}
	}

	public static function check(){
		if( \Auth::check()
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
		if(static::check() === false){ return array(); }

		$template_parameters['guid']     = array_key_exists('guid',$parameters)     ? $parameters['guid']     : '@me';
		$template_parameters['selector'] = array_key_exists('selector',$parameters) ? $parameters['selector'] : '@self';
		$template_parameters['pid']      = array_key_exists('pid',$parameters)      ? $parameters['pid']      : '';
		
		$query_parameters['fields']      = array_key_exists('fields',$parameters)      ? $parameters['fields']      : '';
		$query_parameters['count']       = array_key_exists('count',$parameters)       ? $parameters['count']       : '';
		$query_parameters['startIndex']  = array_key_exists('startIndex',$parameters)  ? $parameters['startIndex']  : '';
		$query_parameters['filterBy']    = array_key_exists('filterBy',$parameters)    ? $parameters['filterBy']    : '';
		$query_parameters['filterOp']    = array_key_exists('filterOp',$parameters)    ? $parameters['filterOp']    : '';
		$query_parameters['filterValue'] = array_key_exists('filterValue',$parameters) ? $parameters['filterValue'] : '';
		$query_parameters['xoauth_requestor_id'] = array_key_exists('xoauth_requestor_id',$parameters) ? \Session::get('username') : '';
 
		$signature_method    = new OAuthSignatureMethod_HMAC_SHA1();
		$oauth_consumer      = new OAuthConsumer(\Config::get('oauth.consumer_key'),\Config::get('oauth.consumer_secret'));
		$oauth_access_token   = new OAuthToken(\Session::get('oauth_access_token_key'), \Session::get('oauth_access_token_secret'));
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
}


<?php

namespace Greepf;


class Greepf 
{
	private $oauth_consumer_key = null;
	private $oauth_consumer_secret = null;
	private $request_params = array();

	public function __construct(){
		require dirname(__FILE__).'/vendor/oauth.php';
		\Config::load('greepf');

		$this->oauth_consumer_key = \Config::get('oauth.consumer_key');
		$this->oauth_consumer_secret = \Config::get('oauth.consumer_secret');

	}

	public function validate_request()
	{
		$user_request = \OAuthRequest::from_request(null,null,null);
		$oauth_token          = $user_request->get_parameter('oauth_token');
		$oauth_token_secret   = $user_request->get_parameter('oauth_token_secret');
		$oauth_signature      = $user_request->get_parameter('oauth_signature');
		$signature_method = new \OAuthSignatureMethod_HMAC_SHA1();
		$oauth_consumer = new \OAuthConsumer($this->oauth_consumer_key, $this->oauth_consumer_secret);
		$access_token   = new \OAuthToken($oauth_token, $oauth_token_secret);
		$signature_valid = $signature_method->check_signature($user_request, $oauth_consumer, $access_token, $oauth_signature);

		// read validated request params
		$this->request_params['opensocial_viewer_id']	= $user_request->get_parameter('opensocial_viewer_id');
		$this->request_params['id']      				= $user_request->get_parameter('id'); // This param is used by cycle events
		return $signature_valid ? true : false;
	}

	public function get_request_param($request_param_name){
		if(array_key_exists($request_param_name, $this->request_params)){
			return $this->request_params[$request_param_name];
		}
		return null;
	}
}


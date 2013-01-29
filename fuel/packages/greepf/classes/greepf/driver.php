<?php
/**
 * Conection GREE Platform solution for FuelPHP
 *
 * @package		Greepf	
 * @version		1.0
 * @useror		foostan	
 * @license		MIT License
 */

namespace Greepf;

abstract class Greepf_Driver {

	/**
	 * @var  array  $config  the greepf's settings
	 */
	protected $config = array();

	/**
	 * @var  boolean  $deleted  whether the greepf is deleted
	 */
	protected $deleted = false;


	/**
	 * Greepf driver constructor.
	 *
	 * @param  array  $config  greepf config array
	 */
	public function __construct($config)
	{
		$this->config = $config;
		require_once dirname(__FILE__).'/vendor/oauth.php';
	}

	/**
	 * Validate GREE Platform API Response 
	 *
	 * @return  bool 
	 */
	public function validate_api_response()
	{
		$user_request       = OAuthRequest::from_request(null,null,null);
		$oauth_signature    = $user_request->get_parameter('oauth_signature');
		$signature_method   = new OAuthSignatureMethod_HMAC_SHA1();
		$oauth_consumer     = new OAuthConsumer($this->config['consumer_key'],$this->config['consumer_secret']);

		$oauth_token_key    = $user_request->get_parameter('oauth_token');
		$oauth_token_secret = $user_request->get_parameter('oauth_token_secret');
		$oauth_access_token = $this->create_access_token( $oauth_token_key, $oauth_token_secret );

		$signature_valid = $signature_method->check_signature(
			$user_request,
			$oauth_consumer,
			$oauth_access_token,
			$oauth_signature
		);


		return $signature_valid ? $user_request : null;
	}

	/**
	 * Use GREE Platform People API 
	 *
	 * @return  bool 
	 */
	public function people_api($parameters = array()){
		
		$template_parameters['guid']     = array_key_exists('guid',$parameters)     ? $parameters['guid']     : '@me';
		$template_parameters['selector'] = array_key_exists('selector',$parameters) ? $parameters['selector'] : '@self';
		$template_parameters['pid']      = array_key_exists('pid',$parameters)      ? $parameters['pid']      : '';
		
		$query_parameters['fields']      = array_key_exists('fields',$parameters)      ? $parameters['fields']      : '';
		$query_parameters['count']       = array_key_exists('count',$parameters)       ? $parameters['count']       : '';
		$query_parameters['startIndex']  = array_key_exists('startIndex',$parameters)  ? $parameters['startIndex']  : '';
		$query_parameters['filterBy']    = array_key_exists('filterBy',$parameters)    ? $parameters['filterBy']    : '';
		$query_parameters['filterOp']    = array_key_exists('filterOp',$parameters)    ? $parameters['filterOp']    : '';
		$query_parameters['filterValue'] = array_key_exists('filterValue',$parameters) ? $parameters['filterValue'] : '';
		$query_parameters['xoauth_requestor_id'] = array_key_exists('xoauth_requestor_id',$parameters) ? $parameters['xoauth_requestor_id'] : $this->get_user_id();

		$signature_method    = new OAuthSignatureMethod_HMAC_SHA1();
		$oauth_consumer     = new OAuthConsumer($this->config['consumer_key'],$this->config['consumer_secret']);
		$oauth_access_token = $this->create_access_token();

		$http_method         = 'GET';
		$endpoint_url        = $this->config['api_endpoint_url'] . '/people/'.$template_parameters['guid'].'/'.$template_parameters['selector'].'/'.$template_parameters['pid'];
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
			throw new \GreepfException('GREE Platform People API is show no reaction.', 1);
		}
	}
	

	/**
	 * Create OAuth access token 
	 *
	 * @return signature_method 
	 */
	protected function create_access_token( $oauth_token_key=null, $oauth_token_secret=null)
	{
		if( is_null($oauth_token_key) || is_null($oauth_token_secret) )
		{
			$oauth_token_key = \Session::get('oauth_token_key');
			$oauth_token_secret = \Session::get('oauth_token_secret');

			if( is_null($oauth_token_key) || is_null($oauth_token_secret) )
			{
				\Session::delete('oauth_access_token_key');
				\Session::delete('oauth_access_token_secret');
				return null;
			}
		}
		else
		{
			\Session::set('oauth_token_key', $oauth_token_key);
			\Session::set('oauth_token_secret', $oauth_token_secret);
		}

		return new OAuthToken($oauth_token_key, $oauth_token_secret);
	}



	/**
	 * Actural login check 
	 *
	 * @return bool
	 */
	public function check()
	{
		if( is_null(\Session::get('oauth_token_key')) || is_null(\Session::get('oauth_token_secret')) )
		{
			return false;
		}

		return $this->_check();
	}

	/**
	 * Login method 
	 *
	 * @return bool whether login succeeded 
	 */
	public function login()
	{
		return $this->_login();
	}

	/**
	 * Redirect after Login 
	 *
	 * @return string redirect url 
	 */
	public function login_redirect()
	{
		return $this->login()
			? $this->config['redirect_url_after_login']
			: $this->config['redirect_url_after_failed_login'];
	}

	/**
	 * Logout method 
	 */
	public function logout()
	{
		\Session::delete('oauth_access_token_key');
		\Session::delete('oauth_access_token_secret');
		return $this->_login();
	}

	/**
	 * Redirect after Logout
	 *
	 * @return string redirect url 
	 */
	public function logout_redirect()
	{
		$this->login();
		return $this->config['redirect_url_after_logout'];
	}

	/**
	 * Actural login check inside a child class
	 *
	 * @return bool
	 */
	abstract protected function _check();

	/**
	 * Login method Actural login check inside a child class
	 *
	 * @return bool whether login succeeded 
	 */
	abstract protected function _login();

	/**
	 * Logout method 
	 */
	abstract protected function _logout();

	/**
	 * @return string 
	 */
	abstract protected function get_user_id();

	/**
	 * @return string 
	 */
	abstract protected function get_user_difftime();

}

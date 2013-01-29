<?php
/**
 * Conection GREE Platform solution for FuelPHP
 *
 * @package		Greepf	
 * @version		1.0
 * @author		foostan	
 * @license		MIT License
 */


namespace Greepf;

class GreepfException extends \FuelException {}

abstract class Greepf {
	/**
	 * @var  array  $instances  greepf instances
	 */
	protected static $instances = array();

	/**
	 * @var  object  $instance  instance for singleton usage
	 */
	protected static $instance = null;

	/**
	 * Greepf instance factory. Returns a new greepf driver.
	 *
	 * @param       string    $auth      auth method.
	 * @param       array     $config    aditional config array
	 * @return      object    new greepf driver instance
	 * @deprecated  until 1.2
	 */
	public static function factory($auth = 'default', $config = array())
	{
		logger(\Fuel::L_WARNING, 'This method is deprecated.  Please use a forge() instead.', __METHOD__);
		return static::forge($auth, $config);
	}

	/**
	 * Greepf instance factory. Returns a new greepf driver.
	 *
	 * @param       string    $auth      auth method.
	 * @param   array   $config     aditional config array
	 * @return  object  new greepf driver instance
	 */
	public static function forge($auth = 'default', $config = array())
	{
		$key = $auth;
		empty($config) or $key.= md5(var_export($config, true));
		
		if(array_key_exists($key, static::$instances))
		{
			return static::$instances[$key];
		}
		
		$greepf_config = array();
		$greepf_config['consumer_key'] = \Config::get('greepf.consumer_key','');
		$greepf_config['consumer_secret'] = \Config::get('greepf.consumer_secret','');
		$greepf_config['api_endpoint_url'] = \Config::get('greepf.api_endpoint_url','');

		$greepf_config_auth = \Config::get('greepf.auth.'.$auth);
		if( ! is_array($greepf_config_auth))
		{
			throw new \InvalidGreepfException('Could not instantiate card: '.$auth);
		}
		
		$config += $greepf_config;
		$config += $greepf_config_auth;
		
		$driver = '\\Greepf_'.ucfirst($config['driver']);
		if( ! class_exists($driver, true))
		{
			throw new \InvalidGreepfException('Unknown greepf driver: '.$config['driver'].' ('.$driver.')');
		}
				
		$instance = new $driver($config);
		static::$instances[$key] =& $instance;

		return static::$instances[$key];
	}
	
	/**
	 * Resturns a greepf driver instance.
	 *
	 * @param       string    $auth      auth method.
	 * @param	array	$config		aditional config array
	 * @return	object	new greepf driver instance
	 */
	public static function instance($auth = null, $config = array())
	{
		$auth or $auth = \Config::get('greepf.default_auth', 'default');
		
		$key = $auth;
		empty($config) or $key.= md5(var_export($config, true));
		
		if(array_key_exists($key, static::$instances))
		{
			return static::$instances[$key];
		}
		return static::forge($auth, $config);
	}
	
	/**
	 * Method passthrough for static usage.
	 */
	public static function __callStatic($method, $args)
	{
		static::$instance or static::$instance = static::instance();
		
		if(method_exists(static::$instance, $method))
		{
			return call_user_func_array(array(static::$instance, $method), $args);
		}
		
		throw new \BadMethodCallException('Invalid method: '.get_called_class().'::'.$method);
	}

	/**
	 * Class init.
	 */
	public static function _init()
	{
		\Config::load('greepf', true);
	}
	
}

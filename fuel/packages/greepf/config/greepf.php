<?php
/**
 * Conection GREE Platform solution for FuelPHP
 *
 * @package		Greepf	
 * @version		1.0
 * @author		foostan	
 * @license		MIT License
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */

return array(
	'consumer_key'     => '',
	'consumer_secret'  => '',
	'api_endpoint_url' => '',

	'default_auth' => 'default',
	'auth' => array(
		'default' => array(
			'driver'          => 'greeauth',
			'db_table_name'   => 'greeusers',
			'login_hash_salt' => '',
			'redirect_url_after_login'        => '',
			'redirect_url_after_failed_login' => '',
			'redirect_url_after_logout'       => '',
		),
	),
);

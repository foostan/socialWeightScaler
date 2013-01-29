<?php
/**
 * Conection GREE Platform solution for FuelPHP
 *
 * @package		Greepf	
 * @version		1.0
 * @author		foostan	
 * @license		MIT License
 */


Autoloader::add_core_namespace('Greepf');

Autoloader::add_classes(array(
	'Greepf\\Greepf'			=> __DIR__.'/classes/greepf.php',
	'Greepf\\GreepfException'	=> __DIR__.'/classes/greepf.php',
	'Greepf\\Greepf_Driver'		=> __DIR__.'/classes/greepf/driver.php',
	'Greepf\\Greepf_Greeauth'	=> __DIR__.'/classes/greepf/greeauth.php',
));

<?php


Autoloader::add_core_namespace('Greepf');

Autoloader::add_classes(array(
	'Greepf\\Greepf'           => __DIR__.'/classes/greepf.php',
	'Greepf\\OAuthException'   => __DIR__.'/classes/vendor/oauth.php',
));



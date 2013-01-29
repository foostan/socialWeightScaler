<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

$j = json_decode(getenv('DB'));
$mysql = $j->{'mysql'}[0];

return array(
    'default' => array(
        'connection' => array(
            'dsn' => 'mysql:host='.$mysql->{'host'}.';dbname='.$mysql->{'name'},
            'username' => $mysql->{'username'},
            'password' => $mysql->{'password'},
        )
    )
);

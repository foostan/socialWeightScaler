<?php
/**
 * The development database settings. These get merged with the global settings.
 */

$j = json_decode(getenv('VCAP_SERVICES'));
$mysql = $j->{'mysql-5.1'}[0]->{'credentials'};

return array(
    'default' => array(
        'connect' => array(
            'dsn' => 'mysql:host='.$mysql->{'host'}.';dbname='.$mysql->{'name'},
            'username' => $mysql->{'username'},
            'password' => $mysql->{'password'},
        )
    )
);

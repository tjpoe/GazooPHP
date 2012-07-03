<?php

/* Single DB object connection
define( "DB_HOST", "localhost" );
define( "DB_USER", "username" );
define( "DB_PASS", "password" );
define( "DB_NAME", "database_name" );
*/

/* below is an example of a multi DB system */
$dbArray = array( 
    'db1' => array( 
        'user' => 'username',
        'pass' => 'password',
        'host' => 'hostname',
        'db' => 'database'
        ),
    'db2' => array( 
        'user' => 'username',
        'pass' => 'password',
        'host' => 'hostname',
        'db' => 'database'
        ),
    'db3' => array( 
        'user' => 'username',
        'pass' => 'password',
        'host' => 'hostname',
        'db' => 'database'
        )
    );


?>

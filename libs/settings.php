<?php

//Database
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'root');
define('MYSQL_PASS', '');
define('MYSQL_DB', 'rmpl');

//Facebook
$facebook = new Facebook(array(
  'appId'  => '325784244103665',
  'secret' => 'd24f8448fb0ceeb0a5c55d2efee6469f',
));

?>
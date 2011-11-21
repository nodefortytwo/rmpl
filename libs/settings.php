<?php

//Database
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'root');
define('MYSQL_PASS', '');
define('MYSQL_DB', 'rmpl');


//Facebook
define('FB_APPID', '325784244103665');
define('FB_SECRET', 'd24f8448fb0ceeb0a5c55d2efee6469f');
$facebook = new Facebook(array(
  'appId'  => FB_APPID,
  'secret' => FB_SECRET,
));

//Theme Stuff
define('HOST', 'localhost');
define('SITE_ROOT', 'rmpl');

?>
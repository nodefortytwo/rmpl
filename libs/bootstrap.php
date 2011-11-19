<?php
require('facebook/facebook.php');
require('settings.php');
require('misc.php');
require('db.php');
require('users.php');
require('media.php');

foreach (glob("libs/modules/*.php") as $filename)
{
	print($filename);
    require($filename);
}

// This request is either a clean URL, or 'index.php', or nonsense.
// Extract the path from REQUEST_URI.
$request_path = strtok($_SERVER['REQUEST_URI'], '?');
$base_path_len = strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/'));
// Unescape and strip $base_path prefix, leaving q without a leading slash.
$path = substr(urldecode($request_path), $base_path_len + 1);
// If the path equals the script filename, either because 'index.php' was
// explicitly provided in the URL, or because the server added it to
// $_SERVER['REQUEST_URI'] even when it wasn't provided in the URL (some
// versions of Microsoft IIS do this), the front page should be served.
if ($path == basename($_SERVER['PHP_SELF'])) {
  $path = '';
}



//init the db connection
$db = new Database();

//Create the user object
$user = new User($facebook);

if ($path == 'home'){
	$module = 'homepage';
	call_user_func($module.'_init');
}else{
	print('some other page');
}



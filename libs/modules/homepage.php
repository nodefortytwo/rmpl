<?php

function homepage_init(){
	print '<div class="grid_8 box homepage_box"><h1><a href="/'.SITE_ROOT.'/playlists/">View your playlists</a></h1></div>';
    print '<div class="grid_8 box homepage_box"><h1><a href="/'.SITE_ROOT.'/playlists/new">Create a Playlist</a></h1></div>';
}

function homepage_menu(){
    
    $menu = array();
    
    $menu['home'] = array(
        'callback' => 'homepage_init'
    );
    
    return $menu;
}

?>
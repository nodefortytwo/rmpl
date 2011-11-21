<?php

function playlist_init(){
    
}

function playlist_menu(){
    
    $menu = array();
    $menu['playlists/new']['callback'] = 'playlist_new';
    $menu['playlists']['callback'] = 'playlist_list';
    $menu['playlists/view']['callback'] = 'playlist_view';
    $menu['ajax/playlist/order/save']['callback'] = 'playlist_ajax_order_update';
    return $menu;
}

function playlist_ajax_order_update($plid){
    global $db;
    $media = $_GET['media'];
    
    foreach($media as $pos=>$id){
        $db->query('UPDATE join_playlist_media SET `order` = '.$pos.' WHERE `mid` = '.$id.' AND plid = '. $plid);
    }
    
    print json_encode(array('success'));
}

function playlist_view($id = null){
    if ($id === null){redirect('/rmpl/playlists');}
    
    $playlist = new Playlist($id);
    print ('<script>var playlist_id='.$playlist->plid.';</script>');
    print ('<div class="grid_16"><h1>' . $playlist->title . '</h1></div>');
    print ('<div class="grid_16 alpha omega">');
        print ('<div class="grid_8">');
            
            print ('<ul class="grid_16 alpha omega media_list">');
            foreach ($playlist->media as $media){
                print ('<li id="media-'.$media->mid.'">' . $media->title . '</li>');    
            }
            print ('</ul>');
        print ('</div>');
        print ('<div class="grid_8">');
            print ('<a href="javascript:void(0);" onclick="rmpl_next();">Next</a>');
            print (form_add_media($id));
            print ('<div id="play_window"></div>');
        print ('</div>');
    print ('</div>');
}

function playlist_list(){
    global $db;
    global $user;
    if ($user->uid == 0){
        print ('<div class="grid_16" style="text-align:center;"><h1>You need to login to view your playlists</h1></div>');
        return;
    }else{
        $db->query("SELECT plid FROM playlists WHERE uid = " . $user->uid);
        $results = $db->fetch_all();
        $playlists = array();
        print('<div class="grid_16 alpha omega">');
        foreach ($results as $row){
            $playlist = new Playlist($row['plid']);
            print('<div class="grid_8">');
                print('<h2><a href="/'.SITE_ROOT.'/playlists/view/~/'.$playlist->plid.'">' . $playlist->title . '</a></h2>');
            print('</div>');
        }
        print('</div>');
    }
    
}

function playlist_new(){
    global $user;
    if (!empty($_POST)){
        $playlist = new Playlist;
        $playlist->title = $_POST['title'];
        $playlist->creator = $user->uid;
        $playlist->save();
    }else{
        $output = '<div class="grid_4 prefix_5 suffix_5">';
        $output .= '<form actiom="./" method="post">';
        $output .= '<label for="title">Playlist Title</label>';
        $output .= '<input type="text" id="title" name="title"/>';
        $output .= '<input type="submit"/>';
        $output .= '</form>';
        $output .= '</div>';
        print $output;
    }
}

class Playlist {
    
    public $plid, $title, $creator, $created, $deleted, $media;
    
    public function __construct($plid = false) {
        if ($plid === false){
            $this->created = time();
            $this->deleted = 0;
            $this->updated = time();
        }else{
            $this->plid = $plid;
            $this->load();      
        }       
    }
    
    public function save(){
        global $db;
        if ($this->plid){
            $db->query('UPDATE playlists SET title = "'.$this->title.'", updated = '.$this->updated.' WHERE plid = ' . $this->plid);
        }else{
            $db->query('INSERT INTO playlists (title, uid, created, updated, deleted) VALUES ("'.$this->title.'", '.$this->creator.', '.$this->created.','.$this->updated.','.$this->deleted.')');
        }
    }    
    
    private function load(){
        global $db;
        
        $db->query('SELECT * FROM playlists WHERE plid = ' . $this->plid);
        $record = $db->fetch_all();
        
        $this->title = $record[0]['title'];
        $this->creator = $record[0]['uid'];
        $this->created = $record[0]['created'];
        $this->updated = $record[0]['updated'];
        $this->deleted = $record[0]['deleted'];
        
        $db->query('SELECT m.mid FROM media AS m 
                    INNER JOIN `join_playlist_media` AS jpm ON m.mid = jpm.mid
                    INNER JOIN playlists AS p ON p.plid = jpm.plid
                    WHERE p.plid = '.$this->plid.' ORDER BY jpm.order asc, jpm.jid asc;');
        $this->media = $db->fetch_all();
        foreach ($this->media as &$media){
          $media = new Media($media['mid']);  
        }
    }
    
    public function add_media($media){
        global $db;
        $db->query('SELECT * FROM join_playlist_media WHERE plid = ' . $this->plid . ' AND mid = ' . $media->mid);
        $result = $db->fetch_all();
        if (empty($result)){
            $this->tracks[] = $media;
            $db->query('INSERT INTO `join_playlist_media` (plid, `mid`, `order`) VALUES ('.$this->plid.', '.$media->mid.', 999);');
        }
    }
}

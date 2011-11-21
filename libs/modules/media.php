<?php

function media_menu(){
    $menu = array();
    
    $menu['ajax/media/load']['callback'] = 'media_load_ajax';
    
    return $menu;
}

function media_load_ajax($id){
    $media = new Media($id);
    print json_encode($media);
}

class Media {
	
	public $mid, $title, $url, $provider_name, $provider_url, $html, $added, $added_by, $deleted, $id, $error;
	
	public function __construct($mid) {
	
		//sometimes we pass in a url instead of the mid, this needs to either create a new media record or load the existing one
		if (!is_numeric($mid)){
			$this->mid = $this->create_media($mid);
		}else{
		    $this->mid = $mid;	
		}
        $this->load();

        if (empty($this->id)){
            $this->get_id();
            $this->save();
        }
        
        
	}
	
	private function load(){
		global $db;
		if (!is_numeric($this->mid)){
			$this->error[] = "mid provided is not numeric";
			exit;
		}
		
		$db->query('SELECT * FROM media WHERE mid = ' . $this->mid);
		$record = $db->fetch_all();
 
		foreach($record[0] as $fkey=>$field){
			try {
			    $this->$fkey = $field;
			} catch (Exception $e) {}
			
		}
	
	}
	
    private function save(){
        global $db;
        $db->query('UPDATE media SET 
        title = "'.$this->title.'", 
        url = "'.$this->url.'", 
        id = "'.$this->id.'", 
        provider_name = "'.$this->provider_name.'", 
        provider_url = "'.$this->provider_url.'", 
        html = "'.mysql_real_escape_string($this->html).'", 
        added = "'.$this->added.'", 
        added_by = "'.$this->added_by.'", 
        deleted = "'.$this->deleted.'"
        WHERE mid = ' . $this->mid);
    }
	
    private function get_id(){
        
        switch ($this->provider_name){
            case 'YouTube':
                $parsed = parse_url($this->url);
                $parsed['query'] = explode('&', $parsed['query']);
                foreach ($parsed['query'] as $key=>$query){
                    $tmp = explode('=',$query);
                    $parsed['query'][$tmp[0]] = $tmp[1];
                    unset($parsed['query'][$key]);
                }
                $this->id = $parsed['query']['v'];
            break;
            case 'Vimeo':
                $parsed = parse_url($this->url);
                $id = trim($parsed['path'], "/");
                $id = explode('/', $id);
                $this->id = $id[0];
            break;
        }
        
        
    }
    
	private function create_media($url){
		global $user;
		global $db;       
        if ($mid = media_exists($url)){
			return $mid;
		}else{
		  
    		$provider = rmpl_parse_url($url);
    		$request = $provider . "?url=" . $url . "&format=json";
    		$response = oembed_request($request);
			$db->query('INSERT INTO media (title,
			url, 
			provider_name, 
			provider_url, 
			html, 
			added, 
			added_by, 
			deleted
			)VALUES (
			"'.$response->title.'", 
			"'.$url.'", 
			"'.$response->provider_name.'",
			"'.$response->provider_url.'",
			"'.mysql_real_escape_string($response->html).'",
			'.time().',
			'.$user->uid.',
			0)');
			return media_exists($url);
		}
	
	}
}




function media_exists($url){
	global $db;
	
	$db->query('SELECT mid FROM media WHERE url = "' . $url . '"');
	$result = $db->fetch_all();

	if (empty($result)){
		return 0;
	}else{
		return $result[0]['mid'];
	}
	
}

function oembed_request($url){
 	global $cookie;
	$ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
    //curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    //curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    $content = curl_exec( $ch );
    $response = curl_getinfo( $ch );
    curl_close ( $ch );
	return json_decode($content);
}


function rmpl_parse_url($url){
	$url = parse_url($url);
	
	switch ($url['host']){
		case 'youtu.be':
		case 'youtube.com':
		case 'www.youtube.com':
			$provider = 'http://www.youtube.com/oembed';
		break;
		case 'soundcloud.com':
		case 'www.soundcloud.com':
			$provider = 'http://soundcloud.com/oembed';
		break;
        case 'vimeo.com':
        case 'www.vimeo.com':
            $provider = 'http://vimeo.com/api/oembed.json';
        break;
		default:
			$type = false;
		break;
	}
	return $provider;
}

function form_add_media($plid){
   
    if (!empty($_POST)){
        $media = new Media($_POST['url']);
        $playlist = new Playlist($plid);
        $playlist->add_media($media);
    }
    $output = '<h2>' . 'Add Media' . '</h2>';
    $output = '<form method="post">';
        $output .= '<label for="url">' . 'URL' . '</label>';
        $output .= '<input type="text" id="url" name="url"/>';
        $output .= '<input type="submit"/>';
        $output .= '<input type="hidden" id="plid" name="plid" value="'.$plid.'"/>';
    $output .= '</form>';
    return $output;
}

?>
<?php
class Media {
	
	public $mid, $title, $url, $provider, $provider_url, $html, $added, $added_by, $error;
	
	public function __construct($mid) {
	
		//sometimes we pass in a url instead of the mid, this needs to either create a new media record or load the existing one
		if (!is_numeric($mid)){
			$this->mid = $this->create_media($mid);
		}
			
		$this->load_media();
		
	}
	
	private function load_media(){
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
	
	
	private function create_media($url){
		global $user;
		global $db;
		$provider = rmpl_parse_url($url);
		$request = $provider . "?url=" . $url . "&format=json";
		$response = oembed_request($request);
		
		if ($mid = media_exists($url)){
			return $mid;
		}else{
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
		default:
			$type = false;
		break;
	}
	return $provider;
}

?>
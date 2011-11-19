<?php

function rmpl_parse_url($url){
	$url = parse_url($url);
	
	switch ($url['host']){
		case 'youtu.be':
		case 'youtube.com':
			$url['host'] = 'www.youtube.com';
		case 'www.youtube.com':
			$type = 'yt';
			$rm = rmpl_parse_youtube($url);
			$rm = rmpl_generate_youtube_embed($rm);
		break;
		default:
			$type = false;
		break;
	}
	
	print($rm);
}


function rmpl_parse_youtube($url){
	if (strpos($url['path'], 'embed')){
		$id = str_replace('/embed/', '', $url['path']);
	
	}elseif (strpos($url['path'], 'watch')){
		$url['query'] = rmpl_parse_querystring($url['query']);
		$id = $url['query']['v'];
	}else{
		$id = str_replace('/', '', $url['path']);
	}
	
	return $id;
}

function rmpl_generate_youtube_embed($id, $width=420, $height=315){
	
	$html = '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe>';
	return $html;
}

function rmpl_parse_querystring($qs){
	$qs = explode('&', $qs);
	$parts = array();
	foreach($qs as $part){
		$part = explode('=', $part);
		$parts[$part[0]] = $part[1];
	}
	return $parts;
}

?>
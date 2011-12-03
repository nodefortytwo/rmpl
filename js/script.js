/* Author: Rick Burgess

*/

var current;
var ytplayer;
var vplayer;
var scplayer;

$(document).ready(function(){
 $("#play_window").html("<-- Click a media link to start playing");
 
 $('.media_list').sortable();
 $('.media_list').disableSelection();
 $('.media_list').bind( "sortupdate", rmpl_playlist_update_order);
 
 $('.media_list li').bind('click', function() {
  rmpl_play($(this).attr('id'));
});
 
});

function rmpl_playlist_update_order(){
    var order = $('.media_list').sortable('serialize', 'id');
    $.ajax({
      url: "/rmpl/ajax/playlist/order/save/~/" + playlist_id,
      context: document.body,
      data: order,
      success: function(data){
        var result = eval('(' + data + ')');
        console.log(result);
      }
    });
    
}

function rmpl_next(){
    if (current){
        var id = $('#' + current).next().attr('id');
        if (id){
            rmpl_play(id);
        }else{
            id = $('#' + current).parent().children().first().attr('id');
            rmpl_play(id);
        }
    }else{
        id = $('.media_list').children().first().attr('id');
        rmpl_play(id);
    }
}

function rmpl_prev(){
    if (current){
        var id = $('#' + current).prev().attr('id');
        if (id){
            rmpl_play(id);
        }else{
            id = $('#' + current).parent().children().last().attr('id');
            rmpl_play(id);
        }
    }else{
        id = $('.media_list').children().last().attr('id');
        rmpl_play(id);
    }
}
 
function rmpl_play(id){
    //if we don't have an id check if we have a current video OR play the first one
    if (!id){
        if (current){
            id = current;
        }else{
            id = $('.media_list').children().first().attr('id'); 
        }
    }
    
    
    //check if the requested video is in the play window
    if ($('#'+id).hasClass('playing')){
        //check if the current video is paused
        if (is_paused(id)){
            //it is paused so we need to resume
            rmpl_resume();
            return;
        }else{
            //it is not paused so we do nothing
            return;
        }          
    }
    
    $('.playing').removeClass('playing');
    current = id;
    $('#' + current).addClass('playing');
    id = id.split('-');
    id = id[1];
    $.ajax({
      url: "/rmpl/ajax/media/load/~/" + id,
      context: document.body,
      success: function(data){
        var result = eval('(' + data + ')');
        switch(result.provider_name)
            {
            case 'YouTube':
              rmpl_play_youtube(result.id);
              break;
            case 'Vimeo':
              rmpl_play_vimeo(result.id);
              break;
            case 'Soundcloud':
              rmpl_play_soundcloud(result.id);
              break; 
            default:
              alert('no player found for :' + result.provider_name);
            }
      }
    });
    
}

function is_paused(id){
    if (current){
        if ($('#'+current).hasClass('YouTube')){
            if (ytplayer.getPlayerState() == 2){
                return true;
            }else{
                return false;
            }
        }else if ($('#'+current).hasClass('Vimeo')){
            return vplayer.api_paused();
        }
    }
}

function rmpl_pause(){
    
    if (current){
        if ($('#'+current).hasClass('YouTube')){
            rmpl_youtube_pause();
        }else if ($('#'+current).hasClass('Vimeo')){
            rmpl_vimeo_pause();
        }
    }
    
}

function rmpl_resume(){
    if (current){
        if ($('#'+current).hasClass('YouTube')){
            rmpl_youtube_resume();
        }else if ($('#'+current).hasClass('Vimeo')){
            rmpl_vimeo_resume();
        }
    } 
}

//YouTube
function rmpl_play_youtube(id){
   $("#play_window").html('<div id="yt_player" style="width:425px; height:356px; background-color:white;"></div>');
   
   var params = { allowScriptAccess: "always", allowfullscreen: 'true' };
   var atts = { id: "ytplayer" };
   
   swfobject.embedSWF("http://www.youtube.com/v/"+id+"?enablejsapi=1&playerapiid=ytplayer&version=3",
                       "yt_player", "425", "356", "8", null, null, params, atts);
}

function onYouTubePlayerReady(playerId) {      
    ytplayer = document.getElementById(playerId);
    ytplayer.addEventListener("onStateChange", "rmpl_youtube_state_change");
    ytplayer.playVideo();
}

function rmpl_youtube_state_change(newState){
    if (newState == 0){
        rmpl_next();
    }
}

function rmpl_youtube_pause(){
    ytplayer.pauseVideo();
}

function rmpl_youtube_resume(){
    ytplayer.playVideo();
}

//Vimeo
function rmpl_play_vimeo(id){
    $("#play_window").html('<div id="v_player" style="width:425px; height:356px; background-color:white;"></div>');
    
    var params = { allowScriptAccess: "always", flashvars: "api=1" };
   var atts = { id: "vplayer" };
   swfobject.embedSWF("http://vimeo.com/moogaloop.swf?clip_id="+id,
                       "v_player", "425", "356", "8", null, null, params, atts) 
}

function vimeo_player_loaded(){
    vplayer = document.getElementById("vplayer");
    vplayer.api_addEventListener('finish', 'rmpl_vimeo_finish');
    vplayer.api_play();
}

function rmpl_vimeo_finish(){
    rmpl_next();
}


function rmpl_vimeo_pause(){
    vplayer.api_pause();
}

function rmpl_vimeo_resume(){
    vplayer.api_play();
}

//Soundcloud
function rmpl_play_soundcloud(id){
    $("#play_window").html('<div id="sc_player" style="width:100%; height:356px;"></div>');
    var flashvars = {
      enable_api: true, 
      object_id: "scPlayer",
      url: id
    };
    
    var params = {
      allowscriptaccess: "always"
    };
    
    var attributes = {
      id: "scPlayer",
      name: "scPlayer"
    };
    
    swfobject.embedSWF("http://player.soundcloud.com/player.swf", "sc_player", "100%", "100%", "9.0.0","expressInstall.swf", flashvars, params, attributes);
    
    scplayer = soundcloud.getPlayer('scPlayer');
    scplayer.api_play();    
}


/* Author: Rick Burgess

*/

var current;

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
    var id = $('#' + current).next().attr('id');
    if (id){
        rmpl_play(id);
    }else{
        id = $('#' + current).parent().children().first().attr('id');
        rmpl_play(id);
    }
}
 
function rmpl_play(id){
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
            default:
              alert('no player found for :' + result.provider_name);
            }
      }
    });
    
}

//YouTube
function rmpl_play_youtube(id){
   $("#play_window").html('<div id="yt_player" style="width:425px; height:356px; background-color:white;"></div>');
   
   var params = { allowScriptAccess: "always" };
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

//Vimeo
function rmpl_play_vimeo(id){
    $("#play_window").html('<div id="v_player" style="width:425px; height:356px; background-color:white;"></div>');
    
    var params = { allowScriptAccess: "always", flashvars: "api=1" };
   var atts = { id: "vplayer" };
   swfobject.embedSWF("http://vimeo.com/moogaloop.swf?clip_id="+id,
                       "v_player", "425", "356", "8", null, null, params, atts) 
}

function vimeo_player_loaded(){
    var vplayer = document.getElementById("vplayer");
    vplayer.api_addEventListener('finish', 'rmpl_vimeo_finish');
    vplayer.api_play();
}

function rmpl_vimeo_finish(){
    rmpl_next();
}

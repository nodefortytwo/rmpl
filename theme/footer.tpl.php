
<div class="grid_16" id="footer">
    <p>&copy; nodefortytwo</p>
</div>

</div>

  <!-- JavaScript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.6.2.min.js"><\/script>')</script>
  <script src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
    
  <!-- Soundcloud Stuff -->
  <script src="http://connect.soundcloud.com/sdk.js" type="text/JavaScript"></script>
  
  
  <!-- scripts concatenated and minified via ant build script-->
  <script defer src="/<?php print SITE_ROOT;?>/js/plugins.js"></script>
  <script defer src="/<?php print SITE_ROOT;?>/js/script.js"></script>
  <script defer src="/<?php print SITE_ROOT;?>/js/soundcloud.player.api.js"></script>
  <!-- end scripts-->
    
	
  <!-- Change UA-XXXXX-X to be your site's ID -->
  <script>
    window._gaq = [['_setAccount','UAXXXXXXXX1'],['_trackPageview'],['_trackPageLoadTime']];
    Modernizr.load({
      load: ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js'
    });
  </script>


  <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
       chromium.org/developers/how-tos/chrome-frame-getting-started -->
  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
  
</body>
</html>
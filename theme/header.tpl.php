<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/b/378 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title></title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

  <!-- CSS: implied media=all -->
  <!-- CSS concatenated and minified via ant build script-->
  <link href='http://fonts.googleapis.com/css?family=Play' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="/<?php print SITE_ROOT;?>/css/style.css">
  <link rel="stylesheet" type="text/css" href="/<?php print SITE_ROOT;?>/css/960/reset.css" media="screen" />
  <link rel="stylesheet" type="text/css" href="/<?php print SITE_ROOT;?>/css/960/text.css" media="screen" />
  <link rel="stylesheet" type="text/css" href="/<?php print SITE_ROOT;?>/css/960/grid.css" media="screen" />
  <link rel="stylesheet" href="/<?php print SITE_ROOT;?>/css/core.css">
  <link rel="stylesheet" href="../css/sc-player-standard.css" type="text/css">
  <!-- end CSS-->

  <!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

  <!-- All JavaScript at the bottom, except for Modernizr / Respond.
       Modernizr enables HTML5 elements & feature detects; Respond is a polyfill for min/max-width CSS3 Media Queries
       For optimal performance, use a custom Modernizr build: www.modernizr.com/download/ -->
  <script src="/<?php print SITE_ROOT;?>/js/libs/modernizr-2.0.6.min.js"></script>
</head>

<body>
<div class="container_16">
    <div class="grid_16" id="banner">
        <div class="grid_10">
        <h1 id="branding">
        	<a href="/<?php print SITE_ROOT;?>">RichMediaPlayList</a>
        </h1>
        </div>
        <div class="grid_5 prefix_1 box" id="user_box">
            <?php print $user->theme_user_box(); ?>
        </div>
    </div>
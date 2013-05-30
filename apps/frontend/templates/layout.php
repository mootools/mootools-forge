<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="robots" content="all" />
	
	<!-- Section Specific -->
	
	<link rel="alternate" type="application/atom+xml" title="Atom 1.0 &mdash; Plugins" href="<?php echo url_for('recentfeed', array('format' => 'atom1'), array('absolute' => true)) ?>" />
	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0 &mdash; Plugins" href="<?php echo url_for('recentfeed', array('format' => 'rss201'), array('absolute' => true)) ?>" />	
	
	<link rel="alternate" type="text/xml" title="RSS .91 &mdash; Plugins" href="<?php echo url_for('recentfeed', array('format' => 'rss091'), array('absolute' => true)) ?>" />	
	
	<?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_title() ?>	
  <?php include_javascripts() ?>
  <?php include_stylesheets() ?>

<script type="text/javascript">/*<![CDATA[*/

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-1122274-8'], ['_trackPageview']);

	(function(){
		var ga = document.createElement('script');
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		ga.setAttribute('async', 'true');
		document.documentElement.firstChild.appendChild(ga);
	})();

//]]></script>
</head>
<body>
	
	<div id="header">	
		<div class="container">
			<a id="mediatemple" href="http://mediatemple.net">
				<span>in partnership with mediatemple</span>
			</a>
			<div id="logo">
				<h1><a href="/"><span>MooTools</span></a></h1>
				<h2><span>a plugins repository</span></h2>
			</div>
			<div id="navigation">
				<a href="/" class="first">Home</a>
				<a href="/download">Download</a>
				<a href="/docs">Docs</a>
				<a href="/forge/">Forge</a>
				<a href="/blog">Blog</a>
				<a href="/demos">Demos</a>
			</div>
		</div>	
	</div>	

	<div id="wrapper">
		<div id="container" class="container forge">
			<div id="main" class="span-18 colborder">
				<?php if ($sf_user->hasFlash('notice')): ?>
			  <div class="notice"><?php echo $sf_user->getFlash('notice') ?></div>
				<?php endif; ?>
				
				<?php echo $sf_content ?>		
			</div>
			
			<!-- Sidebar -->
			<div id="sidebar" class="span-5 last">
				<?php include_component('default', 'sidebar') ?>
			</div>
			
		</div>	
	</div>

	<div id="footer">
		<div class="container">
			<p class="copy"><a href="http://mad4milk.net" id="mucca"></a></p>
			<p>copyright ©2006-2012 <a href="http://mad4milk.net">Valerio Proietti</a></p>
		</div>
	</div>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
	<?php include_http_metas() ?>
	<?php include_metas() ?>
	<?php include_title() ?>

	<link rel="alternate" type="application/atom+xml" title="Atom 1.0 &mdash; Plugins" href="<?php echo url_for('recentfeed', array('format' => 'atom1'), array('absolute' => true)) ?>" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0 &mdash; Plugins" href="<?php echo url_for('recentfeed', array('format' => 'rss201'), array('absolute' => true)) ?>" />
	<link rel="alternate" type="text/xml" title="RSS .91 &mdash; Plugins" href="<?php echo url_for('recentfeed', array('format' => 'rss091'), array('absolute' => true)) ?>" />

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
<body class="forge">
	<div id="global-bar" class="clearfix">
		<div class="clearfix wrapper">
			<div id="logo">
				<p><a href="/"><img src="/images/logo/mootools.png" alt="MooTools Home"></a></p>
			</div>
			<div class="right clearfix">
				<ul>
					<li><a href="/blog">Blog</a></li>
					<li><a href="http://github.com/mootools">Contribute</a></li>
					<li><a href="<?php echo url_for('@homepage') ?>">Forge</a></li>
				</ul>
				<form id="search" role="search" method="get" action="/">
					<label for="search-field">
						<span aria-hidden="true" class="icon search"></span>
						<input id="search-field" type="search" placeholder="Search in the site">
					</label>
				</form>
			</div>
		</div>
	</div>
	<hr>

	<div role="main" class="main clearfix">
		<div class="overview clearfix wrapper">
			<div id="main" class="span-18 colborder">
				<?php if ($sf_user->hasFlash('notice')): ?>
					<div class="notice"><?php echo $sf_user->getFlash('notice') ?></div>
				<?php endif; ?>
				<?php echo $sf_content ?>
			</div>

			<div id="sidebar" class="span-5 last">
				<?php include_component('default', 'sidebar') ?>
			</div>
		</div>
	</div>

	<footer id="footer" class="clearfix wrapper">
		<nav id="sitemap" role="navigation">
			<div>
				<h3>About<span aria-hidden="true" class="slider"></span></h3>
				<ul>
					<li><a href="/blog">Blog</a></li>
					<li><a href="#">Books</a></li>
					<li><a href="#">Developers</a></li>
					<li><a href="#">Merchandising</a></li>
				</ul>
			</div>
			<div>
				<h3>Support<span aria-hidden="true" class="slider"></span></h3>
				<ul>
					<li><a href="irc://irc.freenode.net/#mootools">IRC Channel</a></li>
					<li><a href="http://groups.google.com/group/mootools-users">User Group</a></li>
					<li><a href="http://mootorial.com">The MooTorial</a></li>
				</ul>
			</div>
			<div>
				<h3>Connect with us<span aria-hidden="true" class="slider"></span></h3>
				<ul>
					<li><a href="http://github.com/mootools"><span aria-hidden="true" class="icon github"></span>GitHub</a></li>
					<li><a href="http://twitter.com/mootools"><span aria-hidden="true" class="icon twitter"></span>Twitter</a></li>
					<li><a href="#"><span aria-hidden="true" class="icon facebook"></span>Facebook</a></li>
					<li><a href="#"><span aria-hidden="true" class="icon googleplus"></span>Google+</a></li>
				</ul>
			</div>
		</nav>
		<div id="credits" role="contentinfo">
			<p><a href="/"><img class="mootools" src="/images/logo/mootools.png" alt="MooTools"></a></p>
			<p>Copyright © 2006-2013<br>Valerio Proietti & MooTools Developers</p>
			<p><a href="http://mediatemple.net"><img src="/images/credits/mediatemple.png" alt="In partnership with Media Temple"></a></p>
		</div>
	</footer>
</body>
</html>

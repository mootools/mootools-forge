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

	<link rel="shortcut icon" type="image/x-icon" href="/images/favicon/mootools.ico">
	<link rel="icon" type="image/png" href="/images/favicon/mootools.png">

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
			<div id="main-header" class="right clearfix">
				<ul>
					<li><a href="/core/">Core</a></li>
					<li><a href="/more/"> More</a></li>
					<li><a href="/blog">Blog</a></li>
					<li class="selected"><a href="<?php echo url_for('@homepage') ?>">Forge</a></li>
					<li><a href="http://github.com/mootools">Contribute</a></li>
				</ul>
				<form id="search" role="search" method="get" action="/search">
					<label for="search-field">
						<span aria-hidden="true" class="icon search"></span>
						<input id="search-field" name="q" type="search" placeholder="Search in the site">
					</label>
				</form>
			</div>
		</div>
	</div>
	<hr>
	<header id="header" role="banner"></header>

	<div role="main" class="main clearfix">
		<div class="clearfix wrapper">
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

	<hr>
	<footer id="footer" class="clearfix wrapper">
		<nav id="sitemap" role="navigation">
			<div>
				<h3>About<span aria-hidden="true" class="slider"></span></h3>
				<ul>
					<li><a href="/blog">Blog</a></li>
					<li><a href="/books">Books</a></li>
					<li><a href="/developers">Developers</a></li>
					<li><a href="http://mad4milk.spreadshirt.net/" target="_blank">Merchandising</a></li>
				</ul>
			</div>
			<div>
				<h3>Support<span aria-hidden="true" class="slider"></span></h3>
				<ul>
					<li><a href="irc://irc.freenode.net/#mootools" target="_blank">IRC Channel</a></li>
					<li><a href="http://groups.google.com/group/mootools-users" target="_blank">User Group</a></li>
					<li><a href="http://mootorial.com" target="_blank">The MooTorial</a></li>
					<li><a href="http://stackoverflow.com/questions/tagged/mootools" target="_blank">Stack Overflow</a></li>
				</ul>
			</div>
			<div>
				<h3>Connect with us<span aria-hidden="true" class="slider"></span></h3>
				<ul>
					<li><a href="http://github.com/mootools" target="_blank"><span aria-hidden="true" class="icon github"></span>GitHub</a></li>
					<li><a href="http://twitter.com/mootools" target="_blank"><span aria-hidden="true" class="icon twitter"></span>Twitter</a></li>
					<li><a href="https://www.facebook.com/mootools" target="_blank"><span aria-hidden="true" class="icon facebook"></span>Facebook</a></li>
					<li><a href="https://plus.google.com/u/0/117731876964599459921/posts" target="_blank"><span aria-hidden="true" class="icon googleplus"></span>Google+</a></li>
				</ul>
			</div>
			<div>
				<h3>Documentation<span aria-hidden="true" class="slider"></span></h3>
				<ul>
					<li><a href="/core/docs">MooTools Core</a></li>
					<li><a href="/more/docs">MooTools More</a></li>
				</ul>
			</div>
		</nav>
		<div id="credits" role="contentinfo">
			<p><a href="/"><img src="/images/logo/mootools.png" alt="MooTools" class="mootools"></a></p>
			<p>Copyright © 2006-2014<br />Valerio Proietti &amp; MooTools Developers</p>
			<p class="mediatemple"><a href="http://mediatemple.net">Web Hosting by Media Temple</a></p>
		</div>
	</footer>
</body>
</html>

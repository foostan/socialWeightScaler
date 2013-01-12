<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>FuelPHP Framework</title>
	<?php echo Asset::css('themes/default/jquery.mobile-1.2.0.css'); ?>
	<?php echo Asset::js('jquery.js'); ?>
	<?php echo Asset::js('jquery.mobile-1.2.0.js'); ?>
</head>
<div data-role="page" class="type-home">
	<div data-role="content">
		<div class="content-secondary">
			<p class="intro"><strong>Welcome.</strong> jQuery Mobile is the easiest way to build sites and apps that are accessible on all popular smartphone, tablet and desktop devices. For jQuery 1.7.0 to 1.8.2.</p>

			<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="f">
				<li data-role="list-divider">Overview</li>
				<li><a href="docs/about/intro.html">Intro to jQuery Mobile</a></li>
				<li><a href="docs/about/getting-started.html">Quick start guide</a></li>
				<li><a href="docs/about/features.html">Features</a></li>
				<li><a href="docs/about/accessibility.html">Accessibility</a></li>
				<li><a href="docs/about/platforms.html">Supported platforms</a></li>
			</ul>

		</div><!--/content-primary-->

		<div class="content-primary">
			<nav>


				<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
					<li data-role="list-divider">Components</li>
					<li><a href="docs/pages/index.html">Pages &amp; dialogs</a></li>
					<li><a href="docs/toolbars/index.html">Toolbars</a></li>
					<li><a href="docs/buttons/index.html">Buttons</a></li>
					<li><a href="docs/content/index.html">Content formatting</a></li>
					<li><a href="docs/forms/index.html">Form elements</a></li>
					<li><a href="docs/lists/index.html">Listviews</a></li>

					<li data-role="list-divider">API</li>
					<li><a href="docs/api/globalconfig.html">Configuring defaults</a></li>
					<li><a href="docs/api/events.html">Events</a></li>
					<li><a href="docs/api/methods.html">Methods &amp; Utilities</a></li>
					<li><a href="docs/api/data-attributes.html">Data attribute reference</a></li>
					<li><a href="docs/api/themes.html">Theme framework</a></li>


				</ul>
			</nav>
		</div>



	</div>

	<div data-role="footer" class="footer-docs" data-theme="c">
            <p class="jqm-version"></p>
			<p>&copy; 2012 jQuery Foundation and other contributors</p>
	</div>

</div>
</html>

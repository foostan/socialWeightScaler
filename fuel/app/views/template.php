<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>Social Weight Scaler</title>
	<?php echo Asset::css('themes/default/jquery.mobile-1.2.0.css'); ?>
	<?php echo Asset::css('weloveiconfonts.css'); ?>
	<script type="text/javascript" src="http://pf-sb.gree.net/js/app/touch.js"></script>
	<?php echo Asset::js('jquery.js'); ?>
	<?php echo Asset::js('jquery.mobile-1.2.0.js'); ?>
	<script>
		$.mobile.ajaxEnabled = false;
	</script>
</head>

<body>
	<div data-role="page">
		<div data-role="header" data-theme="a">
			<div data-role="navbar" data-iconpos="top">
				<ul>
					<li><a href="<?php echo Uri::create('wslog/add') ?>" data-icon="gear"  data-rel="dialog">Get on the scale!</a></li>
				</ul>
			</div>
		</div>
		<div data-role="header" data-theme="c">
			<?php echo $header ?>
		</div>

		<div data-role="content">
			<?php echo $contents ?>
		</div>
	</div>
</body>
</html>

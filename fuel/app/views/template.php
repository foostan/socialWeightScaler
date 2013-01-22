<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Social Weight Scaler</title>
	<?php echo Asset::css('themes/default/jquery.mobile-1.2.0.css'); ?>
	<script type="text/javascript" src="http://pf-sb.gree.net/js/app/touch.js"></script>
</head>

<body>
	<div data-role="page" class="type-home">

		<div data-role="header">
			<?php echo $header ?>
		</div>

		<div data-role="content">
			<?php echo $contents ?>
		</div>
	</div>
	<?php echo Asset::js('jquery.js'); ?>
	<?php //echo Asset::js('jquery.mobile-1.2.0.js'); ?>
</body>
</html>

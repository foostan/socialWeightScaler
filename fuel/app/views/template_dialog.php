<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>Social Weight Scaler</title>
	<?php echo Asset::css('themes/default/jquery.mobile-1.2.0.css'); ?>
	<script type="text/javascript" src="http://pf-sb.gree.net/js/app/touch.js"></script>
	<?php echo Asset::js('jquery.js'); ?>
	<?php echo Asset::js('jquery.mobile-1.2.0.js'); ?>
	<script>
		$.mobile.ajaxEnabled = false;
	</script>
</head>

<body>
	<div data-role="dialog" data-thema="a">
		<div data-role="content" data-theme="c">
			<div class="content-primary"></div>
		</div>
		<div data-role="content" data-thema="a">
			<?php echo $contents ?>
		</div>
	</div>
</body>
</html>



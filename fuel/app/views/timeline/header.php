

<div data-role="navbar" data-iconpos="bottom">
	<ul>
		<li><a href="<?php echo Uri::create('timeline/public'); ?>" <?php if($scope==='public'){ ?>class="ui-btn-active ui-state-persist"<?php } ?>>Public timeline</a></li>
		<li><a href="<?php echo Uri::create('timeline/private'); ?>" <?php if($scope==='private'){ ?>class="ui-btn-active ui-state-persist"<?php } ?>>Private timeline</a></li>
	</ul>
</div>



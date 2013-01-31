<div class="content-primary">
	<h3></h3>

	<ul data-role="listview" data-split-icon="delete">
		<?php foreach($wslogs as $wslog){ ?>
		<li data-theme="c">
			<a href="#">
				<!-- <img src="" class="ui-li-thumb" /> -->
				<h3 class="ui-li-heading">Weight: <?php echo $wslog->generate_diff_msg($wslog->weight_diff,'kg'); ?></h3>
				<h3 class="ui-li-heading">Body fat: <?php echo $wslog->generate_diff_msg($wslog->body_fat_diff,'%'); ?></h3>
				<p class="ui-li-desc">Weight: <?php echo $wslog->weight; ?> kg / Body fat: <?php echo $wslog->body_fat; ?> %</p>
				<p class="ui-li-desc">
					<span class="entypo-comment"></span> <?php echo $wslog->comments; ?>
				</p>
				<p class="ui-li-desc">
					at <?php echo date_format(date_create_from_format('U', $wslog->measured_at+Greepf::get_user_difftime()), 'M. d, Y - H'); ?>
				</p>
				<p class="ui-li-desc">
					Good! <span class="fontawesome-thumbs-up"></span> - <?php echo $wslog->how_many_good('good'); ?> / No good! <span class="fontawesome-thumbs-down"></span> - <?php echo $wslog->how_many_good('nogood'); ?>
				</p>
			</a>
			<a href="<?php echo Uri::create('wslog/delete/'.$wslog->id) ?>" data-theme="c"></a>

		</li>
		<?php } ?>
	</ul>
</div>

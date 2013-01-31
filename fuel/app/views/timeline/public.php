<div class="content-primary">
	<h3></h3>

	<ul data-role="listview">
		<?php foreach($wslogs as $wslog){ ?>
		<li data-theme="c">
				<!-- <img src="" class="ui-li-thumb" /> -->
				<h3 class="ui-li-heading">Weight: <?php echo $wslog->generate_diff_msg($wslog->weight_diff,'kg'); ?></h3>
				<h3 class="ui-li-heading">Body fat: <?php echo $wslog->generate_diff_msg($wslog->body_fat_diff,'%'); ?></h3>
				<?php if($wslog->share_with_everyone_is){ ?>
					<p class="ui-li-desc">Weight: <?php echo $wslog->weight; ?> kg / Body fat: <?php echo $wslog->body_fat; ?> %</p>
					<p class="ui-li-desc">
						<span class="entypo-comment"></span> <?php echo $wslog->comments; ?>
					</p>
				<?php }?>
				<p class="ui-li-heading">
					<?php if($wslog->share_with_everyone_is){ ?>
						<img src="<?php echo $wslog->user->thumbnail_url_small; ?>" />
						<?php echo $wslog->user->display_name; ?>
					<?php }?>

					at <?php echo date_format(date_create_from_format('U', $wslog->measured_at+Greepf::get_user_difftime()), 'M. d, Y - a'); ?>
				</p>



				<div data-role="controlgroup" data-type="horizontal">
					<a href="" data-role="button" data-mini="true">Good! <span class="fontawesome-thumbs-up"></span> - 0</a>
					<a href="" data-role="button" data-mini="true">No good! <span class="fontawesome-thumbs-down"></span> - 0</a>
				</div>


		<?php } ?>
	</ul>

</div>

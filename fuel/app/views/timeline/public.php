<div class="content-primary">
	<h3></h3>

	<ul data-role="listview">
		<?php foreach($wslogs as $wslog){ ?>
		<li data-theme="c">
				<!-- <img src="" class="ui-li-thumb"> -->
				<h3 class="ui-li-heading"><?php echo $wslog->user_id; ?></h3>
				<p class="ui-li-desc">Weight: <?php echo $wslog->weight; ?></p>
				<p class="ui-li-desc">Body fat: <?php echo $wslog->body_fat; ?></p>
				<p class="ui-li-desc">measured_at: <?php echo Date::forge($wslog->measured_at+Greepf::get_user_difftime())->format("%m/%d/%Y %H:%M"); ?></p>
				<p class="ui-li-desc">Comments: <?php echo $wslog->comments; ?></p>
		</li>
		<?php } ?>
	</ul>

</div>

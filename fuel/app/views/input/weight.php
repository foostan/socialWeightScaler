<div class="content-primary">	

<?php
	$today = Date::forge(Date::time()->get_timestamp()+Greepf::get_user_difftime());
	$month = $today->format("%m"); 
	$day = $today->format("%d"); 
	$year = $today->format("%Y"); 
	$hour = $today->format("%H"); 

	$month_strs = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

	$last_weight = isset($last_wslog->weight) ? $last_wslog->weight : '50';
	$last_body_fat = isset($last_wslog->body_fat) ? $last_wslog->body_fat : '20';
	$last_share_with_friends_is = isset($last_wslog->share_with_friends_is) ? $last_wslog->share_with_friends_is : '0';
	$last_share_with_everyone_is = isset($last_wslog->share_with_everyone_is) ? $last_wslog->share_with_everyone_is : '0';

?>
<form action="<?php echo Uri::create('wslog/add') ?>" method="post">
	<ul data-role="listview">

		<li data-role="fieldcontain">
		    <fieldset data-role="controlgroup" data-type="horizontal">
		        <legend>Date:</legend>
		    
		        <label for="select-choice-month">Month</label>
		        <select name="select-choice-month" id="select-choice-month" data-mini="true">
					<?php for($i=1; $i<13; $i++){ 
		            	echo '<option value="'.sprintf('%02d',$i).'"'.($i==$month?' selected ':'').'>'.$month_strs[$i-1].'</option>';
					 } ?>
		        </select>
		    
		        <label for="select-choice-day">Day</label>
		        <select name="select-choice-day" id="select-choice-day" data-mini="true">
					<?php for($i=1; $i<32; $i++){ 
		            	echo '<option value="'.sprintf('%02d',$i).'"'.($i==$day?' selected ':'').'>'.sprintf('%02d',$i).'</option>';
					 } ?>
		        </select>
		    
		        <label for="select-choice-year">Year</label>
		        <select name="select-choice-year" id="select-choice-year" data-mini="true">
					<?php for($i=$year-2; $i<$year+1; $i++){ 
		            	echo '<option value="'.sprintf('%02d',$i).'"'.($i==$year?' selected ':'').'>'.sprintf('%02d',$i).'</option>';
					 } ?>
		        </select>
		    </fieldset>

		        
		</li>
		<li>
			<fieldset class="ui-grid-a">
				<div class="ui-block-a">
					<label for="select-choice-hour">Hour:</label>
					<select name="select-choice-hour" id="select-choice-hour" data-mini="true">
						<?php for($i=0; $i<24; $i++){ 
					    	echo '<option value="'.sprintf('%02d',$i).'"'.($i==$hour?' selected ':'').'>'.sprintf('%02d',$i).'</option>';
						 } ?>
					</select>
				</div>
			</fieldset>
		</li>

		<li data-role="fieldcontain">
			<label for="weight">Weight:</label>
			<input type="text" name="weight" id="weight" value="<?php echo $last_weight ; ?>"  />
		</li>
		<li data-role="fieldcontain">
			<label for="body-fat">Body fat:</label>
			<input type="text" name="body-fat" id="body-fat" value="<?php echo $last_body_fat; ?>"  />
		</li>
		<li data-role="fieldcontain">
			<label for="comments">Comments:</label>
			<input type="text" name="comments" id="comments" value=""  />
		</li>

<!--
		<li data-role="fieldcontain">
			<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
				<legend>Share with my friends:</legend>
				<select name="share-with-friends-is" id="share-with-friends-is" data-role="slider">
					<option value="0" <?php if($last_share_with_friends_is == '0') echo 'selected' ?>>Off</option>
					<option value="1" <?php if($last_share_with_friends_is == '1') echo 'selected' ?>>On</option>
				</select> 
			</fieldset>
		</li>
-->
		<input type="hidden" name="share-with-friends-is" value="0" />

		<li data-role="fieldcontain">
			<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
				<legend>Share with everyone:</legend>
				<select name="share-with-everyone-is" id="share-with-everyone-is" data-role="slider">
					<option value="0" <?php if($last_share_with_everyone_is == '0') echo 'selected' ?>>Off</option>
					<option value="1" <?php if($last_share_with_everyone_is == '1') echo 'selected' ?>>On</option>
				</select> 
			</fieldset>
		</li>

		<li>
			<fieldset class="ui-grid-a">
				<div class="ui-block-a">
					<a href="<?php echo Uri::create('timeline/public') ?>" data-role="button" data-theme="d" data-icon="delete">Cansel</a>
				</div>
				<div class="ui-block-b">
					<button type="submit" data-theme="b" data-icon="check">Submit</button>
				</div>
			</fieldset>
		</li>
	</ul>

</form>

</div><!--/content-primary -->		

<div class="content-primary">	


<?php
	$today = Date::forge(Date::time()->get_timestamp()+\Session::get('timezone')*60);
	$month = $today->format("%m"); 
	$day = $today->format("%d"); 
	$year = $today->format("%Y"); 
	$hour = $today->format("%H"); 

	$month_strs = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

?>
<form action="<?php echo Uri::create('input/weight') ?>" method="post">
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
			<label for="weight">Weight(kg):</label>
			<input type="text" name="weight" id="weight" value="50.5"  />
		</li>
		<li data-role="fieldcontain">
			<label for="body-fat">Body fat(%):</label>
			<input type="text" name="body-fat" id="body-fat" value="12.4"  />
		</li>
		<li data-role="fieldcontain">
			<label for="comments">Comments:</label>
			<input type="text" name="comments" id="comments" value=""  />
		</li>

		<li data-role="fieldcontain">
			<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
				<legend>Share with my friends:</legend>
				<select name="share-with-friends-is" id="share-with-friends-is" data-role="slider">
					<option value="0">Off</option>
					<option value="1">On</option>
				</select> 
			</fieldset>
		</li>
		<li data-role="fieldcontain">
			<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
				<legend>Share with everyone:</legend>
				<select name="share-with-everyone-is" id="share-with-everyone-is" data-role="slider">
					<option value="0">Off</option>
					<option value="1">On</option>
				</select> 
			</fieldset>
		</li>

		<li>
			<fieldset class="ui-grid-a">
				<div class="ui-block-a">
					<button type="submit" name="submit" value="cansel" data-theme="d" data-icon="delete">Cansel</button>
				</div>
				<div class="ui-block-b">
					<button type="submit" name="submit" value="submit" data-theme="b" data-icon="check">Submit</button>
				</div>
			</fieldset>
		</li>
	</ul>

</form>

</div><!--/content-primary -->		

<?php /* Smarty version 2.6.11, created on 2013-12-19 18:15:14
         compiled from modules/Calendar/tpls/settings.tpl */ ?>

<script type="text/javascript">
<?php echo '
function toggleDisplayTimeslots() {
	if (document.getElementById(\'display_timeslots\').checked) {
		$(".time_range_options_row").css(\'display\', \'\');
	} else {
		$(".time_range_options_row").css(\'display\', \'none\');
	}
}

$(function() {
	toggleDisplayTimeslots();
});

'; ?>

</script>

<div id="settings_dialog" style="width: 450px; display: none;">
	<div class="hd"><?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS_TITLE']; ?>
</div>
	<div class="bd">
	<form name="settings" id="form_settings" method="POST" action="index.php?module=Calendar&action=SaveSettings">
		<input type="hidden" name="view" value="<?php echo $this->_tpl_vars['view']; ?>
">
		<input type="hidden" name="day" value="<?php echo $this->_tpl_vars['day']; ?>
">
		<input type="hidden" name="month" value="<?php echo $this->_tpl_vars['month']; ?>
">
		<input type="hidden" name="year" value="<?php echo $this->_tpl_vars['year']; ?>
">
		
		<table class='edit view tabForm'>
				<tr>
					<td scope="row" valign="top" width="55%">
						<?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS_DISPLAY_TIMESLOTS']; ?>

					</td>
					<td width="45%">	
						<input type="hidden" name="display_timeslots" value="">
						<input type="checkbox" id="display_timeslots" name="display_timeslots" <?php if ($this->_tpl_vars['display_timeslots']): ?>checked<?php endif; ?> value="1" tabindex="102" onchange="toggleDisplayTimeslots();">
					</td>
				</tr>
				<tr class="time_range_options_row">
					<td scope="row" valign="top">
						<?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS_TIME_STARTS']; ?>

					</td>
					<td>
						<div id="d_start_time_section">
							<select size="1" id="day_start_hours" name="day_start_hours" tabindex="102">
								<?php echo $this->_tpl_vars['TIME_START_HOUR_OPTIONS']; ?>

							</select>&nbsp;:
							
							<select size="1" id="day_start_minutes" name="day_start_minutes"  tabindex="102">
								<?php echo $this->_tpl_vars['TIME_START_MINUTES_OPTIONS']; ?>

							</select>
								&nbsp;
							<?php echo $this->_tpl_vars['TIME_START_MERIDIEM']; ?>

						</div>
					</td>
				</tr>
				<tr class="time_range_options_row">
					<td scope="row" valign="top">
						<?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS_TIME_ENDS']; ?>

					</td>
					<td>
						<div id="d_end_time_section">
							<select size="1" id="day_end_hours" name="day_end_hours" tabindex="102">
								<?php echo $this->_tpl_vars['TIME_END_HOUR_OPTIONS']; ?>

							</select>&nbsp;:
							
							<select size="1" id="day_end_minutes" name="day_end_minutes"  tabindex="102">
								<?php echo $this->_tpl_vars['TIME_END_MINUTES_OPTIONS']; ?>

							</select>
								&nbsp;
							<?php echo $this->_tpl_vars['TIME_END_MERIDIEM']; ?>

						</div>
					</td>
				</tr>
				<tr>
					<td scope="row" valign="top">
						<?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS_CALLS_SHOW']; ?>

					</td>
					<td>	
						<select size="1" name="show_calls" tabindex="102">
							<option value='' <?php if (! $this->_tpl_vars['show_calls']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_NO']; ?>
</option>
							<option value='true' <?php if ($this->_tpl_vars['show_calls']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_YES']; ?>
</option>								
						</select>
					</td>
				</tr>
				<tr>
					<td scope="row" valign="top">
						<?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS_TASKS_SHOW']; ?>

					</td>
					<td>	
						<select size="1" name="show_tasks" tabindex="102">
							<option value='' <?php if (! $this->_tpl_vars['show_tasks']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_NO']; ?>
</option>
							<option value='true' <?php if ($this->_tpl_vars['show_tasks']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_YES']; ?>
</option>								
						</select>
					</td>
				</tr>
				<tr>
					<td scope="row" valign="top">
						<?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS_COMPLETED_SHOW']; ?>

					</td>
					<td>	
						<select size="1" name="show_completed" tabindex="102">
							<option value='' <?php if (! $this->_tpl_vars['show_completed']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_NO']; ?>
</option>
							<option value='true' <?php if ($this->_tpl_vars['show_completed']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_YES']; ?>
</option>								
						</select>
					</td>
				</tr>
		</table>
	</form>
	
	
	<div style="text-align: right;">
		<button id="btn-save-settings" class="button" type="button"><?php echo $this->_tpl_vars['MOD']['LBL_APPLY_BUTTON']; ?>
</button>&nbsp;
		<button id="btn-cancel-settings" class="button" type="button"><?php echo $this->_tpl_vars['MOD']['LBL_CANCEL_BUTTON']; ?>
</button>&nbsp;
	</div>
	</div>
</div>
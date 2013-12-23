<?php /* Smarty version 2.6.11, created on 2013-12-21 07:16:52
         compiled from modules/Campaigns/TrackDetailView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sugar_getscript', 'modules/Campaigns/TrackDetailView.tpl', 171, false),)), $this); ?>
<!-- BEGIN: main -->
<?php echo $this->_tpl_vars['chartResources']; ?>

<script>SUGAR.loadChart = true;</script>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td>
<form action="index.php" method="post" name="DetailView" id="form">
			<input type="hidden" name="module" value="CampaignLog">
			<input type="hidden" name="subpanel_parent_module" value="Campaigns">			
			<input type="hidden" name="record" value="<?php echo $this->_tpl_vars['ID']; ?>
">
			<input type="hidden" name="isDuplicate" value=false>
			<input type="hidden" name="action">
			<input type="hidden" name="return_module">
			<input type="hidden" name="return_action">
			<input type="hidden" name="return_id" >
			<input type="hidden" name="campaign_id" value="<?php echo $this->_tpl_vars['ID']; ?>
">
			<input type="hidden" name="mode" value="">
			
			<?php echo $this->_tpl_vars['TRACK_DELETE_BUTTON']; ?>

	</td>
		<td align='right'></td>
	<td align='right'>		    
		<input type="button" class="button" id="launch_wizard_button" onclick="javascript:window.location='index.php?module=Campaigns&action=WizardHome&record=<?php echo $this->_tpl_vars['ID']; ?>
';" value="<?php echo $this->_tpl_vars['MOD']['LBL_TO_WIZARD_TITLE']; ?>
" />
		<input type="button" class="button" id="view_details_button" onclick="javascript:window.location='index.php?module=Campaigns&action=DetailView&record=<?php echo $this->_tpl_vars['ID']; ?>
';" value="<?php echo $this->_tpl_vars['MOD']['LBL_TODETAIL_BUTTON_LABEL']; ?>
" />
		<span style="<?php echo $this->_tpl_vars['DISABLE_LINK']; ?>
"><input type="button" class="button" id="view_roi_button" onclick="javascript:window.location='index.php?module=Campaigns&action=RoiDetailView&record=<?php echo $this->_tpl_vars['ID']; ?>
';" value="<?php echo $this->_tpl_vars['MOD']['LBL_TRACK_ROI_BUTTON_LABEL']; ?>
" /></SPAN><?php echo $this->_tpl_vars['ADMIN_EDIT']; ?>

	</td>

	<td align='right'><?php echo $this->_tpl_vars['ADMIN_EDIT']; ?>
</td>
</tr>
</table>
<div class="detail view">
<table width="100%" border="0" cellspacing="<?php echo $this->_tpl_vars['GRIDLINE']; ?>
" cellpadding="0">
<tr>
<?php echo $this->_tpl_vars['PAGINATION']; ?>

	<td width="20%" scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_NAME']; ?>
</slot></td>
	<td width="30%"><slot><?php echo $this->_tpl_vars['NAME']; ?>
</slot></td>
	<td width="20%" scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_ASSIGNED_TO']; ?>
</slot></td>
	<td width="30%"><slot><?php echo $this->_tpl_vars['ASSIGNED_TO']; ?>
</slot></td>
	</tr><tr>
	<td width="20%" scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_CAMPAIGN_STATUS']; ?>
</slot></td>
	<td width="30%"><slot><?php echo $this->_tpl_vars['STATUS']; ?>
</slot></td>
<!-- BEGIN: pro -->
	<td width="20%" scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_TEAM']; ?>
</slot></td>
	<td width="30%"><slot><?php echo $this->_tpl_vars['TEAM_NAME']; ?>
</slot></td>
<!-- END: pro -->
<!-- BEGIN: open_source -->
	<td width="20%" scope="row"><slot>&nbsp;</slot></td>
	<td width="30%"><slot>&nbsp;</slot></td>
<!-- END: open_source -->
	</tr><tr>
	<td width="20%" scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_CAMPAIGN_START_DATE']; ?>
</slot></td>
	<td width="30%"><slot><?php echo $this->_tpl_vars['START_DATE']; ?>
</slot></td>
	<td scope="row"><slot><?php echo $this->_tpl_vars['APP']['LBL_DATE_MODIFIED']; ?>
&nbsp;</slot></td>
	<td><slot><?php echo $this->_tpl_vars['DATE_MODIFIED']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_BY']; ?>
 <?php echo $this->_tpl_vars['MODIFIED_BY']; ?>
</slot></td>
	</tr><tr>
	<td width="20%" scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_CAMPAIGN_END_DATE']; ?>
</slot></td>
	<td width="30%"><slot><?php echo $this->_tpl_vars['END_DATE']; ?>
</slot></td>
	<td scope="row"><slot><?php echo $this->_tpl_vars['APP']['LBL_DATE_ENTERED']; ?>
&nbsp;</slot></td>
	<td><slot><?php echo $this->_tpl_vars['DATE_ENTERED']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_BY']; ?>
 <?php echo $this->_tpl_vars['CREATED_BY']; ?>
</slot></td>
	</tr><tr>
	<td width="20%" scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_CAMPAIGN_TYPE']; ?>
</slot></td>
	<td width="30%"><slot><?php echo $this->_tpl_vars['TYPE']; ?>
</slot></td>
	<td width="20%" scope="row"><slot>&nbsp;</slot></td>
	<td width="30%"><slot>&nbsp;</slot></td>
	</tr><tr>
	<td width="20%" scope="row"><slot>&nbsp;</slot></td>
	<td width="30%"><slot>&nbsp;</slot></td>
	<td width="20%" scope="row"><slot>&nbsp;</slot></td>
	<td width="30%"><slot>&nbsp;</slot></td>
	</tr><tr>
	<td width="20%" nowrap scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_CAMPAIGN_BUDGET']; ?>
 (<?php echo $this->_tpl_vars['CURRENCY']; ?>
)</slot></td>
	<td width="30%"><slot><?php echo $this->_tpl_vars['BUDGET']; ?>
</slot></td>
	<td width="20%" nowrap scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_CAMPAIGN_ACTUAL_COST']; ?>
 (<?php echo $this->_tpl_vars['CURRENCY']; ?>
)</slot></td>
	<td width="30%"><slot><?php echo $this->_tpl_vars['ACTUAL_COST']; ?>
</slot></td>
	</tr><tr>
	<td width="20%" nowrap scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_CAMPAIGN_EXPECTED_REVENUE']; ?>
 (<?php echo $this->_tpl_vars['CURRENCY']; ?>
)</slot></td>
	<td width="30%" nowrap><slot><?php echo $this->_tpl_vars['EXPECTED_REVENUE']; ?>
</slot></td>
	<td width="20%" nowrap scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_CAMPAIGN_EXPECTED_COST']; ?>
 (<?php echo $this->_tpl_vars['CURRENCY']; ?>
)</slot></td>
	<td width="30%"><slot><?php echo $this->_tpl_vars['EXPECTED_COST']; ?>
</slot></td>
	</tr><tr>
	</tr><tr>
	<td width="20%" scope="row"><slot>&nbsp;</slot></td>
	<td width="30%"><slot>&nbsp;</slot></td>
	<td width="20%" scope="row"><slot>&nbsp;</slot></td>
	<td width="30%"><slot>&nbsp;</slot></td>
	</tr>
	<tr>
	<td width="20%" valign="top" scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_CAMPAIGN_OBJECTIVE']; ?>
</slot></td>
	<td colspan="3"><slot><?php echo $this->_tpl_vars['OBJECTIVE']; ?>
</slot></td>
</tr><tr>
	<td width="20%" valign="top" scope="row"><slot><?php echo $this->_tpl_vars['MOD']['LBL_CAMPAIGN_CONTENT']; ?>
</slot></td>
	<td colspan="3"><slot><?php echo $this->_tpl_vars['CONTENT']; ?>
</slot></td>
</tr>
</table>
</div>
	<table border='0' width='100%'>
		<tr>
			<td width="10%"><?php echo $this->_tpl_vars['FILTER_LABEL']; ?>
</td>
			<td width="70%"><?php echo $this->_tpl_vars['MKT_DROP_DOWN']; ?>
</td>
			<td width="20%">&nbsp;</td>			
		</tr>
		<tr>
			<td colspan="3"><div class="reportChartContainer"><?php echo $this->_tpl_vars['MY_CHART']; ?>
</div></td>				
		</tr>
	</table>
</form>
<script type="text/javascript" language="javascript">
<?php echo '
function re_draw_chart(x){
alert(x.value);
}

var toggle = 0;
	function show_more_info(tog){
		elem = document.getElementById(\'more_info\');
		if (tog == 0){
			toggle=1;
			elem.style.display = \'\';
		}else{
			toggle=0;		
			elem.style.display = \'none\';			
		}
	}
'; ?>

</script>

<!-- END: main -->

<!-- BEGIN: subpanel -->
<?php echo smarty_function_sugar_getscript(array('file' => "modules/Campaigns/DetailView.js"), $this);?>

<slot><?php echo $this->_tpl_vars['SUBPANEL']; ?>
</slot>
<?php /* Smarty version 2.6.11, created on 2013-12-21 06:44:58
         compiled from modules/Campaigns/tpls/WizardHomeStart.tpl */ ?>

<div id='wiz_stage'>
<form  id="wizform" name="wizform" method="POST" action="index.php">
	<input type="hidden" name="module" value="Campaigns">
	<input type="hidden" id='action' name="action" value='WizardNewsletter'>
	<input type="hidden" id="return_module" name="return_module" value="Campaigns">
	<input type="hidden" id="return_action" name="return_action" value="WizardHome">


	
<table class='other view' cellspacing="1">
<tr>
<td rowspan='2' width="10%" scope="row" style="vertical-align: top;">
<p>
<div id='nav'>
<table border="0" cellspacing="0" cellpadding="0" width="100%" >
<tr><td scope='row' ><div id='nav_step1'><?php echo $this->_tpl_vars['MOD']['LBL_CHOOSE_CAMPAIGN_TYPE']; ?>
</div></td></tr>
</table>
</div>
</p>
</td>

<td  rowspan='2' width='100%' class='edit view'>
<div id="wiz_message"></div>
<div id=wizard>


	<div id='step1' >
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr><th scope='col' colspan='2' align="left" ><h4><?php echo $this->_tpl_vars['MOD']['LBL_CHOOSE_CAMPAIGN_TYPE']; ?>
</h4></th></tr>
			<tr><td colspan='2' >
				<fieldset><legend><?php echo $this->_tpl_vars['MOD']['LBL_HOME_START_MESSAGE']; ?>
</legend>
                     <p>
                        <input type="radio"  id="wizardtype_nl" name="wizardtype" value='1'checked ><label for='wizardtype_nl'><?php echo $this->_tpl_vars['MOD']['LBL_NEWSLETTER']; ?>
</label><br>
                        <input type="radio"  id="wizardtype_em" name="wizardtype" value='2'><label for='wizardtype_em'><?php echo $this->_tpl_vars['MOD']['LBL_EMAIL']; ?>
</label><br>
                        <input type="radio"  id="wizardtype_ot" name='wizardtype' value='3'><label for='wizardtype_ot'><?php echo $this->_tpl_vars['MOD']['LBL_OTHER_TYPE_CAMPAIGN']; ?>
</label><br>
                    </p>
                </fieldset>
			</td></tr>
			</table>	
	</div>
	</p>

	
	
	</td>
</tr>
</table>

<div id ='buttons' >
	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
	<tr>
		<td  align="right" width='40%'>&nbsp;</td>
		<td  align="right" width='30%'>
			<table><tr>
				<td><div id="start_button_div"><input id="startbutton" type='submit' title="<?php echo $this->_tpl_vars['MOD']['LBL_START']; ?>
" class="button" name="<?php echo $this->_tpl_vars['MOD']['LBL_START']; ?>
" value="<?php echo $this->_tpl_vars['MOD']['LBL_START']; ?>
"></div></td>
			</tr></table>
		</td>
	</tr>
	</table>
</div>

</form>
<script>
document.getElementById('startbutton').focus=true;
</script>


</div>



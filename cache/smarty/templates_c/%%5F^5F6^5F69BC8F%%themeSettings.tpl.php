<?php /* Smarty version 2.6.11, created on 2013-12-21 11:19:45
         compiled from modules/Administration/templates/themeSettings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sugar_getjspath', 'modules/Administration/templates/themeSettings.tpl', 39, false),array('function', 'sugar_translate', 'modules/Administration/templates/themeSettings.tpl', 90, false),)), $this); ?>
<script type="text/javascript" src="cache/include/javascript/sugar_grp_yui_widgets.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_sugar_getjspath(array('file' => 'modules/Connectors/tpls/tabs.css'), $this);?>
"/>

<form name="themeSettings" method="POST">
	<input type="hidden" name="module" value="Administration">
	<input type="hidden" name="action" value="ThemeSettings">
	<input type="hidden" name="disabled_themes" value="">
	
	<table border="0" cellspacing="1" cellpadding="1" class="actionsContainer">
		<tr>
			<td>
			<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" class="button primary" onclick="SUGAR.saveThemeSettings();" type="button" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
">
			<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="button" onclick="document.themeSettings.action.value='';" type="submit" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
">
			</td>
		</tr>
	</table>
	
	<div class='add_table' style='margin-bottom:5px'>
		<table id="themeSettings" class="themeSettings edit view" style='margin-bottom:0px;' border="0" cellspacing="0" cellpadding="0">
            <td nowrap><b><?php echo $this->_tpl_vars['MOD']['DEFAULT_THEME']; ?>
</b> &nbsp;
                <select name='default_theme' id='default_theme'><?php echo $this->_tpl_vars['THEMES']; ?>
</select>
            </td>
		    <tr>
				<td width='1%'>
					<div id="enabled_div"></div>	
				</td>
				<td>
					<div id="disabled_div"></div>
				</td>
			</tr>
		</table>
	</div>
	
	<table border="0" cellspacing="1" cellpadding="1" class="actionsContainer">
		<tr>
			<td>
				<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
" class="button primary" onclick="SUGAR.saveThemeSettings();" type="button" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
">
				<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
" class="button" onclick="document.themeSettings.action.value='';" type="submit" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
">
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">
(function(){
    var Connect = YAHOO.util.Connect;
	Connect.url = 'index.php';
    Connect.method = 'POST';
    Connect.timeout = 300000; 

	var enabled_modules = <?php echo $this->_tpl_vars['enabled_modules']; ?>
;
	var disabled_modules = <?php echo $this->_tpl_vars['disabled_modules']; ?>
;
	var lblEnabled = '<?php echo smarty_function_sugar_translate(array('label' => 'LBL_ACTIVE_THEMES'), $this);?>
';
	var lblDisabled = '<?php echo smarty_function_sugar_translate(array('label' => 'LBL_DISABLED_THEMES'), $this);?>
';
	<?php echo '
	SUGAR.themeEnabledTable = new YAHOO.SUGAR.DragDropTable(
		"enabled_div",
		[{key:"theme",  label: lblEnabled, width: 200, sortable: false},
		{key:"dir", hidden:true}],
		new YAHOO.util.LocalDataSource(enabled_modules, {
			responseSchema: {fields : [{key : "theme"}, {key : "dir"}]}
		}),  
		{height: "300px"}
	);
	SUGAR.themeDisabledTable = new YAHOO.SUGAR.DragDropTable(
		"disabled_div",
		[{key:"theme",  label: lblDisabled, width: 200, sortable: false},
		{key:"dir", hidden:true}],
		new YAHOO.util.LocalDataSource(disabled_modules, {
			responseSchema: {fields : [{key : "theme"}, {key : "dir"}]}
		}),
		{height: "300px"}
	);
	SUGAR.themeEnabledTable.disableEmptyRows = true;
	SUGAR.themeDisabledTable.disableEmptyRows = true;
	SUGAR.themeEnabledTable.addRow({module: "", label: ""});
	SUGAR.themeDisabledTable.addRow({module: "", label: ""});
	SUGAR.themeEnabledTable.render();
	SUGAR.themeDisabledTable.render();
	
	SUGAR.saveThemeSettings = function()
	{
		var disabledTable = SUGAR.themeDisabledTable;
		var themes = [];
		for(var i=0; i < disabledTable.getRecordSet().getLength(); i++){
			var data = disabledTable.getRecord(i).getData();
			if (data.dir && data.dir != \'\') {
			    themes[i] = data.dir;
			    if ( themes[i] == document.getElementById(\'default_theme\').value ) {
			        if ( !confirm(SUGAR.language.get(\'Administration\', \'LBL_DEFAULT_THEME_IS_DISABLED\')) ) {
			            return false;
			        }
			    }
			}
		}
		
		ajaxStatus.showStatus(SUGAR.language.get(\'Administration\', \'LBL_SAVING\'));
        Connect.asyncRequest(
            Connect.method, 
            Connect.url, 
            {success: SUGAR.saveCallBack},
			\'to_pdf=1&module=Administration&action=ThemeSettings&default_theme=\'+document.getElementById(\'default_theme\').value+\'&disabled_themes=\' + YAHOO.lang.JSON.stringify(themes)
        );
		
		return true;
	}
	SUGAR.saveCallBack = function(o)
	{
	   ajaxStatus.flashStatus(SUGAR.language.get(\'app_strings\', \'LBL_DONE\'));
	   if (o.responseText == "true")
	   {
	       window.location.assign(\'index.php?module=Administration&action=ThemeSettings\');
	   } 
	   else 
	   {
	       YAHOO.SUGAR.MessageBox.show({msg:o.responseText});
	   }
	}	
})();
'; ?>

</script>
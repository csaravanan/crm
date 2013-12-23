<?php /* Smarty version 2.6.11, created on 2013-12-21 11:32:30
         compiled from modules/Administration/templates/Languages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sugar_getjspath', 'modules/Administration/templates/Languages.tpl', 38, false),array('function', 'sugar_getscript', 'modules/Administration/templates/Languages.tpl', 39, false),array('function', 'sugar_translate', 'modules/Administration/templates/Languages.tpl', 95, false),)), $this); ?>
<link rel="stylesheet" type="text/css" href="<?php echo smarty_function_sugar_getjspath(array('file' => 'modules/Connectors/tpls/tabs.css'), $this);?>
"/>
<?php echo smarty_function_sugar_getscript(array('file' => "cache/include/javascript/sugar_grp_yui_widgets.js"), $this);?>

<style>.yui-dt-scrollable .yui-dt-bd {overflow-x: hidden;}</style>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan='100'><h2><?php echo $this->_tpl_vars['title']; ?>
</h2></td></tr>
<tr><td colspan='100'>
<?php echo $this->_tpl_vars['MOD']['LBL_CONFIG_LANGS_DESC']; ?>

</td></tr><tr><td><br></td></tr><tr><td colspan='100'>

<form name="ConfigureLangs" method="POST"  method="POST" action="index.php">
	<input type="hidden" name="module" value="Administration">
	<input type="hidden" name="action" value="SaveLanguages">
	<input type="hidden" id="enabled_langs" name="enabled_langs" value="">
	<input type="hidden" id="disabled_langs" name="disabled_langs" value="">
	<input type="hidden" name="return_module" value="<?php echo $this->_tpl_vars['RETURN_MODULE']; ?>
">
	<input type="hidden" name="return_action" value="<?php echo $this->_tpl_vars['RETURN_ACTION']; ?>
">

	<table border="0" cellspacing="1" cellpadding="1" class="actionsContainer">
		<tr>
			<td>
				<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="button primary" onclick="SUGAR.saveConfigureLangs();this.form.action.value='SaveLanguages'; " type="submit" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
" >
				<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="button" onclick="this.form.action.value='index'; this.form.module.value='Administration';" type="submit" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
">
			</td>
		</tr>
	</table>

	<div class='add_table' style='margin-bottom:5px'>
		<table id="ConfigureLangs" class="themeSettings edit view" style='margin-bottom:0px;' border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width='1%'>
					<div id="enabled_div" class="enabled_tab_workarea">
					</div>
				</td>
				<td>
					<div id="disabled_div" class="disabled_tab_workarea">
					</div>
				</td>
			</tr>
		</table>
	</div>

	<table border="0" cellspacing="1" cellpadding="1" class="actionsContainer">
		<tr>
			<td>
				<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" class="button primary" onclick="SUGAR.saveConfigureLangs();this.form.action.value='SaveLanguages'; " type="submit" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
" >
				<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" class="button" onclick="this.form.action.value='index'; this.form.module.value='Administration';" type="submit" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
">
			</td>
		</tr>
	</table>
</form>


<script type="text/javascript">
(function(){
	var enabled_modules = <?php echo $this->_tpl_vars['enabled_langs']; ?>
;
	var disabled_modules = <?php echo $this->_tpl_vars['disabled_langs']; ?>
;
	var lblEnabled = '<?php echo smarty_function_sugar_translate(array('label' => 'LBL_ENABLED_LANGS'), $this);?>
';
	var lblDisabled = '<?php echo smarty_function_sugar_translate(array('label' => 'LBL_DISABLED_LANGS'), $this);?>
';
	<?php echo '
	SUGAR.enabledLangsTable = new YAHOO.SUGAR.DragDropTable(
		"enabled_div",
		[{key:"label",  label: lblEnabled, width: 200, sortable: false},
		 {key:"module", label: lblEnabled, hidden:true}],
		new YAHOO.util.LocalDataSource(enabled_modules, {
			responseSchema: {
			   resultsList : "modules",
			   fields : [{key : "module"}, {key : "label"}]
			}
		}),
		{height: "300px"}
	);
	SUGAR.disabledLangsTable = new YAHOO.SUGAR.DragDropTable(
		"disabled_div",
		[{key:"label",  label: lblDisabled, width: 200, sortable: false},
		 {key:"module", label: lblDisabled, hidden:true}],
		new YAHOO.util.LocalDataSource(disabled_modules, {
			responseSchema: {
			   resultsList : "modules",
			   fields : [{key : "module"}, {key : "label"}]
			}
		}),
		{height: "300px"}
	);
	SUGAR.enabledLangsTable.disableEmptyRows = true;
    SUGAR.disabledLangsTable.disableEmptyRows = true;
    SUGAR.enabledLangsTable.addRow({module: "", label: ""});
    SUGAR.disabledLangsTable.addRow({module: "", label: ""});
	SUGAR.enabledLangsTable.render();
	SUGAR.disabledLangsTable.render();

	SUGAR.saveConfigureLangs = function()
	{
		var enabledTable = SUGAR.enabledLangsTable;
		var modules = [];
		for(var i=0; i < enabledTable.getRecordSet().getLength(); i++){
			var data = enabledTable.getRecord(i).getData();
			if (data.module && data.module != \'\')
			    modules[i] = data.module;
		}
		YAHOO.util.Dom.get(\'enabled_langs\').value = YAHOO.lang.JSON.stringify(modules);

		var disabledTable = SUGAR.disabledLangsTable;
		var modules = [];
		for(var i=0; i < disabledTable.getRecordSet().getLength(); i++){
			var data = disabledTable.getRecord(i).getData();
			if (data.module && data.module != \'\')
			    modules[i] = data.module;
		}
		YAHOO.util.Dom.get(\'disabled_langs\').value = YAHOO.lang.JSON.stringify(modules);
	}
})();
'; ?>

</script>
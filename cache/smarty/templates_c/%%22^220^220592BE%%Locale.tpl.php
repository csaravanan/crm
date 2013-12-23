<?php /* Smarty version 2.6.11, created on 2013-12-21 11:22:35
         compiled from modules/Administration/Locale.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'modules/Administration/Locale.tpl', 95, false),)), $this); ?>


<script type="text/javascript">
	var ERR_NO_SINGLE_QUOTE = '<?php echo $this->_tpl_vars['APP']['ERR_NO_SINGLE_QUOTE']; ?>
';
	var cannotEq = "<?php echo $this->_tpl_vars['APP']['ERR_DECIMAL_SEP_EQ_THOUSANDS_SEP']; ?>
";
<?php echo '
	function verify_data(formName) {
		var f = document.getElementById(formName);

		for(i=0; i<f.elements.length; i++) {
			if(f.elements[i].value == "\'") {
				alert(ERR_NO_SINGLE_QUOTE + " " + f.elements[i].name);
				return false;
			}
		}
		// currency syntax
		if (document.ConfigureSettings.default_number_grouping_seperator.value == document.ConfigureSettings.default_decimal_seperator.value) {
			alert(cannotEq);
			return false;
		}
		return true;
	}
</script>
'; ?>

<BR>
<form id="ConfigureSettings" name="ConfigureSettings" enctype='multipart/form-data' method="POST"
	action="index.php?module=Administration&action=Locale&process=true">

<span class='error'><?php echo $this->_tpl_vars['error']['main']; ?>
</span>

<table width="100%" cellpadding="0" cellspacing="0" border="0" class="actionsContainer">
<tr>
	<td>
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
"
			accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
"
			class="button primary"
			type="submit"
			name="save"
			onclick="return verify_data('ConfigureSettings');"
			value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " >
		&nbsp;<input title="<?php echo $this->_tpl_vars['MOD']['LBL_CANCEL_BUTTON_TITLE']; ?>
"  onclick="document.location.href='index.php?module=Administration&action=index'" class="button"  type="button" name="cancel" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " > </td>
	</tr>
</table>




<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
	<tr><th align="left" scope="row" colspan="4"><h4><?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DEFAULT_SYSTEM_SETTINGS']; ?>
</h4></th>
	</tr>
	<tr>
		<td  scope="row" width="200"><?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DEFAULT_DATE_FORMAT']; ?>
: </td>
		<td  >
			<?php echo smarty_function_html_options(array('name' => 'default_date_format','selected' => $this->_tpl_vars['config']['default_date_format'],'options' => $this->_tpl_vars['config']['date_formats']), $this);?>

		</td>
		<td  scope="row" width="200"><?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DEFAULT_TIME_FORMAT']; ?>
: </td>
		<td  >
			<?php echo smarty_function_html_options(array('name' => 'default_time_format','selected' => $this->_tpl_vars['config']['default_time_format'],'options' => $this->_tpl_vars['config']['time_formats']), $this);?>

		</td>
	</tr><tr>
		<td  scope="row"><?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DEFAULT_LANGUAGE']; ?>
: </td>
		<td  >
			<?php echo smarty_function_html_options(array('name' => 'default_language','selected' => $this->_tpl_vars['config']['default_language'],'options' => $this->_tpl_vars['LANGUAGES']), $this);?>

		</td>
	</tr>
	</tr><tr>
		<td  scope="row" valign="top"><?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DEFAULT_NAME_FORMAT']; ?>
: </td>
		<td>
            <?php echo smarty_function_html_options(array('name' => 'default_locale_name_format','id' => 'default_locale_name_format','selected' => $this->_tpl_vars['config']['default_locale_name_format'],'options' => $this->_tpl_vars['NAMEFORMATS']), $this);?>

		</td>
        <?php if (isset ( $this->_tpl_vars['upgradeInvalidLocaleNameFormat'] )): ?>
        <td>
            <?php echo $this->_tpl_vars['MOD']['ERR_INVALID_LOCALE_NAME_FORMAT_UPGRADE']; ?>

        </td>
        <?php endif; ?>
	</tr>

	</table>



<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
	<tr>
		<th align="left" scope="row" colspan="4"><h4><?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DEFAULT_CURRENCY']; ?>
</h4></th>
	</tr><tr>
		<td  scope="row" width="200"><?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DEFAULT_CURRENCY_NAME']; ?>
: </td>
		<td  >
			<input type='text' size='25' name='default_currency_name' value='<?php echo $this->_tpl_vars['config']['default_currency_name']; ?>
' >
		</td>
		<td  scope="row" width="200"><?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DEFAULT_CURRENCY_SYMBOL']; ?>
: </td>
		<td  >
			<input type='text' size='4' name='default_currency_symbol'  value='<?php echo $this->_tpl_vars['config']['default_currency_symbol']; ?>
' >
		</td>
	</tr><tr>
		<td  scope="row" width="200"><?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DEFAULT_CURRENCY_ISO4217']; ?>
: </td>
		<td  >
			<input type='text' size='4' name='default_currency_iso4217' value='<?php echo $this->_tpl_vars['config']['default_currency_iso4217']; ?>
'>
		</td>
		<td  scope="row"><?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DEFAULT_NUMBER_GROUPING_SEP']; ?>
: </td>
		<td  >
			<input type='text' size='3' maxlength='1' name='default_number_grouping_seperator' value='<?php echo $this->_tpl_vars['config']['default_number_grouping_seperator']; ?>
'>
		</td>
	</tr><tr>
		<td  scope="row"><?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DEFAULT_DECIMAL_SEP']; ?>
: </td>
		<td  >
			<input type='text' size='3' maxlength='1' name='default_decimal_seperator'  value='<?php echo $this->_tpl_vars['config']['default_decimal_seperator']; ?>
'>
		</td>
		<td  scope="row"></td>
		<td  ></td>
	</tr>
</table>



<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
	<tr><th align="left" scope="row" colspan="4"><h4><?php echo $this->_tpl_vars['MOD']['EXPORT']; ?>
</h4></th>
	</tr><tr>
		<td nowrap width="10%" scope="row"><?php echo $this->_tpl_vars['MOD']['EXPORT_DELIMITER']; ?>
: </td>
		<td width="25%" >
			<input type='text' name='export_delimiter' size="5" value='<?php echo $this->_tpl_vars['config']['export_delimiter']; ?>
'>
		</td>
		<td nowrap width="10%" scope="row"><?php echo $this->_tpl_vars['MOD']['EXPORT_CHARSET']; ?>
: </td>
		<td width="25%" >
			<select name="default_export_charset"><?php echo $this->_tpl_vars['exportCharsets']; ?>
</select>
		</td>
		</tr><tr>
		<td nowrap width="10%" scope="row"><?php echo $this->_tpl_vars['MOD']['DISABLE_EXPORT']; ?>
: </td>
		<?php if (! empty ( $this->_tpl_vars['config']['disable_export'] )): ?>
			<?php $this->assign('disable_export_checked', 'CHECKED'); ?>
		<?php else: ?>
			<?php $this->assign('disable_export_checked', ''); ?>
		<?php endif; ?>
		<td width="25%" ><input type='hidden' name='disable_export' value='false'><input name='disable_export'  type="checkbox" value="true" <?php echo $this->_tpl_vars['disable_export_checked']; ?>
></td>
		<td nowrap width="10%" scope="row"><?php echo $this->_tpl_vars['MOD']['ADMIN_EXPORT_ONLY']; ?>
: </td>
		<?php if (! empty ( $this->_tpl_vars['config']['admin_export_only'] )): ?>
			<?php $this->assign('admin_export_only_checked', 'CHECKED'); ?>
		<?php else: ?>
			<?php $this->assign('admin_export_only_checked', ''); ?>
		<?php endif; ?>
		<td width="20%" ><input type='hidden' name='admin_export_only' value='false'><input name='admin_export_only'  type="checkbox" value="true" <?php echo $this->_tpl_vars['admin_export_only_checked']; ?>
></td>

	</tr>
</table>


<?php if (! empty ( $this->_tpl_vars['collationOptions'] )): ?>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
	<tr>
		<th align="left" scope="row" colspan="2">
			<h4>
				<?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DB_COLLATION_TITLE']; ?>

			</h4>
		</th>
	</tr>
	<tr>
		<td scope="row" width="200">
			<?php echo $this->_tpl_vars['MOD']['LBL_LOCALE_DB_COLLATION']; ?>

		</td>
		<td scope="row">
			<select name="collation" id="collation"><?php echo $this->_tpl_vars['collationOptions']; ?>
</select>
		</td>
	</tr>
</table>


<?php endif; ?>
<div style="padding-top: 2px;">
<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" class="button primary"  type="submit" name="save" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " />
		&nbsp;<input title="<?php echo $this->_tpl_vars['MOD']['LBL_CANCEL_BUTTON_TITLE']; ?>
"  onclick="document.location.href='index.php?module=Administration&action=index'" class="button"  type="button" name="cancel" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " />
</div>
<?php echo $this->_tpl_vars['JAVASCRIPT']; ?>

</form>

<script language="Javascript" type="text/javascript">
<?php echo $this->_tpl_vars['getNameJs']; ?>

</script>
<?php /* Smarty version 2.6.11, created on 2013-12-17 13:34:18
         compiled from modules/Import/tpls/step2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sugar_help', 'modules/Import/tpls/step2.tpl', 72, false),array('modifier', 'upper', 'modules/Import/tpls/step2.tpl', 109, false),)), $this); ?>
<?php echo '

<style>

.link {
    text-decoration:underline
}
</style>
'; ?>



<?php echo $this->_tpl_vars['INSTRUCTION']; ?>


<div class="hr"></div>

<form enctype="multipart/form-data" name="importstep2" method="POST" action="index.php" id="importstep2">
<input type="hidden" name="module" value="Import">
<input type="hidden" name="custom_delimiter" value="<?php echo $this->_tpl_vars['CUSTOM_DELIMITER']; ?>
">
<input type="hidden" name="custom_enclosure" value="<?php echo $this->_tpl_vars['CUSTOM_ENCLOSURE']; ?>
">
<input type="hidden" name="source" value="<?php echo $this->_tpl_vars['SOURCE']; ?>
">
<input type="hidden" name="source_id" value="<?php echo $this->_tpl_vars['SOURCE_ID']; ?>
">
<input type="hidden" name="action" value="Confirm">
<input type="hidden" name="current_step" value="<?php echo $this->_tpl_vars['CURRENT_STEP']; ?>
">
<input type="hidden" name="import_module" value="<?php echo $this->_tpl_vars['IMPORT_MODULE']; ?>
">
<input type="hidden" name="from_admin_wizard" value="<?php echo $_REQUEST['from_admin_wizard']; ?>
">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td align="left" scope="row" colspan="4" style="padding-left: 10px;"><?php echo $this->_tpl_vars['SAMPLE_URL']; ?>
 &nbsp;<?php echo smarty_function_sugar_help(array('text' => $this->_tpl_vars['MOD']['LBL_SAMPLE_URL_HELP']), $this);?>
</td>
        </tr>
        <tr>
            <td scope="row" colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td scope="row" colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" scope="row" colspan="3"><label for="userfile"><?php echo $this->_tpl_vars['MOD']['LBL_SELECT_FILE']; ?>
</label> <input type="hidden" /><input size="20" id="userfile" name="userfile" type="file"/> &nbsp;<?php echo smarty_function_sugar_help(array('text' => $this->_tpl_vars['MOD']['LBL_FILE_UPLOAD_WIDGET_HELP']), $this);?>
</td>
        </tr>
        <tr>
            <td scope="row" colspan="4"><div class="hr">&nbsp;</div></td>
        </tr>
        <tr>
            <td scope="row" colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td scope="row" colspan="3">
                <h3><?php echo $this->_tpl_vars['MOD']['LBL_IMPORT_TYPE']; ?>
&nbsp;</h3></td>
          </tr>
          <tr>
            <td scope="row" colspan="3">
                <input id="import_create" class="radio" type="radio" name="type" value="import" checked="checked" />
                &nbsp;<label for="type"><?php echo $this->_tpl_vars['MOD']['LBL_IMPORT_BUTTON']; ?>
</label> &nbsp;<?php echo smarty_function_sugar_help(array('text' => $this->_tpl_vars['MOD']['LBL_CREATE_BUTTON_HELP']), $this);?>

            </td>
          </tr>
          <tr>
            <td scope="row" colspan="3">
                <input id="import_update" class="radio" type="radio" name="type" id="type" value="update" />
                &nbsp;<label for="type"><?php echo $this->_tpl_vars['MOD']['LBL_UPDATE_BUTTON']; ?>
</label> &nbsp;<?php echo smarty_function_sugar_help(array('text' => $this->_tpl_vars['MOD']['LBL_UPDATE_BUTTON_HELP']), $this);?>

            </td>
          </tr>
	</table>
    <br>
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
          <?php $_from = $this->_tpl_vars['custom_mappings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['custommappings'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['custommappings']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['custommappings']['iteration']++;
?>
          <?php ob_start();  echo $this->_tpl_vars['MOD']['LBL_CUSTOM_MAPPING_'];  echo ((is_array($_tmp=$this->_tpl_vars['item'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp));  $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('mapping_label', ob_get_contents());ob_end_clean(); ?>
          <tr>
            <td colspan="3" scope="row"><input class="radio" type="radio" id="source" name="source" value="<?php echo $this->_tpl_vars['item']; ?>
" />
              &nbsp;<label for="source"><?php echo $this->_tpl_vars['mapping_label']; ?>
</label></td>
          </tr>
          <?php endforeach; endif; unset($_from); ?>

          <?php if (! empty ( $this->_tpl_vars['custom_imports'] ) || ! empty ( $this->_tpl_vars['published_imports'] )): ?>
          <tr>
            <td scope="row" colspan="3">
                <h3><?php echo $this->_tpl_vars['MOD']['LBL_PUBLISHED_SOURCES']; ?>
&nbsp;<?php echo smarty_function_sugar_help(array('text' => $this->_tpl_vars['savedMappingHelpText']), $this);?>
</h3></td>
          </tr>
          
          <tr id="custom_import_<?php echo ($this->_foreach['saved']['iteration']-1); ?>
">
            <td scope="row" colspan="4">
                <input class="radio" type="radio" name="source" value=""/>
                &nbsp;<?php echo $this->_tpl_vars['MOD']['LBL_NONE']; ?>

            </td>

          </tr>
          <?php endif; ?>
          <?php $_from = $this->_tpl_vars['custom_imports']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['saved'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['saved']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['saved']['iteration']++;
?>
          <tr id="custom_import_<?php echo ($this->_foreach['saved']['iteration']-1); ?>
">
            <td scope="row" colspan="2" width="10%" style="padding-right: 10px;">
                <input class="radio" type="radio" name="source" value="custom:<?php echo $this->_tpl_vars['item']['IMPORT_ID']; ?>
"/>
                &nbsp;<?php echo $this->_tpl_vars['item']['IMPORT_NAME']; ?>

            </td>
            <td scope="row">
                <?php if ($this->_tpl_vars['is_admin']): ?>
                <input type="button" name="publish" value="<?php echo $this->_tpl_vars['MOD']['LBL_PUBLISH']; ?>
" class="button" publish="yes"
                    onclick="publishMapping(this, 'yes','<?php echo $this->_tpl_vars['item']['IMPORT_ID']; ?>
');">
                <?php endif; ?>
                <input type="button" name="delete" value="<?php echo $this->_tpl_vars['MOD']['LBL_DELETE']; ?>
" class="button"
					onclick="if(confirm('<?php echo $this->_tpl_vars['MOD']['LBL_DELETE_MAP_CONFIRMATION']; ?>
'))<?php echo '{'; ?>
 deleteMapping('custom_import_<?php echo ($this->_foreach['saved']['iteration']-1); ?>
', '<?php echo $this->_tpl_vars['item']['IMPORT_ID']; ?>
' );<?php echo '}'; ?>
">
            </td>
          </tr>
          <?php endforeach; endif; unset($_from); ?>

          <?php $_from = $this->_tpl_vars['published_imports']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['published'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['published']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['published']['iteration']++;
?>
          <tr id="published_import_<?php echo ($this->_foreach['published']['iteration']-1); ?>
">
            <td scope="row" colspan="2">
                <input class="radio" type="radio" name="source" value="custom:<?php echo $this->_tpl_vars['item']['IMPORT_ID']; ?>
"/>
                &nbsp;<?php echo $this->_tpl_vars['item']['IMPORT_NAME']; ?>

            </td>
            <td scope="row">
                <?php if ($this->_tpl_vars['is_admin']): ?>
                <input type="button" name="publish" value="<?php echo $this->_tpl_vars['MOD']['LBL_UNPUBLISH']; ?>
" class="button" publish="no"
                    onclick="publishMapping(this, 'no','<?php echo $this->_tpl_vars['item']['IMPORT_ID']; ?>
');">
                <input type="button" name="delete" value="<?php echo $this->_tpl_vars['MOD']['LBL_DELETE']; ?>
" class="button"
                    onclick="if(confirm('<?php echo $this->_tpl_vars['MOD']['LBL_DELETE_MAP_CONFIRMATION']; ?>
'))<?php echo '{'; ?>
deleteMapping('published_import_<?php echo ($this->_foreach['published']['iteration']-1); ?>
','<?php echo $this->_tpl_vars['item']['IMPORT_ID']; ?>
' );<?php echo '}'; ?>
">
                <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; endif; unset($_from); ?>
    </table>
</td>
</tr>
</table>

<br>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
  <td align="left">
        <?php if ($this->_tpl_vars['displayBackBttn']): ?>
            <input title="<?php echo $this->_tpl_vars['MOD']['LBL_BACK']; ?>
"  class="button" type="submit" name="button" value="  <?php echo $this->_tpl_vars['MOD']['LBL_BACK']; ?>
  " id="goback">&nbsp;
        <?php endif; ?>
      <input title="<?php echo $this->_tpl_vars['MOD']['LBL_NEXT']; ?>
"  class="button" type="submit" name="button" value="  <?php echo $this->_tpl_vars['MOD']['LBL_NEXT']; ?>
  " id="gonext">
    </td>
</tr>
</table>
<script>
<?php echo $this->_tpl_vars['JAVASCRIPT']; ?>

</script>  
</form>
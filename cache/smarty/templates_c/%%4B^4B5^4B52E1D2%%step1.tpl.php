<?php /* Smarty version 2.6.11, created on 2013-12-21 11:19:24
         compiled from modules/Import/tpls/step1.tpl */ ?>

<?php echo '
<style>
#smtpButtonGroup .yui-button {
    padding-top: 10px;
}
#smtpButtonGroup .yui-radio-button-checked button, .yui-checkbox-button-checked button {
    background-color: #CCCCCC;
    color: #FFFFFF;
    text-shadow: none;
}


'; ?>

</style>
<?php if ($this->_tpl_vars['ERROR'] != ''): ?>
<span class="error"><?php echo $this->_tpl_vars['ERROR']; ?>
</span>
<?php endif;  echo $this->_tpl_vars['INSTRUCTION']; ?>


<form enctype="multipart/form-data" name="importstep1" method="post" action="index.php" id="importstep1">
<input type="hidden" name="module" value="Import">
<input type="hidden" name="action" value="Step2">
<input type="hidden" name="current_step" value="1">
<input type="hidden" name="external_source" value="">
<input type="hidden" name="from_admin_wizard" value="<?php echo $this->_tpl_vars['FROM_ADMIN']; ?>
">
<input type="hidden" name="import_module" value="<?php echo $this->_tpl_vars['IMPORT_MODULE']; ?>
">
<p>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" width='100%' scope="row">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <?php if ($this->_tpl_vars['showModuleSelection']): ?>
                                <tr>
                                    <td align="left" scope="row" colspan="3"><h3><?php echo $this->_tpl_vars['MOD']['LBL_STEP_MODULE']; ?>
&nbsp;</h3></td>
                                </tr>
                                <tr>
                                    <td><select tabindex='4' name='admin_import_module' id='admin_import_module'><?php echo $this->_tpl_vars['IMPORTABLE_MODULES_OPTIONS']; ?>
</select></td>
                                </tr>
                                <tr>
                                    <td align="left" scope="row" colspan="3"><div class="hr">&nbsp;</div></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="source" value="csv" />
                                </td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</p>
<br>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td align="left"><input title="<?php echo $this->_tpl_vars['MOD']['LBL_NEXT']; ?>
"  class="button" type="submit" name="button" value="  <?php echo $this->_tpl_vars['MOD']['LBL_NEXT']; ?>
  "  id="gonext"></td>
    </tr>
</table>
</form>
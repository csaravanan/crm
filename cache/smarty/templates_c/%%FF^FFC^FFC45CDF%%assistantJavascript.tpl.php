<?php /* Smarty version 2.6.11, created on 2013-12-21 11:26:49
         compiled from modules/ModuleBuilder/tpls/assistantJavascript.tpl */ ?>
<script>
<?php echo '
if(typeof(Assistant)!="undefined" && Assistant.mbAssistant){
	//Assistant.mbAssistant.render(document.body);
'; ?>

<?php if ($this->_tpl_vars['userPref']): ?>
	Assistant.processUserPref("<?php echo $this->_tpl_vars['userPref']; ?>
");
<?php endif;  if ($this->_tpl_vars['assistant']['key'] && $this->_tpl_vars['assistant']['group']): ?>
	Assistant.mbAssistant.setBody(SUGAR.language.get('ModuleBuilder','assistantHelp').<?php echo $this->_tpl_vars['assistant']['group']; ?>
.<?php echo $this->_tpl_vars['assistant']['key']; ?>
);
<?php endif;  echo '
	if(Assistant.mbAssistant.visible){
		Assistant.mbAssistant.show();
		}
}
'; ?>

</script>
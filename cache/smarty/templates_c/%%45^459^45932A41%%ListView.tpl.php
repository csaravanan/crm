<?php /* Smarty version 2.6.11, created on 2013-12-21 08:05:23
         compiled from include/SugarFields/Fields/Link/ListView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sugar_fetch', 'include/SugarFields/Fields/Link/ListView.tpl', 38, false),array('function', 'sugar_replace_vars', 'include/SugarFields/Fields/Link/ListView.tpl', 41, false),array('modifier', 'to_url', 'include/SugarFields/Fields/Link/ListView.tpl', 44, false),)), $this); ?>
<?php ob_start();  echo smarty_function_sugar_fetch(array('object' => $this->_tpl_vars['parentFieldArray'],'key' => $this->_tpl_vars['col']), $this); $this->_smarty_vars['capture']['getLink'] = ob_get_contents();  $this->assign('link', ob_get_contents());ob_end_clean();  if ($this->_tpl_vars['vardef']['gen'] && $this->_tpl_vars['vardef']['default'] && $this->_tpl_vars['link']): ?>
    <?php ob_start();  if (is_string ( $this->_tpl_vars['vardef']['default'] )):  echo $this->_tpl_vars['vardef']['default'];  else:  echo $this->_tpl_vars['link'];  endif;  $this->_smarty_vars['capture']['getDefault'] = ob_get_contents();  $this->assign('default', ob_get_contents());ob_end_clean(); ?>
    <?php echo smarty_function_sugar_replace_vars(array('subject' => $this->_tpl_vars['default'],'use_curly' => true,'assign' => 'link','fields' => $this->_tpl_vars['parentFieldArray']), $this);?>

<?php endif; ?>

<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['link'])) ? $this->_run_mod_handler('to_url', true, $_tmp) : smarty_modifier_to_url($_tmp)); ?>
" <?php if ($this->_tpl_vars['displayParams']['link_target']): ?>target='<?php echo $this->_tpl_vars['displayParams']['link_target']; ?>
'<?php elseif ($this->_tpl_vars['vardef']['link_target']): ?>target='<?php echo $this->_tpl_vars['vardef']['link_target']; ?>
'<?php endif; ?>><?php echo $this->_tpl_vars['link']; ?>
</a>
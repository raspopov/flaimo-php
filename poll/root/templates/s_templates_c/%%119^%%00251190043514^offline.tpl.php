<?php /* Smarty version 2.6.2, created on 2004-08-19 15:34:47
         compiled from networld_leute_dschungel_oesis/offline.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'networld_leute_dschungel_oesis/offline.tpl', 1, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => "poll.conf"), $this);?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
	<head>
		<title>Umfrage - <?php echo $this->_tpl_vars['poll_title']; ?>
</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			<!--
			@import url("generalstyles.css");
			@import url("<?php echo $this->_tpl_vars['path']; ?>
dschungel.css");
			-->
		</style>
	</head>
	<body>
		<h1><span><?php echo $this->_tpl_vars['poll_title']; ?>
</span></h1>
		<p><?php echo $this->_tpl_vars['poll_description']; ?>
</p>
		<p id="offline"><?php echo $this->_config[0]['vars']['poll_not_online']; ?>
</p>
		<script type="text/javascript">
		<!--
			var ts=new Date(); ts=ts.getTime();
			document.write('<img src="/cgi-bin/ivw/CP/<?php echo $this->_tpl_vars['cp_type'];  echo $this->_tpl_vars['L1_cat']; ?>
/umfrage<?php echo $this->_tpl_vars['L2_cat']; ?>
/<?php echo $this->_tpl_vars['cp']; ?>
?' + ts + '" width="1" height="1" alt="Zählpixel" />');
		//-->
		</script>
		<noscript>
		<img src="/cgi-bin/ivw/CP/<?php echo $this->_tpl_vars['cp_type'];  echo $this->_tpl_vars['L1_cat']; ?>
/umfrage<?php echo $this->_tpl_vars['L2_cat']; ?>
/<?php echo $this->_tpl_vars['cp']; ?>
" width="1" height="1" alt="Zählpixel" />
		</noscript>
	</body>
</html>
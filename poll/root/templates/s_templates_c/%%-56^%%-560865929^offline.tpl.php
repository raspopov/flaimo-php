<?php /* Smarty version 2.6.2, created on 2004-08-10 11:43:25
         compiled from networld_leute_fernsehen_expedition_wer_soll_raus/offline.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'networld_leute_fernsehen_expedition_wer_soll_raus/offline.tpl', 1, false),)), $this); ?>
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
expedition.css");
			-->
		</style>
	</head>
	<body>
		<h1><span><?php echo $this->_tpl_vars['poll_title']; ?>
</span></h1>
		<p id="offline"><?php echo $this->_config[0]['vars']['poll_not_online']; ?>
</p>
		<script language="JavaScript">
		<!--
			var ts=new Date(); ts=ts.getTime();
			document.write('<img src="/cgi-bin/ivw/CP/c/nn/umfrage?' + ts + '" width="1" height="1"><br>');
		//-->
		</script>
		<noscript>
		<img src="/cgi-bin/ivw/CP/c/nn/umfrage" width="1" height="1"><br>
		</noscript>
	</body>
</html>
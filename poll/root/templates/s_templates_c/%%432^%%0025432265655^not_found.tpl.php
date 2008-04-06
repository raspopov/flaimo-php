<?php /* Smarty version 2.6.2, created on 2004-08-02 17:03:36
         compiled from not_found.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'not_found.tpl', 1, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => "poll.conf"), $this);?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
<head>
<title>Umfrage</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php echo $this->_config[0]['vars']['poll_not_found']; ?>

</body>
</html>
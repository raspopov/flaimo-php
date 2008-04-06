<?php
require_once 'Smarty.class.php';
class PollSmarty extends Smarty {
   function __construct() {
		$this->Smarty();
		$path = '/networld_classes/poll/root/templates/';
		$this->template_dir = $path . 's_templates';
		$this->compile_dir = $path . 's_templates_c';
		$this->config_dir = $path . 's_configs';
		$this->cache_dir = $path . 's_cache';
		$this->caching = 0;
		$this->compile_check = true;
		$this->debugging = true;
   } // end constructor
} // end class
?>

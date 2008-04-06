<?php /* Smarty version 2.6.2, created on 2004-08-17 15:58:33
         compiled from networld_default/vote.tpl */ ?>
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
poll_networld_default.css");
			-->
		</style>
	</head>
	<body>
		<h4><span>News Networld</span> Umfrage</h4>
		<p>Stimmen Sie mit. Und schauen Sie, wie Österreich darüber denkt! </p>
		<h1><?php echo $this->_tpl_vars['poll_title']; ?>
</h1>
		<p><?php echo $this->_tpl_vars['poll_description']; ?>
</p>
		<?php if ($this->_tpl_vars['count_errors'] > 0): ?>
			<ul class="errors">
			<?php if (isset($this->_sections['error'])) unset($this->_sections['error']);
$this->_sections['error']['name'] = 'error';
$this->_sections['error']['loop'] = is_array($_loop=$this->_tpl_vars['errors']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['error']['show'] = true;
$this->_sections['error']['max'] = $this->_sections['error']['loop'];
$this->_sections['error']['step'] = 1;
$this->_sections['error']['start'] = $this->_sections['error']['step'] > 0 ? 0 : $this->_sections['error']['loop']-1;
if ($this->_sections['error']['show']) {
    $this->_sections['error']['total'] = $this->_sections['error']['loop'];
    if ($this->_sections['error']['total'] == 0)
        $this->_sections['error']['show'] = false;
} else
    $this->_sections['error']['total'] = 0;
if ($this->_sections['error']['show']):

            for ($this->_sections['error']['index'] = $this->_sections['error']['start'], $this->_sections['error']['iteration'] = 1;
                 $this->_sections['error']['iteration'] <= $this->_sections['error']['total'];
                 $this->_sections['error']['index'] += $this->_sections['error']['step'], $this->_sections['error']['iteration']++):
$this->_sections['error']['rownum'] = $this->_sections['error']['iteration'];
$this->_sections['error']['index_prev'] = $this->_sections['error']['index'] - $this->_sections['error']['step'];
$this->_sections['error']['index_next'] = $this->_sections['error']['index'] + $this->_sections['error']['step'];
$this->_sections['error']['first']      = ($this->_sections['error']['iteration'] == 1);
$this->_sections['error']['last']       = ($this->_sections['error']['iteration'] == $this->_sections['error']['total']);
?>
				<li><?php echo $this->_tpl_vars['errors'][$this->_sections['error']['index']]; ?>
</li>
			<?php endfor; endif; ?>
			</ul>
		<?php endif; ?>

		<form action="<?php echo $this->_tpl_vars['php_self']; ?>
" method="post">
				<?php if (isset($this->_sections['id'])) unset($this->_sections['id']);
$this->_sections['id']['name'] = 'id';
$this->_sections['id']['loop'] = is_array($_loop=$this->_tpl_vars['option_ids']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['id']['show'] = true;
$this->_sections['id']['max'] = $this->_sections['id']['loop'];
$this->_sections['id']['step'] = 1;
$this->_sections['id']['start'] = $this->_sections['id']['step'] > 0 ? 0 : $this->_sections['id']['loop']-1;
if ($this->_sections['id']['show']) {
    $this->_sections['id']['total'] = $this->_sections['id']['loop'];
    if ($this->_sections['id']['total'] == 0)
        $this->_sections['id']['show'] = false;
} else
    $this->_sections['id']['total'] = 0;
if ($this->_sections['id']['show']):

            for ($this->_sections['id']['index'] = $this->_sections['id']['start'], $this->_sections['id']['iteration'] = 1;
                 $this->_sections['id']['iteration'] <= $this->_sections['id']['total'];
                 $this->_sections['id']['index'] += $this->_sections['id']['step'], $this->_sections['id']['iteration']++):
$this->_sections['id']['rownum'] = $this->_sections['id']['iteration'];
$this->_sections['id']['index_prev'] = $this->_sections['id']['index'] - $this->_sections['id']['step'];
$this->_sections['id']['index_next'] = $this->_sections['id']['index'] + $this->_sections['id']['step'];
$this->_sections['id']['first']      = ($this->_sections['id']['iteration'] == 1);
$this->_sections['id']['last']       = ($this->_sections['id']['iteration'] == $this->_sections['id']['total']);
?>
					<?php if ($this->_tpl_vars['display_active_only'] == FALSE || ( $this->_tpl_vars['option_active'][$this->_sections['id']['index']] == TRUE && $this->_tpl_vars['display_active_only'] == TRUE )): ?>
						<h2>
						<?php if ($this->_tpl_vars['option_active'][$this->_sections['id']['index']] == TRUE): ?>
							<?php if ($this->_tpl_vars['multiple_choise'] == TRUE): ?>
								<input type="checkbox" name="selection[<?php echo $this->_tpl_vars['option_ids'][$this->_sections['id']['index']]; ?>
]" id="selection-<?php echo $this->_tpl_vars['option_ids'][$this->_sections['id']['index']]; ?>
" value="<?php echo $this->_tpl_vars['option_ids'][$this->_sections['id']['index']]; ?>
" />
							<?php else: ?>
								<input type="radio" name="selection" id="selection-<?php echo $this->_tpl_vars['option_ids'][$this->_sections['id']['index']]; ?>
" value="<?php echo $this->_tpl_vars['option_ids'][$this->_sections['id']['index']]; ?>
" />
							<?php endif; ?>
							<label for="selection-<?php echo $this->_tpl_vars['option_ids'][$this->_sections['id']['index']]; ?>
"><?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>
</label></h2>
							<?php if ($this->_tpl_vars['option_descriptions'][$this->_sections['id']['index']] != ""): ?>
								<p class="description"><?php echo $this->_tpl_vars['option_descriptions'][$this->_sections['id']['index']]; ?>
&nbsp;</p>
							<?php endif; ?>
						<?php else: ?>
							<span class="option-placeholder"></span>
							<label for="selection-<?php echo $this->_tpl_vars['option_ids'][$this->_sections['id']['index']]; ?>
" class="inactive"><?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>
</label>
							</h2>
							<?php if ($this->_tpl_vars['option_descriptions'][$this->_sections['id']['index']] != ""): ?>
								<p class="inactive-description"><?php echo $this->_tpl_vars['option_descriptions'][$this->_sections['id']['index']]; ?>
&nbsp;</p>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				<?php endfor; else: ?>
					<p>Es stehen keine Antwortmöglichkeiten für diese Umfrage zur Verfügung</p>
				<?php endif; ?>
			<?php echo $this->_tpl_vars['form_token']; ?>

			<input type="hidden" name="poll" value="<?php echo $this->_tpl_vars['poll_id']; ?>
" />
			<input type="submit" name="vote" id="vote" value="<?php echo $this->_tpl_vars['button_label']; ?>
" />
		</form>
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
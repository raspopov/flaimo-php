<?php /* Smarty version 2.6.2, created on 2004-08-10 12:08:31
         compiled from networld_leute_fernsehen_expedition_wer_soll_raus/result.tpl */ ?>
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
		<p id="poll-description"><?php echo $this->_tpl_vars['poll_description']; ?>
</p>
		<form action="<?php echo $this->_tpl_vars['php_self']; ?>
" method="post" id="polloptions">
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
						<?php if ($this->_tpl_vars['option_active'][$this->_sections['id']['index']] == TRUE): ?>
							<div class="active-participant">
								<img src="<?php echo $this->_tpl_vars['path'];  echo $this->_tpl_vars['option_ids'][$this->_sections['id']['index']]; ?>
.jpg" class="portait" alt="<?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>
 - Portrait" />
								<p class="place">Platz <?php echo $this->_tpl_vars['option_place'][$this->_sections['id']['index']]; ?>
</p>
								<h2><?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>
</h2>
								<span class="active-block" style="width: <?php echo $this->_tpl_vars['option_percent'][$this->_sections['id']['index']]+1; ?>
px;"></span>
								<p class="percent"><?php echo $this->_tpl_vars['option_percent_exact'][$this->_sections['id']['index']]; ?>
%</p>
						<?php else: ?>
							<div class="inactive-participant">
								<img src="<?php echo $this->_tpl_vars['path'];  echo $this->_tpl_vars['option_ids'][$this->_sections['id']['index']]; ?>
_out.jpg" class="portait" alt="<?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>
 - Portrait" />
								<p class="place">Platz <?php echo $this->_tpl_vars['option_place'][$this->_sections['id']['index']]; ?>
</p>
								<h2><?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>
</h2>
								<span class="block" style="width: <?php echo $this->_tpl_vars['option_percent'][$this->_sections['id']['index']]+1; ?>
px;"></span>
								<p class="percent"><?php echo $this->_tpl_vars['option_percent_exact'][$this->_sections['id']['index']]; ?>
%</p>
						<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endfor; else: ?>
					<p>Es stehen keine Antwortmöglichkeiten für diese Umfrage zur Verfügung</p>
				<?php endif; ?>
			<?php echo $this->_tpl_vars['form_token']; ?>

			<input type="hidden" name="poll" value="<?php echo $this->_tpl_vars['poll_id']; ?>
" />
			<input type="hidden" name="vote" value="1" />
		</form>
		<?php if ($this->_tpl_vars['show_revote_link'] == TRUE): ?>
			<p id="voteagain"><a href="<?php echo $this->_tpl_vars['php_self']; ?>
?poll=<?php echo $this->_tpl_vars['poll_id']; ?>
"><img src="<?php echo $this->_tpl_vars['path']; ?>
return.gif" width="250" height="41" alt="Nocheinmal Abstimmen" /></a></p>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['show_next_revote'] == TRUE): ?>
			<p id="nextvote">Nächste Abstimmung in <span><?php echo $this->_tpl_vars['next_revote_min']; ?>
</span> Minuten</p>
		<?php endif; ?>

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
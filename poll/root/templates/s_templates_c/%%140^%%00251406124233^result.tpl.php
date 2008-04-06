<?php /* Smarty version 2.6.2, created on 2004-09-24 12:57:23
         compiled from networld_leute_single_fuer_eberharter/result.tpl */ ?>
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
eberharter.css");
			-->
		</style>
	</head>
	<body>
		<h6><span>Networld Umfrage</span></h6>
		<h2><span>Österreich jagt den Superstar</span></h2>
		<div id="participants">
			<div id="pollcontent">
				<h1><?php echo $this->_tpl_vars['poll_title']; ?>
</h1>
				<p><?php echo $this->_tpl_vars['poll_description']; ?>
</p>
				<table><tr valign="top"><td>
					<?php if (isset($this->_sections['id'])) unset($this->_sections['id']);
$this->_sections['id']['name'] = 'id';
$this->_sections['id']['loop'] = is_array($_loop=$this->_tpl_vars['option_ids']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['id']['max'] = (int)$this->_tpl_vars['sum_polloptions_half'];
$this->_sections['id']['show'] = true;
if ($this->_sections['id']['max'] < 0)
    $this->_sections['id']['max'] = $this->_sections['id']['loop'];
$this->_sections['id']['step'] = 1;
$this->_sections['id']['start'] = $this->_sections['id']['step'] > 0 ? 0 : $this->_sections['id']['loop']-1;
if ($this->_sections['id']['show']) {
    $this->_sections['id']['total'] = min(ceil(($this->_sections['id']['step'] > 0 ? $this->_sections['id']['loop'] - $this->_sections['id']['start'] : $this->_sections['id']['start']+1)/abs($this->_sections['id']['step'])), $this->_sections['id']['max']);
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
							<div class="participant">
								<?php if ($this->_tpl_vars['option_active'][$this->_sections['id']['index']] == TRUE): ?>
									<img src="<?php echo $this->_tpl_vars['option_image'][$this->_sections['id']['index']]; ?>
" alt="<?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>
" class="portrait" />
									<h3><?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>

									<?php if ($this->_tpl_vars['show_percent'] == TRUE): ?>
										<small><?php echo $this->_tpl_vars['option_percent_exact'][$this->_sections['id']['index']]; ?>
 %
										<?php if ($this->_tpl_vars['show_absolute'] == TRUE): ?>
											 (<?php echo $this->_tpl_vars['option_votes'][$this->_sections['id']['index']]; ?>
 Stimmen)
										<?php endif; ?>
										</small>
									<?php elseif ($this->_tpl_vars['show_absolute'] == TRUE): ?>
										<small><?php echo $this->_tpl_vars['option_votes'][$this->_sections['id']['index']]; ?>
 Stimmen</small>
									<?php endif; ?>
									</h3>
									<img src="<?php echo $this->_tpl_vars['path']; ?>
pix.gif" alt="Balken" height="23" class="block" width="<?php echo $this->_tpl_vars['option_percent'][$this->_sections['id']['index']]*2+1; ?>
" />
								<?php else: ?>
									<img src="<?php echo $this->_tpl_vars['option_inactive_image'][$this->_sections['id']['index']]; ?>
" alt="<?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>
" class="portrait" />
									<h3 class="inactive"><?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>

									<?php if ($this->_tpl_vars['show_percent'] == TRUE): ?>
										<small><?php echo $this->_tpl_vars['option_percent_exact'][$this->_sections['id']['index']]; ?>
 %
										<?php if ($this->_tpl_vars['show_absolute'] == TRUE): ?>
											 (<?php echo $this->_tpl_vars['option_votes'][$this->_sections['id']['index']]; ?>
 Stimmen)
										<?php endif; ?>
										</small>
									<?php elseif ($this->_tpl_vars['show_absolute'] == TRUE): ?>
										<small><?php echo $this->_tpl_vars['option_votes'][$this->_sections['id']['index']]; ?>
 Stimmen</small>
									<?php endif; ?>
									</h3>
									<img src="<?php echo $this->_tpl_vars['path']; ?>
pix_inactive.gif" alt="Balken" height="23" class="block" width="<?php echo $this->_tpl_vars['option_percent'][$this->_sections['id']['index']]*2+1; ?>
" />
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php endfor; else: ?>
						<p>Es stehen keine Antwortmöglichkeiten für diese Umfrage zur Verfügung</p>
					<?php endif; ?>
				</td><td>
				<?php if (isset($this->_sections['id'])) unset($this->_sections['id']);
$this->_sections['id']['name'] = 'id';
$this->_sections['id']['loop'] = is_array($_loop=$this->_tpl_vars['option_ids']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['id']['start'] = (int)$this->_tpl_vars['sum_polloptions_half'];
$this->_sections['id']['show'] = true;
$this->_sections['id']['max'] = $this->_sections['id']['loop'];
$this->_sections['id']['step'] = 1;
if ($this->_sections['id']['start'] < 0)
    $this->_sections['id']['start'] = max($this->_sections['id']['step'] > 0 ? 0 : -1, $this->_sections['id']['loop'] + $this->_sections['id']['start']);
else
    $this->_sections['id']['start'] = min($this->_sections['id']['start'], $this->_sections['id']['step'] > 0 ? $this->_sections['id']['loop'] : $this->_sections['id']['loop']-1);
if ($this->_sections['id']['show']) {
    $this->_sections['id']['total'] = min(ceil(($this->_sections['id']['step'] > 0 ? $this->_sections['id']['loop'] - $this->_sections['id']['start'] : $this->_sections['id']['start']+1)/abs($this->_sections['id']['step'])), $this->_sections['id']['max']);
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
							<div class="participant" title="<?php echo $this->_tpl_vars['option_description'][$this->_sections['id']['index']]; ?>
">
								<?php if ($this->_tpl_vars['option_active'][$this->_sections['id']['index']] == TRUE): ?>
									<img src="<?php echo $this->_tpl_vars['option_image'][$this->_sections['id']['index']]; ?>
" alt="<?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>
" class="portrait" />
									<h3><?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>

									<?php if ($this->_tpl_vars['show_percent'] == TRUE): ?>
										<small><?php echo $this->_tpl_vars['option_percent_exact'][$this->_sections['id']['index']]; ?>
 %
										<?php if ($this->_tpl_vars['show_absolute'] == TRUE): ?>
											 (<?php echo $this->_tpl_vars['option_votes'][$this->_sections['id']['index']]; ?>
 Stimmen)
										<?php endif; ?>
										</small>
									<?php elseif ($this->_tpl_vars['show_absolute'] == TRUE): ?>
										<small><?php echo $this->_tpl_vars['option_votes'][$this->_sections['id']['index']]; ?>
 Stimmen</small>
									<?php endif; ?>
									</h3>
									<img src="<?php echo $this->_tpl_vars['path']; ?>
pix.gif" alt="Balken" height="23" class="block" width="<?php echo $this->_tpl_vars['option_percent'][$this->_sections['id']['index']]*2+1; ?>
" />
								<?php else: ?>
									<img src="<?php echo $this->_tpl_vars['option_inactive_image'][$this->_sections['id']['index']]; ?>
" alt="<?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>
" class="portrait" />
									<h3 class="inactive"><?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>

									<?php if ($this->_tpl_vars['show_percent'] == TRUE): ?>
										<small><?php echo $this->_tpl_vars['option_percent_exact'][$this->_sections['id']['index']]; ?>
 %
										<?php if ($this->_tpl_vars['show_absolute'] == TRUE): ?>
											 (<?php echo $this->_tpl_vars['option_votes'][$this->_sections['id']['index']]; ?>
 Stimmen)
										<?php endif; ?>
										</small>
									<?php elseif ($this->_tpl_vars['show_absolute'] == TRUE): ?>
										<small><?php echo $this->_tpl_vars['option_votes'][$this->_sections['id']['index']]; ?>
 Stimmen</small>
									<?php endif; ?>
									</h3>
									<img src="<?php echo $this->_tpl_vars['path']; ?>
pix_inactive.gif" alt="Balken" height="23" class="block" width="<?php echo $this->_tpl_vars['option_percent'][$this->_sections['id']['index']]*2+1; ?>
" />
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php endfor; endif; ?>
				</td></tr></table>
			</div>
		</div>
		<div id="submit">
			<?php if ($this->_tpl_vars['show_revote_link'] == TRUE): ?>
				<a href="<?php echo $this->_tpl_vars['php_self']; ?>
?poll=<?php echo $this->_tpl_vars['poll_id']; ?>
"><img src="<?php echo $this->_tpl_vars['path']; ?>
revote" width="163" height="25" alt="Nochmal abstimmen" /></a>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['show_next_revote'] == TRUE): ?>
				<b id="nextvote">Nächste Abstimmung in <span><?php echo $this->_tpl_vars['next_revote_min']; ?>
</span> Minuten</b>
			<?php endif; ?>
		</div>



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
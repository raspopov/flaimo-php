<?php /* Smarty version 2.6.2, created on 2004-08-24 14:41:40
         compiled from networld_leute_dschungel_oesis/result.tpl */ ?>
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
		<h1><span>Stars im Dschungel</span></h1>
		<h3><?php echo $this->_tpl_vars['poll_title']; ?>
</h3>
		<p><a href="#" id="post-suggestion" onClick="void window.open('/nw1/gen/slideshows/slider.php?show=greetings/leute/dschungel_tv/', 'greedings', 'width=680,height=560,menubar=0,resizable=0,toolbar=0,scrollbars=0,location=0, copyhistory=0,status=0,directories=0').focus();return false;">Vorschlag posten</a><?php echo $this->_tpl_vars['poll_description']; ?>
</p>
		<div id="participants">
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
				<div class="participant" title="<?php echo $this->_tpl_vars['option_description'][$this->_sections['id']['index']]; ?>
">
					<img src="<?php echo $this->_tpl_vars['option_image'][$this->_sections['id']['index']]; ?>
" alt="<?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>
" class="portrait" />
					<h2><?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>

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
					</h2>
					<img src="<?php echo $this->_tpl_vars['path']; ?>
pix.gif" alt="Balken" height="24" class="block" width="<?php echo $this->_tpl_vars['option_percent'][$this->_sections['id']['index']]*2+1; ?>
" />
				</div>
			<?php endif; ?>
		<?php endfor; else: ?>
			<p>Es stehen keine Antwortmöglichkeiten für diese Umfrage zur Verfügung</p>
		<?php endif; ?>
		</div>

		<?php if ($this->_tpl_vars['show_revote_link'] == TRUE): ?>
			<p id="voteagain"><a href="<?php echo $this->_tpl_vars['php_self']; ?>
?poll=<?php echo $this->_tpl_vars['poll_id']; ?>
">» Nochmal Abstimmen »</a></p>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['show_next_revote'] == TRUE): ?>
			<p id="nextvote">Nächste Abstimmung in <span><?php echo $this->_tpl_vars['next_revote_min']; ?>
</span> Minuten</p>
		<?php endif; ?>

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
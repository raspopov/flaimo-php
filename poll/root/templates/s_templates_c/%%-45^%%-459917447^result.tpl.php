<?php /* Smarty version 2.6.2, created on 2004-08-12 14:09:16
         compiled from networld_sport_olympia2004_metals/result.tpl */ ?>
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
poll_olympia2004_metals.css");

			body {
				background: #fff url(<?php echo $this->_tpl_vars['path']; ?>
background.jpg) left top no-repeat;
			}
			-->
		</style>
	</head>
	<body>
		<h1><?php echo $this->_tpl_vars['poll_title']; ?>
</h1>
		<p><?php echo $this->_tpl_vars['poll_description']; ?>
</p>

		<table id="result">
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
				<tr>
					<th><h2><?php echo $this->_tpl_vars['option_titles'][$this->_sections['id']['index']]; ?>
</h2></th>
					<td>
						<?php if ($this->_tpl_vars['option_active'][$this->_sections['id']['index']] == TRUE): ?>
							<span class="active-block" style="width: <?php echo $this->_tpl_vars['option_percent'][$this->_sections['id']['index']]*4+1; ?>
px;"></span>
						<?php else: ?>
							<span class="block" style="width: <?php echo $this->_tpl_vars['option_percent'][$this->_sections['id']['index']]*4+1; ?>
px;"></span>
						<?php endif; ?>
							<?php if ($this->_tpl_vars['show_percent'] == TRUE): ?>
								<p class="votes"><?php echo $this->_tpl_vars['option_percent_exact'][$this->_sections['id']['index']]; ?>
 %
								<?php if ($this->_tpl_vars['show_absolute'] == TRUE): ?>
									<small>(<?php echo $this->_tpl_vars['option_votes'][$this->_sections['id']['index']]; ?>
 Stimmen)</small>
								<?php endif; ?>
								</p>
							<?php elseif ($this->_tpl_vars['show_absolute'] == TRUE): ?>
								<p class="votes"><?php echo $this->_tpl_vars['option_votes'][$this->_sections['id']['index']]; ?>
 Stimmen</p>
							<?php endif; ?>
					</td>
				</tr>
			<?php endif; ?>
		<?php endfor; else: ?>
			<p>Es stehen keine Antwortmöglichkeiten für diese Umfrage zur Verfügung</p>
		<?php endif; ?>
		</table>

		<?php if ($this->_tpl_vars['show_revote_link'] == TRUE): ?>
			<p id="voteagain"><a href="<?php echo $this->_tpl_vars['php_self']; ?>
?poll=<?php echo $this->_tpl_vars['poll_id']; ?>
">» Nochmal Abstimmen »</a></p>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['show_next_revote'] == TRUE): ?>
			<p id="nextvote">Nächste Abstimmung in <?php echo $this->_tpl_vars['next_revote_min']; ?>
 Minuten</p>
		<?php endif; ?>

		<script type="text/javascript">
		<!--
			var ts=new Date(); ts=ts.getTime();
			document.write('<img src="http://www.news.at/cgi-bin/ivw/CP/c/nn/umfrage?' + ts + '" width="1" height="1" alt="Zählpixel" />');
		//-->
		</script>
		<noscript>
		<img src="http://www.news.at/cgi-bin/ivw/CP/c/nn/umfrage" width="1" height="1" alt="Zählpixel" />
		</noscript>
	</body>
</html>
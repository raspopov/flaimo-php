<?php /* Smarty version 2.3.0, created on 2002-09-16 20:53:21
         compiled from index.tpl.php */ ?>
<?php echo '<?xml version="1.0" encoding="iso-8859-1"?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
    <title>ShoutBox</title>
      <meta http-equiv="MSThemeCompatible" content="Yes" />
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <meta http-equiv="Content-Style-Type" content="text/css" />
      <meta http-equiv="expires" content="<?php echo $this->_tpl_vars['META_EXPIRES']; ?>
" />
      <meta http-equiv="pragma" content="no-cache" />
 <style type="text/css">
<!--
.shoutbox {
	margin: 1px;
	padding: 7px;
	height: 450px;
	width: 250px;
	font: small Georgia, "Times New Roman", Times, serif;
	background: #FFFFFF;
	border: medium dashed #0000FF;
	overflow: scroll;
}
-->
</style>
  </head>
  <body>
    <div class="shoutbox">
      <h1>ShoutBox</h1> 
      <strong><?php echo $this->_tpl_vars['FEEDBACK_MESSAGE']; ?>
</strong>
      <?php if (isset($this->_sections["shoutbox"])) unset($this->_sections["shoutbox"]);
$this->_sections["shoutbox"]['name'] = "shoutbox";
$this->_sections["shoutbox"]['loop'] = is_array($this->_tpl_vars['SB_TEXT']) ? count($this->_tpl_vars['SB_TEXT']) : max(0, (int)$this->_tpl_vars['SB_TEXT']);
$this->_sections["shoutbox"]['show'] = true;
$this->_sections["shoutbox"]['max'] = $this->_sections["shoutbox"]['loop'];
$this->_sections["shoutbox"]['step'] = 1;
$this->_sections["shoutbox"]['start'] = $this->_sections["shoutbox"]['step'] > 0 ? 0 : $this->_sections["shoutbox"]['loop']-1;
if ($this->_sections["shoutbox"]['show']) {
    $this->_sections["shoutbox"]['total'] = $this->_sections["shoutbox"]['loop'];
    if ($this->_sections["shoutbox"]['total'] == 0)
        $this->_sections["shoutbox"]['show'] = false;
} else
    $this->_sections["shoutbox"]['total'] = 0;
if ($this->_sections["shoutbox"]['show']):

            for ($this->_sections["shoutbox"]['index'] = $this->_sections["shoutbox"]['start'], $this->_sections["shoutbox"]['iteration'] = 1;
                 $this->_sections["shoutbox"]['iteration'] <= $this->_sections["shoutbox"]['total'];
                 $this->_sections["shoutbox"]['index'] += $this->_sections["shoutbox"]['step'], $this->_sections["shoutbox"]['iteration']++):
$this->_sections["shoutbox"]['rownum'] = $this->_sections["shoutbox"]['iteration'];
$this->_sections["shoutbox"]['index_prev'] = $this->_sections["shoutbox"]['index'] - $this->_sections["shoutbox"]['step'];
$this->_sections["shoutbox"]['index_next'] = $this->_sections["shoutbox"]['index'] + $this->_sections["shoutbox"]['step'];
$this->_sections["shoutbox"]['first']      = ($this->_sections["shoutbox"]['iteration'] == 1);
$this->_sections["shoutbox"]['last']       = ($this->_sections["shoutbox"]['iteration'] == $this->_sections["shoutbox"]['total']);
?>
        <h4><?php echo $this->_tpl_vars['SB_DATE'][$this->_sections['shoutbox']['index']]; ?>
</h4>
        <p>
        <big>
          <?php if ($this->_tpl_vars['SB_EMAIL'][$this->_sections['shoutbox']['index']] != ""): ?>
            <a href="<?php echo $this->_tpl_vars['SB_EMAIL'][$this->_sections['shoutbox']['index']]; ?>
"><?php echo $this->_tpl_vars['SB_NAME'][$this->_sections['shoutbox']['index']]; ?>
</a>
          <?php else: ?>
            <?php echo $this->_tpl_vars['SB_NAME'][$this->_sections['shoutbox']['index']]; ?>

          <?php endif; ?>
        </big><br />
        <small><?php echo $this->_tpl_vars['SB_TIME'][$this->_sections['shoutbox']['index']]; ?>
</small>
        <br />
        <?php echo $this->_tpl_vars['SB_TEXT'][$this->_sections['shoutbox']['index']]; ?>

        </p>
      <?php endfor; else: ?>
        <p>Keine Shouts vorhanden</p>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['SHOW_SB_FORM'] == 1): ?>
        <form action="<?php echo $this->_tpl_vars['PHP_SELF']; ?>
" id="shoutbox_form" name="shoutbox_form" method="post" title="ShoutBox">
          <label for="sb_name" class="sblabel">Name:</label><br />
          <input type="text" name="sb_name" id="sb_name" class="sbinput" value = "<?php echo $this->_tpl_vars['SESS_NAME']; ?>
" /><br />
          <label for="sb_mail" class="sblabel">E-Mail:</label><br />
          <input type="text" name="sb_mail" id="sb_mail" class="sbinput" value="<?php echo $this->_tpl_vars['SESS_EMAIL']; ?>
" /><br />
          <label for="sb_message" class="sblabel">Message:</label><br />
          <textarea name="sb_message" id="sb_message" class="sbtextarea" cols="20" rows="3" ></textarea><br />
          <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['TOKEN']; ?>
" />
          <input type="submit" name="sb_submit" id="sb_submit" value="Shout Out" class="sbsumbit" />
        </form>
      <?php endif; ?>
    </div>
  </body>
</html>
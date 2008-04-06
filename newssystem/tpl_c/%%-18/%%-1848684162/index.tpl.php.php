<?php /* Smarty version 2.3.0, created on 2002-10-05 13:19:15
         compiled from index.tpl.php */ ?>
<?php echo '<?xml version="1.0" encoding="iso-8859-1"?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->_tpl_vars['LANG_ISO']; ?>
" lang="<?php echo $this->_tpl_vars['LANG_ISO']; ?>
">
  <head>
    <title><?php echo $this->_tpl_vars['PAGE_TITLE']; ?>
</title>
      <meta http-equiv="MSThemeCompatible" content="Yes" />
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <meta http-equiv="Content-Style-Type" content="text/css" />
      <meta http-equiv="expires" content="<?php echo $this->_tpl_vars['META_EXPIRES']; ?>
" />
      <meta http-equiv="pragma" content="no-cache" />
      <style type="text/css">
      <!--
      @import url("newssystem.css");
      -->
      </style>
  
  </head>
  <body>
    <div id="nsmenu">
      <?php if ($this->_tpl_vars['COUNT_CATEGORIES'] > 0): ?>
        <h2 class="nsmenu"><a href="<?php echo $this->_tpl_vars['PHP_SELF']; ?>
" style="color:white;text-decoration:none;"><?php echo $this->_tpl_vars['LANG_MENU_TITLE']; ?>
</a></h2>
        <div class="nsmenucontent">
        <?php if (isset($this->_sections["menu"])) unset($this->_sections["menu"]);
$this->_sections["menu"]['name'] = "menu";
$this->_sections["menu"]['loop'] = is_array($this->_tpl_vars['MENU_CAT_NR']) ? count($this->_tpl_vars['MENU_CAT_NR']) : max(0, (int)$this->_tpl_vars['MENU_CAT_NR']);
$this->_sections["menu"]['show'] = true;
$this->_sections["menu"]['max'] = $this->_sections["menu"]['loop'];
$this->_sections["menu"]['step'] = 1;
$this->_sections["menu"]['start'] = $this->_sections["menu"]['step'] > 0 ? 0 : $this->_sections["menu"]['loop']-1;
if ($this->_sections["menu"]['show']) {
    $this->_sections["menu"]['total'] = $this->_sections["menu"]['loop'];
    if ($this->_sections["menu"]['total'] == 0)
        $this->_sections["menu"]['show'] = false;
} else
    $this->_sections["menu"]['total'] = 0;
if ($this->_sections["menu"]['show']):

            for ($this->_sections["menu"]['index'] = $this->_sections["menu"]['start'], $this->_sections["menu"]['iteration'] = 1;
                 $this->_sections["menu"]['iteration'] <= $this->_sections["menu"]['total'];
                 $this->_sections["menu"]['index'] += $this->_sections["menu"]['step'], $this->_sections["menu"]['iteration']++):
$this->_sections["menu"]['rownum'] = $this->_sections["menu"]['iteration'];
$this->_sections["menu"]['index_prev'] = $this->_sections["menu"]['index'] - $this->_sections["menu"]['step'];
$this->_sections["menu"]['index_next'] = $this->_sections["menu"]['index'] + $this->_sections["menu"]['step'];
$this->_sections["menu"]['first']      = ($this->_sections["menu"]['iteration'] == 1);
$this->_sections["menu"]['last']       = ($this->_sections["menu"]['iteration'] == $this->_sections["menu"]['total']);
?>
          <a href="<?php echo $this->_tpl_vars['PHP_SELF']; ?>
?cat=<?php echo $this->_tpl_vars['MENU_CAT_NR'][$this->_sections['menu']['index']]; ?>
&#38;<?php echo $this->_tpl_vars['SESSION_NAME']; ?>
=<?php echo $this->_tpl_vars['SESSION_ID']; ?>
"><?php echo $this->_tpl_vars['MENU_CAT_NAME'][$this->_sections['menu']['index']]; ?>
 <small class="nr">(<?php echo $this->_tpl_vars['MENU_CAT_COUNT'][$this->_sections['menu']['index']]; ?>
)</small></a><br />
        <?php endfor; endif; ?>
      <?php endif; ?>
      <form method="post" action="<?php echo $this->_tpl_vars['PHP_SELF']; ?>
" title="<?php echo $this->_tpl_vars['FORM_SEARCH']; ?>
" style="text-align:center"> 
        <fieldset class="ns">
          <legend><?php echo $this->_tpl_vars['FORM_SEARCH']; ?>
</legend><br />
          <em><label for="search" class="ns"><?php echo $this->_tpl_vars['FORM_KEYWORD']; ?>
:</label></em><br />
          <input type="text" name="search" id="search" size="9" value="<?php echo $this->_tpl_vars['FORM_SEARCHSTRING']; ?>
" tabindex="1" class="ns" /><br />

          <?php if ($this->_tpl_vars['FORM_ADV_SEARCH'] == 1): ?>
            <em><label for="cat_search" class="ns"><?php echo $this->_tpl_vars['FORM_CATEGORY']; ?>
:</label></em><br />
            <select name="cat_search" id="cat_search" size="1" tabindex="2" class="ns">
              <option value="all"><?php echo $this->_tpl_vars['FORM_ALL']; ?>
</option>
              <?php if (isset($this->_sections["form_cat"])) unset($this->_sections["form_cat"]);
$this->_sections["form_cat"]['name'] = "form_cat";
$this->_sections["form_cat"]['loop'] = is_array($this->_tpl_vars['MENU_CAT_NR']) ? count($this->_tpl_vars['MENU_CAT_NR']) : max(0, (int)$this->_tpl_vars['MENU_CAT_NR']);
$this->_sections["form_cat"]['show'] = true;
$this->_sections["form_cat"]['max'] = $this->_sections["form_cat"]['loop'];
$this->_sections["form_cat"]['step'] = 1;
$this->_sections["form_cat"]['start'] = $this->_sections["form_cat"]['step'] > 0 ? 0 : $this->_sections["form_cat"]['loop']-1;
if ($this->_sections["form_cat"]['show']) {
    $this->_sections["form_cat"]['total'] = $this->_sections["form_cat"]['loop'];
    if ($this->_sections["form_cat"]['total'] == 0)
        $this->_sections["form_cat"]['show'] = false;
} else
    $this->_sections["form_cat"]['total'] = 0;
if ($this->_sections["form_cat"]['show']):

            for ($this->_sections["form_cat"]['index'] = $this->_sections["form_cat"]['start'], $this->_sections["form_cat"]['iteration'] = 1;
                 $this->_sections["form_cat"]['iteration'] <= $this->_sections["form_cat"]['total'];
                 $this->_sections["form_cat"]['index'] += $this->_sections["form_cat"]['step'], $this->_sections["form_cat"]['iteration']++):
$this->_sections["form_cat"]['rownum'] = $this->_sections["form_cat"]['iteration'];
$this->_sections["form_cat"]['index_prev'] = $this->_sections["form_cat"]['index'] - $this->_sections["form_cat"]['step'];
$this->_sections["form_cat"]['index_next'] = $this->_sections["form_cat"]['index'] + $this->_sections["form_cat"]['step'];
$this->_sections["form_cat"]['first']      = ($this->_sections["form_cat"]['iteration'] == 1);
$this->_sections["form_cat"]['last']       = ($this->_sections["form_cat"]['iteration'] == $this->_sections["form_cat"]['total']);
?>
                <option class="ns" value="<?php echo $this->_tpl_vars['MENU_CAT_NR'][$this->_sections['form_cat']['index']]; ?>
"><?php echo $this->_tpl_vars['MENU_CAT_NAME'][$this->_sections['form_cat']['index']]; ?>
</option>
              <?php endfor; endif; ?>
            </select><br />
            <em><label for="author" class="ns"><?php echo $this->_tpl_vars['FORM_AUTHOR']; ?>
:</label></em><br />
            <select name="author" id="author" size="1" tabindex="3" class="ns">
              <option value="all" selected="selected"><?php echo $this->_tpl_vars['FORM_ALL']; ?>
</option>        
              <?php if (isset($this->_sections["form_author"])) unset($this->_sections["form_author"]);
$this->_sections["form_author"]['name'] = "form_author";
$this->_sections["form_author"]['loop'] = is_array($this->_tpl_vars['MENU_AUTHOR_NR']) ? count($this->_tpl_vars['MENU_AUTHOR_NR']) : max(0, (int)$this->_tpl_vars['MENU_AUTHOR_NR']);
$this->_sections["form_author"]['show'] = true;
$this->_sections["form_author"]['max'] = $this->_sections["form_author"]['loop'];
$this->_sections["form_author"]['step'] = 1;
$this->_sections["form_author"]['start'] = $this->_sections["form_author"]['step'] > 0 ? 0 : $this->_sections["form_author"]['loop']-1;
if ($this->_sections["form_author"]['show']) {
    $this->_sections["form_author"]['total'] = $this->_sections["form_author"]['loop'];
    if ($this->_sections["form_author"]['total'] == 0)
        $this->_sections["form_author"]['show'] = false;
} else
    $this->_sections["form_author"]['total'] = 0;
if ($this->_sections["form_author"]['show']):

            for ($this->_sections["form_author"]['index'] = $this->_sections["form_author"]['start'], $this->_sections["form_author"]['iteration'] = 1;
                 $this->_sections["form_author"]['iteration'] <= $this->_sections["form_author"]['total'];
                 $this->_sections["form_author"]['index'] += $this->_sections["form_author"]['step'], $this->_sections["form_author"]['iteration']++):
$this->_sections["form_author"]['rownum'] = $this->_sections["form_author"]['iteration'];
$this->_sections["form_author"]['index_prev'] = $this->_sections["form_author"]['index'] - $this->_sections["form_author"]['step'];
$this->_sections["form_author"]['index_next'] = $this->_sections["form_author"]['index'] + $this->_sections["form_author"]['step'];
$this->_sections["form_author"]['first']      = ($this->_sections["form_author"]['iteration'] == 1);
$this->_sections["form_author"]['last']       = ($this->_sections["form_author"]['iteration'] == $this->_sections["form_author"]['total']);
?>
                <option class="ns" value="<?php echo $this->_tpl_vars['MENU_AUTHOR_NR'][$this->_sections['form_author']['index']]; ?>
"><?php echo $this->_tpl_vars['MENU_AUTHOR_NAME'][$this->_sections['form_author']['index']]; ?>
 (<?php echo $this->_tpl_vars['MENU_AUTHOR_COUNT'][$this->_sections['form_author']['index']]; ?>
)</option>
              <?php endfor; endif; ?>
            </select><br /><br />
          <?php else: ?>
            <br />
            <input type="hidden" name="cat_search" value="all" />
            <input type="hidden" name="author" value="all" />
          <?php endif; ?>
          <input type="hidden" name="startlisting" value="<?php echo $this->_tpl_vars['STARTLISTING']; ?>
" />
          <input type="hidden" name="<?php echo $this->_tpl_vars['SESSION_NAME']; ?>
" value="<?php echo $this->_tpl_vars['SESSION_ID']; ?>
" />
          <input type="submit" value="<?php echo $this->_tpl_vars['FORM_SEARCH_BUTTON']; ?>
" class="ns" /><br /><br />
          <?php if ($this->_tpl_vars['FORM_ADV_SEARCH'] == 0): ?>
            <small class="ns"><a href="<?php echo $this->_tpl_vars['PHP_SELF']; ?>
?advsearch=1&#38;<?php echo $this->_tpl_vars['SESSION_NAME']; ?>
=<?php echo $this->_tpl_vars['SESSION_ID']; ?>
"><?php echo $this->_tpl_vars['ADV_SEARCH_LINK']; ?>
</a></small>
          <?php endif; ?>
        </fieldset>
      </form>
      <form action="<?php echo $this->_tpl_vars['PHP_SELF']; ?>
" method="post" title="<?php echo $this->_tpl_vars['FORM_LANGUAGE']; ?>
" style="text-align:center">
        <fieldset class="ns">
          <legend><?php echo $this->_tpl_vars['FORM_LANGUAGE']; ?>
</legend><br />
          <label for="lang" class="ns"><?php echo $this->_tpl_vars['FORM_OSM']; ?>
</label>
          <br /><?php echo $this->_tpl_vars['FORM_DROPDOWN_LANG']; ?>
<br /><br />
          <label for="lang_content" class="ns"><?php echo $this->_tpl_vars['FORM_LANGUAGE_CONTENT']; ?>
</label>
          <br /><?php echo $this->_tpl_vars['FORM_DROPDOWN_LANG_CONTENT']; ?>
<br /><br />
          <label for="timeset" class="ns"><?php echo $this->_tpl_vars['FORM_TIME']; ?>
</label>
          <br /><?php echo $this->_tpl_vars['FORM_DROPDOWN_TIME']; ?>
<br />
          <input type="hidden" name="startlisting" value="<?php echo $this->_tpl_vars['STARTLISTING']; ?>
" />
          <input type="hidden" name="<?php echo $this->_tpl_vars['SESSION_NAME']; ?>
" value="<?php echo $this->_tpl_vars['SESSION_ID']; ?>
" />
          <br /><input type="submit" value="<?php echo $this->_tpl_vars['FORM_CHANGE']; ?>
" class="ns" />
        </fieldset>
      </form>
      <small class="ns">
       <?php echo $this->_tpl_vars['SUM_ALL_NEWS']; ?>
 <?php echo $this->_tpl_vars['LANG_NEWS']; ?>
<br />
       <?php echo $this->_tpl_vars['SUM_ALL_COMMENTS']; ?>
 <?php echo $this->_tpl_vars['LANG_COMMENTS']; ?>
<br />
       <?php echo $this->_tpl_vars['USER_ONLINE']; ?>
 <?php echo $this->_tpl_vars['LANG_USER_ONLINE']; ?>

      </small>     
      <br /><br />
      <small class="ns">
        <a href="<?php echo $this->_tpl_vars['TIMEZONE_LINK']; ?>
" target="_blank"><?php echo $this->_tpl_vars['LANG_TIMEZONE_IS']; ?>
 <?php echo $this->_tpl_vars['TIMEZONE']; ?>
</a><br />
        <strong><?php echo $this->_tpl_vars['LANG_TIMEZONE']; ?>
:<br /><?php echo $this->_tpl_vars['TIMEZONE_DATE']; ?>
 &#8211; <?php echo $this->_tpl_vars['TIMEZONE_TIME']; ?>
</strong>
      </small><br />
      <br /><div style="text-align:center;"><a href="rss.php?lang=<?php echo $this->_tpl_vars['RSS_LINK']; ?>
"><img src="xml.gif" alt="RSS Content Syndication" width="36" height="14" border="0" /></a></div>
     </div>
    </div>
    <div id="nscontent">
      <?php if ($this->_tpl_vars['PROFILE_DEFINED'] == 0): ?>
        <?php if ($this->_tpl_vars['DISPLAY_CAT_HEADER'] != ""): ?>
          <h3 class="nsnewsheader<?php echo $this->_tpl_vars['CAT']; ?>
"><?php echo $this->_tpl_vars['DISPLAY_CAT_HEADER']; ?>
</h3><br />
        <?php endif; ?>
        
        <?php if (( ( $this->_tpl_vars['SEARCHTERM_DEFINED'] == 1 || $this->_tpl_vars['CATSEARCH_DEFINED'] == 1 ) && $this->_tpl_vars['NEWSID_DEFINED'] == 0 )): ?>
          <h2 class="nsneutralheader"><?php echo $this->_tpl_vars['LANG_SEARCHRESULT_FOR']; ?>
 &#8222;<?php echo $this->_tpl_vars['SEARCHTERM']; ?>
&#8220;</h2><h4 class="ns"><?php echo $this->_tpl_vars['SUM_NEWS']; ?>
 <?php echo $this->_tpl_vars['LANG_NEWS_FOUND']; ?>
</h4>
        <?php endif; ?>
      
        <?php if (isset($this->_sections["news"])) unset($this->_sections["news"]);
$this->_sections["news"]['name'] = "news";
$this->_sections["news"]['loop'] = is_array($this->_tpl_vars['NEWS_ID']) ? count($this->_tpl_vars['NEWS_ID']) : max(0, (int)$this->_tpl_vars['NEWS_ID']);
$this->_sections["news"]['show'] = true;
$this->_sections["news"]['max'] = $this->_sections["news"]['loop'];
$this->_sections["news"]['step'] = 1;
$this->_sections["news"]['start'] = $this->_sections["news"]['step'] > 0 ? 0 : $this->_sections["news"]['loop']-1;
if ($this->_sections["news"]['show']) {
    $this->_sections["news"]['total'] = $this->_sections["news"]['loop'];
    if ($this->_sections["news"]['total'] == 0)
        $this->_sections["news"]['show'] = false;
} else
    $this->_sections["news"]['total'] = 0;
if ($this->_sections["news"]['show']):

            for ($this->_sections["news"]['index'] = $this->_sections["news"]['start'], $this->_sections["news"]['iteration'] = 1;
                 $this->_sections["news"]['iteration'] <= $this->_sections["news"]['total'];
                 $this->_sections["news"]['index'] += $this->_sections["news"]['step'], $this->_sections["news"]['iteration']++):
$this->_sections["news"]['rownum'] = $this->_sections["news"]['iteration'];
$this->_sections["news"]['index_prev'] = $this->_sections["news"]['index'] - $this->_sections["news"]['step'];
$this->_sections["news"]['index_next'] = $this->_sections["news"]['index'] + $this->_sections["news"]['step'];
$this->_sections["news"]['first']      = ($this->_sections["news"]['iteration'] == 1);
$this->_sections["news"]['last']       = ($this->_sections["news"]['iteration'] == $this->_sections["news"]['total']);
?>
          <?php if ($this->_tpl_vars['NEWS_CAT_HEADER'][$this->_sections['news']['index']] != ""): ?>
            <h3 class="nsnewsheader<?php echo $this->_tpl_vars['NEWS_CSS_COLOR'][$this->_sections['news']['index']]; ?>
"><?php echo $this->_tpl_vars['NEWS_CAT_HEADER'][$this->_sections['news']['index']]; ?>
</h3>
          <?php endif; ?>
        
          <p class="nsnewssubheader<?php echo $this->_tpl_vars['NEWS_CSS_COLOR'][$this->_sections['news']['index']]; ?>
">
            <big class="nspostdate"><?php echo $this->_tpl_vars['NEWS_DATE'][$this->_sections['news']['index']]; ?>
</big>
            <big class="nspostauthor"><?php echo $this->_tpl_vars['LANG_NEWS_POSTED_BY']; ?>
 <a href="<?php echo $this->_tpl_vars['PHP_SELF']; ?>
?profile=<?php echo $this->_tpl_vars['NEWS_AUTHOR_ID'][$this->_sections['news']['index']]; ?>
&#38;<?php echo $this->_tpl_vars['SESSION_NAME']; ?>
=<?php echo $this->_tpl_vars['SESSION_ID']; ?>
"><?php echo $this->_tpl_vars['NEWS_AUTHOR_NAME'][$this->_sections['news']['index']]; ?>
</a></big>
          </p>
          <h4 class="nsheader<?php echo $this->_tpl_vars['NEWS_CSS_COLOR'][$this->_sections['news']['index']]; ?>
"><?php echo $this->_tpl_vars['NEWS_HEADLINE'][$this->_sections['news']['index']]; ?>
 <span class="nshighlight"><?php echo $this->_tpl_vars['NEWS_LANG_NEW'][$this->_sections['news']['index']]; ?>
</span></h4>         
          <p class="nsnewstext<?php echo $this->_tpl_vars['NEWS_CSS_COLOR'][$this->_sections['news']['index']]; ?>
">
            <?php if ($this->_tpl_vars['NEWS_IMAGE'][$this->_sections['news']['index']] != ""): ?>
              <img src="<?php echo $this->_tpl_vars['PICTURE_PATH']; ?>
<?php echo $this->_tpl_vars['NEWS_IMAGE'][$this->_sections['news']['index']]; ?>
" alt="<?php echo $this->_tpl_vars['NEWS_IMAGE_ALTTEXT'][$this->_sections['news']['index']]; ?>
" class="nsnewspic" />
            <?php endif; ?>
            <?php echo $this->_tpl_vars['NEWS_TEXT'][$this->_sections['news']['index']]; ?>

            <?php if ($this->_tpl_vars['NEWS_SHOW_LINKS'][$this->_sections['news']['index']] == 1): ?>
              <br /><strong><?php echo $this->_tpl_vars['LANG_LINKS']; ?>
:</strong><br />
              <?php if (isset($this->_sections["news_links"])) unset($this->_sections["news_links"]);
$this->_sections["news_links"]['name'] = "news_links";
$this->_sections["news_links"]['loop'] = is_array($this->_tpl_vars['NEWS_LINKS_LINK'][$this->_sections['news']['index']]) ? count($this->_tpl_vars['NEWS_LINKS_LINK'][$this->_sections['news']['index']]) : max(0, (int)$this->_tpl_vars['NEWS_LINKS_LINK'][$this->_sections['news']['index']]);
$this->_sections["news_links"]['show'] = true;
$this->_sections["news_links"]['max'] = $this->_sections["news_links"]['loop'];
$this->_sections["news_links"]['step'] = 1;
$this->_sections["news_links"]['start'] = $this->_sections["news_links"]['step'] > 0 ? 0 : $this->_sections["news_links"]['loop']-1;
if ($this->_sections["news_links"]['show']) {
    $this->_sections["news_links"]['total'] = $this->_sections["news_links"]['loop'];
    if ($this->_sections["news_links"]['total'] == 0)
        $this->_sections["news_links"]['show'] = false;
} else
    $this->_sections["news_links"]['total'] = 0;
if ($this->_sections["news_links"]['show']):

            for ($this->_sections["news_links"]['index'] = $this->_sections["news_links"]['start'], $this->_sections["news_links"]['iteration'] = 1;
                 $this->_sections["news_links"]['iteration'] <= $this->_sections["news_links"]['total'];
                 $this->_sections["news_links"]['index'] += $this->_sections["news_links"]['step'], $this->_sections["news_links"]['iteration']++):
$this->_sections["news_links"]['rownum'] = $this->_sections["news_links"]['iteration'];
$this->_sections["news_links"]['index_prev'] = $this->_sections["news_links"]['index'] - $this->_sections["news_links"]['step'];
$this->_sections["news_links"]['index_next'] = $this->_sections["news_links"]['index'] + $this->_sections["news_links"]['step'];
$this->_sections["news_links"]['first']      = ($this->_sections["news_links"]['iteration'] == 1);
$this->_sections["news_links"]['last']       = ($this->_sections["news_links"]['iteration'] == $this->_sections["news_links"]['total']);
?>
                &#8226; <a href="<?php echo $this->_tpl_vars['NEWS_LINKS_LINK'][$this->_sections['news']['index']][$this->_sections['news_links']['index']]; ?>
" target="_blank" class="nsnewslink<?php echo $this->_tpl_vars['NEWS_CSS_COLOR'][$this->_sections['news']['index']]; ?>
"><?php echo $this->_tpl_vars['NEWS_LINKS_TEXT'][$this->_sections['news']['index']][$this->_sections['news_links']['index']]; ?>
</a><br />
              <?php endfor; endif; ?>
            <?php endif; ?>
          </p>
          <?php if (( $this->_tpl_vars['NEWS_SOURCE_AVAILABLE'][$this->_sections['news']['index']] == 1 || $this->_tpl_vars['NEWS_NO_COMMENTS'][$this->_sections['news']['index']] == 0 )): ?>
            <p class="nsnewssubheader<?php echo $this->_tpl_vars['NEWS_CSS_COLOR'][$this->_sections['news']['index']]; ?>
">
              <big class="nssource">
                <?php if ($this->_tpl_vars['NEWS_SOURCE'][$this->_sections['news']['index']] == ""): ?>
                <?php else: ?>
                  <strong><?php echo $this->_tpl_vars['LANG_SOURCE']; ?>
:</strong>
                  <?php if ($this->_tpl_vars['NEWS_SOURCE_LINK'][$this->_sections['news']['index']] == ""): ?>
                    <?php echo $this->_tpl_vars['NEWS_SOURCE'][$this->_sections['news']['index']]; ?>

                  <?php else: ?>
                    <a href="<?php echo $this->_tpl_vars['NEWS_SOURCE_LINK'][$this->_sections['news']['index']]; ?>
" target="_blank"><?php echo $this->_tpl_vars['NEWS_SOURCE'][$this->_sections['news']['index']]; ?>
</a>
                  <?php endif; ?>
                <?php endif; ?>
              </big>
              <big class="nscomments">
                <?php if ($this->_tpl_vars['NEWS_NO_COMMENTS'][$this->_sections['news']['index']] == 0): ?>
                  <a href="<?php echo $this->_tpl_vars['PHP_SELF']; ?>
?id=<?php echo $this->_tpl_vars['NEWS_ID'][$this->_sections['news']['index']]; ?>
<?php echo $this->_tpl_vars['HIGHLIGHT']; ?>
&#38;<?php echo $this->_tpl_vars['SESSION_NAME']; ?>
=<?php echo $this->_tpl_vars['SESSION_ID']; ?>
" title="<?php echo $this->_tpl_vars['NEWS_COMMENTS_BY'][$this->_sections['news']['index']]; ?>
"><?php echo $this->_tpl_vars['NEWS_COMMENTS_COUNT'][$this->_sections['news']['index']]; ?>
 <?php echo $this->_tpl_vars['NEWS_LANG_COMMENTS'][$this->_sections['news']['index']]; ?>
</a>
                <?php endif; ?>
              </big>
            </p>
          <?php endif; ?>
          <br />
          <?php if ($this->_tpl_vars['SINGLE_NEWS'] == 1): ?>
            <div class="nsquicknav">
              <span class="nsalileft"><small class="ns"><?php echo $this->_tpl_vars['PREV_ARTICLE']; ?>
</small></span>
              <span class="nsalicenter">&nbsp;</span>
              <span class="nsaliright"><small class="ns"><?php echo $this->_tpl_vars['NEXT_ARTICLE']; ?>
</small></span>
            </div>
            <?php if ($this->_tpl_vars['NEWS_NO_COMMENTS'][$this->_sections['news']['index']] == 0): ?>
              <h3 class="nsnewsheader<?php echo $this->_tpl_vars['NEWS_CSS_COLOR'][$this->_sections['news']['index']]; ?>
"><?php echo $this->_tpl_vars['LANG_COMMENTS']; ?>
</h3>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['ERROR_COMMENT'] != ""): ?>
              <div><em><?php echo $this->_tpl_vars['ERROR_COMMENT']; ?>
</em></div>
            <?php endif; ?>         
            <?php if ($this->_tpl_vars['NEWS_NO_COMMENTS'][$this->_sections['news']['index']] == 0): ?>
              <?php if (isset($this->_sections["comments"])) unset($this->_sections["comments"]);
$this->_sections["comments"]['name'] = "comments";
$this->_sections["comments"]['loop'] = is_array($this->_tpl_vars['COMMENT_TEXT']) ? count($this->_tpl_vars['COMMENT_TEXT']) : max(0, (int)$this->_tpl_vars['COMMENT_TEXT']);
$this->_sections["comments"]['show'] = true;
$this->_sections["comments"]['max'] = $this->_sections["comments"]['loop'];
$this->_sections["comments"]['step'] = 1;
$this->_sections["comments"]['start'] = $this->_sections["comments"]['step'] > 0 ? 0 : $this->_sections["comments"]['loop']-1;
if ($this->_sections["comments"]['show']) {
    $this->_sections["comments"]['total'] = $this->_sections["comments"]['loop'];
    if ($this->_sections["comments"]['total'] == 0)
        $this->_sections["comments"]['show'] = false;
} else
    $this->_sections["comments"]['total'] = 0;
if ($this->_sections["comments"]['show']):

            for ($this->_sections["comments"]['index'] = $this->_sections["comments"]['start'], $this->_sections["comments"]['iteration'] = 1;
                 $this->_sections["comments"]['iteration'] <= $this->_sections["comments"]['total'];
                 $this->_sections["comments"]['index'] += $this->_sections["comments"]['step'], $this->_sections["comments"]['iteration']++):
$this->_sections["comments"]['rownum'] = $this->_sections["comments"]['iteration'];
$this->_sections["comments"]['index_prev'] = $this->_sections["comments"]['index'] - $this->_sections["comments"]['step'];
$this->_sections["comments"]['index_next'] = $this->_sections["comments"]['index'] + $this->_sections["comments"]['step'];
$this->_sections["comments"]['first']      = ($this->_sections["comments"]['iteration'] == 1);
$this->_sections["comments"]['last']       = ($this->_sections["comments"]['iteration'] == $this->_sections["comments"]['total']);
?>
                <?php if ($this->_tpl_vars['COMMENT_DISPLAY_DAY'][$this->_sections['comments']['index']] == 1): ?>
                  <h4 class="nscommentdate"><?php echo $this->_tpl_vars['COMMENT_DATE'][$this->_sections['comments']['index']]; ?>
</h4>
                <?php endif; ?>
                <p class="nsnewssubheader<?php echo $this->_tpl_vars['NEWS_CSS_COLOR'][$this->_sections['news']['index']]; ?>
">
                  <big class="nspostdate"><?php echo $this->_tpl_vars['COMMENT_AUTHOR_AND_MAIL'][$this->_sections['comments']['index']]; ?>
</big> 
                  <big class="nspostauthor"><?php echo $this->_tpl_vars['COMMENT_TIME'][$this->_sections['comments']['index']]; ?>
</big>
                </p>
               <p class="nsnewstext<?php echo $this->_tpl_vars['NEWS_CSS_COLOR'][$this->_sections['news']['index']]; ?>
" style="border-bottom: 1px solid #000000;"><?php echo $this->_tpl_vars['COMMENT_TEXT'][$this->_sections['comments']['index']]; ?>
</p><br />
              <?php endfor; else: ?>
                <p class="nsnewstext<?php echo $this->_tpl_vars['NEWS_CSS_COLOR'][$this->_sections['news']['index']]; ?>
" style="border-bottom: 1px solid #000000;"><strong><?php echo $this->_tpl_vars['LANG_NO_COMMENTS']; ?>
</strong></p>
              <?php endif; ?>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['SHOW_COMMENT_FORM'] == 1): ?>
              <br />
              <form method="post" action="<?php echo $this->_tpl_vars['PHP_SELF']; ?>
" title="<?php echo $this->_tpl_vars['FORM_WRITE_COMMENT']; ?>
">
                <fieldset class="ns">
                  <legend class="ns"><?php echo $this->_tpl_vars['FORM_WRITE_COMMENT']; ?>
</legend><br />
                  <label for="name" class="ns"><?php echo $this->_tpl_vars['FORM_NAME']; ?>
:</label><br />
                  <input type="text" id="name" name="name" size="50" value="<?php echo $this->_tpl_vars['FORM_SESSION_NAME']; ?>
" accesskey="n" tabindex="8" class="ns" /><br />
                  <label for="email" class="ns"><?php echo $this->_tpl_vars['FORM_EMAIL']; ?>
 <?php echo $this->_tpl_vars['FORM_OR']; ?>
 <?php echo $this->_tpl_vars['FORM_HOMEPAGE']; ?>
:</label><br />
                  <input type="text" id="email" name="email" size="50" value="<?php echo $this->_tpl_vars['FORM_SESSION_EMAIL']; ?>
" accesskey="e" tabindex="9" class="ns" /><br />
                  <label for="comment" class="ns"><?php echo $this->_tpl_vars['FORM_COMMENT']; ?>
:</label><br />
                  <textarea cols="40" rows="3" id="comment" name="comment" accesskey="k" tabindex="10" class="ns"></textarea><br />
                  <input type="hidden" name="commentsend" value="<?php echo $this->_tpl_vars['SINGLE_NEWS_ID']; ?>
" />
                  <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['SINGLE_NEWS_ID']; ?>
" />
                  <input type="hidden" name="startlisting" value="<?php echo $this->_tpl_vars['STARTLISTING']; ?>
" />
                  <input type="hidden" name="<?php echo $this->_tpl_vars['SESSION_NAME']; ?>
" value="<?php echo $this->_tpl_vars['SESSION_ID']; ?>
" />
                  <br /><input type="submit" name="submitcomment" id="submitcomment" value="<?php echo $this->_tpl_vars['FORM_BUTTON_SEND']; ?>
" accesskey="s" tabindex="11" class="ns" />
                </fieldset>
              </form>
            <?php endif; ?>
          <?php endif; ?>
        <?php endfor; endif; ?>
      <?php else: ?> 
        <?php if (( $this->_tpl_vars['ERROR_MESSAGE'] == "" )): ?>
          <h2 class="nsneutralheader"><?php echo $this->_tpl_vars['LANG_PROFILE']; ?>
</h2>
          <h4 class="ns"><?php echo $this->_tpl_vars['AUTHOR_NAME']; ?>
</h2>
          <div class="nsprofiletext">
            <?php if (( $this->_tpl_vars['AUTHOR_PICTURE'] != "" )): ?>
              <img src="<?php echo $this->_tpl_vars['PICTURE_PATH']; ?>
<?php echo $this->_tpl_vars['AUTHOR_PICTURE']; ?>
" alt="Icon" align="left" class="nsnewspic" />
            <?php endif; ?>
          <?php echo $this->_tpl_vars['AUTHOR_ABSTRACT']; ?>

          </div><br />
          <h4 class="ns"><?php echo $this->_tpl_vars['LANG_LATEST_NEWS_BY']; ?>
 <?php echo $this->_tpl_vars['AUTHOR_NAME']; ?>
</h4>
          <div class="nsprofiletext">
            <?php if (isset($this->_sections["news_author"])) unset($this->_sections["news_author"]);
$this->_sections["news_author"]['name'] = "news_author";
$this->_sections["news_author"]['loop'] = is_array($this->_tpl_vars['AUTHOR_LN_NEWSID']) ? count($this->_tpl_vars['AUTHOR_LN_NEWSID']) : max(0, (int)$this->_tpl_vars['AUTHOR_LN_NEWSID']);
$this->_sections["news_author"]['show'] = true;
$this->_sections["news_author"]['max'] = $this->_sections["news_author"]['loop'];
$this->_sections["news_author"]['step'] = 1;
$this->_sections["news_author"]['start'] = $this->_sections["news_author"]['step'] > 0 ? 0 : $this->_sections["news_author"]['loop']-1;
if ($this->_sections["news_author"]['show']) {
    $this->_sections["news_author"]['total'] = $this->_sections["news_author"]['loop'];
    if ($this->_sections["news_author"]['total'] == 0)
        $this->_sections["news_author"]['show'] = false;
} else
    $this->_sections["news_author"]['total'] = 0;
if ($this->_sections["news_author"]['show']):

            for ($this->_sections["news_author"]['index'] = $this->_sections["news_author"]['start'], $this->_sections["news_author"]['iteration'] = 1;
                 $this->_sections["news_author"]['iteration'] <= $this->_sections["news_author"]['total'];
                 $this->_sections["news_author"]['index'] += $this->_sections["news_author"]['step'], $this->_sections["news_author"]['iteration']++):
$this->_sections["news_author"]['rownum'] = $this->_sections["news_author"]['iteration'];
$this->_sections["news_author"]['index_prev'] = $this->_sections["news_author"]['index'] - $this->_sections["news_author"]['step'];
$this->_sections["news_author"]['index_next'] = $this->_sections["news_author"]['index'] + $this->_sections["news_author"]['step'];
$this->_sections["news_author"]['first']      = ($this->_sections["news_author"]['iteration'] == 1);
$this->_sections["news_author"]['last']       = ($this->_sections["news_author"]['iteration'] == $this->_sections["news_author"]['total']);
?>
              <span class="nscolorbox<?php echo $this->_tpl_vars['AUTHOR_LN_CATID'][$this->_sections['news_author']['index']]; ?>
" title="<?php echo $this->_tpl_vars['AUTHOR_LN_CATNAME'][$this->_sections['news_author']['index']]; ?>
">&nbsp;&#187;&nbsp;</span> <a href="<?php echo $this->_tpl_vars['PHP_SELF']; ?>
?id=<?php echo $this->_tpl_vars['AUTHOR_LN_NEWSID'][$this->_sections['news_author']['index']]; ?>
&#38;<?php echo $this->_tpl_vars['SESSION_NAME']; ?>
=<?php echo $this->_tpl_vars['SESSION_ID']; ?>
"><?php echo $this->_tpl_vars['AUTHOR_LN_HEADLINE'][$this->_sections['news_author']['index']]; ?>
</a> <small class="ns">(<?php echo $this->_tpl_vars['AUTHOR_LN_DATE'][$this->_sections['news_author']['index']]; ?>
)</small><br />
            <?php endfor; endif; ?>
          </div>
        <?php else: ?>
          <strong><em><?php echo $this->_tpl_vars['ERROR_MESSAGE']; ?>
</em></strong>
        <?php endif; ?>
      <?php endif; ?>  
      <br style="clear:both" />
      <span class="nsalileft"><?php echo $this->_tpl_vars['PREV_PAGE']; ?>
</span>
      <span class="nsalicenter"><?php echo $this->_tpl_vars['LIST_PAGES']; ?>
</span>
      <span class="nsaliright"><?php echo $this->_tpl_vars['NEXT_PAGE']; ?>
</span>
      <br style="clear:both" />
    </div>
    <h6 class="ns">Newsscript and all other Content &#169; 2002 Flaimo.com</h6>
  </body>
</html>
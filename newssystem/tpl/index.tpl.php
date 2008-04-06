<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{$LANG_ISO}}" lang="{{$LANG_ISO}}">
  <head>
    <title>{{$PAGE_TITLE}}</title>
      <meta http-equiv="MSThemeCompatible" content="Yes" />
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <meta http-equiv="Content-Style-Type" content="text/css" />
      <meta http-equiv="expires" content="{{$META_EXPIRES}}" />
      <meta http-equiv="pragma" content="no-cache" />
      <style type="text/css">
      <!--
      @import url("newssystem.css");
      -->
      </style>
  
  </head>
  <body>
    <div id="nsmenu">
      {{if $COUNT_CATEGORIES > 0}}
        <h2 class="nsmenu"><a href="{{$PHP_SELF}}" style="color:white;text-decoration:none;">{{$LANG_MENU_TITLE}}</a></h2>
        <div class="nsmenucontent">
        {{section name=menu loop=$MENU_CAT_NR}}
          <a href="{{$PHP_SELF}}?cat={{$MENU_CAT_NR[menu]}}&#38;{{$SESSION_NAME}}={{$SESSION_ID}}">{{$MENU_CAT_NAME[menu]}} <small class="nr">({{$MENU_CAT_COUNT[menu]}})</small></a><br />
        {{/section}}
      {{/if}}
      <form method="post" action="{{$PHP_SELF}}" title="{{$FORM_SEARCH}}" style="text-align:center"> 
        <fieldset class="ns">
          <legend>{{$FORM_SEARCH}}</legend><br />
          <em><label for="search" class="ns">{{$FORM_KEYWORD}}:</label></em><br />
          <input type="text" name="search" id="search" size="9" value="{{$FORM_SEARCHSTRING}}" tabindex="1" class="ns" /><br />

          {{if $FORM_ADV_SEARCH == 1}}
            <em><label for="cat_search" class="ns">{{$FORM_CATEGORY}}:</label></em><br />
            <select name="cat_search" id="cat_search" size="1" tabindex="2" class="ns">
              <option value="all">{{$FORM_ALL}}</option>
              {{section name=form_cat loop=$MENU_CAT_NR}}
                <option class="ns" value="{{$MENU_CAT_NR[form_cat]}}">{{$MENU_CAT_NAME[form_cat]}}</option>
              {{/section}}
            </select><br />
            <em><label for="author" class="ns">{{$FORM_AUTHOR}}:</label></em><br />
            <select name="author" id="author" size="1" tabindex="3" class="ns">
              <option value="all" selected="selected">{{$FORM_ALL}}</option>        
              {{section name=form_author loop=$MENU_AUTHOR_NR}}
                <option class="ns" value="{{$MENU_AUTHOR_NR[form_author]}}">{{$MENU_AUTHOR_NAME[form_author]}} ({{$MENU_AUTHOR_COUNT[form_author]}})</option>
              {{/section}}
            </select><br /><br />
          {{else}}
            <br />
            <input type="hidden" name="cat_search" value="all" />
            <input type="hidden" name="author" value="all" />
          {{/if}}
          <input type="hidden" name="startlisting" value="{{$STARTLISTING}}" />
          <input type="hidden" name="{{$SESSION_NAME}}" value="{{$SESSION_ID}}" />
          <input type="submit" value="{{$FORM_SEARCH_BUTTON}}" class="ns" /><br /><br />
          {{if $FORM_ADV_SEARCH == 0}}
            <small class="ns"><a href="{{$PHP_SELF}}?advsearch=1&#38;{{$SESSION_NAME}}={{$SESSION_ID}}">{{$ADV_SEARCH_LINK}}</a></small>
          {{/if}}
        </fieldset>
      </form>
      <form action="{{$PHP_SELF}}" method="post" title="{{$FORM_LANGUAGE}}" style="text-align:center">
        <fieldset class="ns">
          <legend>{{$FORM_LANGUAGE}}</legend><br />
          <label for="lang" class="ns">{{$FORM_OSM}}</label>
          <br />{{$FORM_DROPDOWN_LANG}}<br /><br />
          <label for="lang_content" class="ns">{{$FORM_LANGUAGE_CONTENT}}</label>
          <br />{{$FORM_DROPDOWN_LANG_CONTENT}}<br /><br />
          <label for="timeset" class="ns">{{$FORM_TIME}}</label>
          <br />{{$FORM_DROPDOWN_TIME}}<br />
          <input type="hidden" name="startlisting" value="{{$STARTLISTING}}" />
          <input type="hidden" name="{{$SESSION_NAME}}" value="{{$SESSION_ID}}" />
          <br /><input type="submit" value="{{$FORM_CHANGE}}" class="ns" />
        </fieldset>
      </form>
      <small class="ns">
       {{$SUM_ALL_NEWS}} {{$LANG_NEWS}}<br />
       {{$SUM_ALL_COMMENTS}} {{$LANG_COMMENTS}}<br />
       {{$USER_ONLINE}} {{$LANG_USER_ONLINE}}
      </small>     
      <br /><br />
      <small class="ns">
        <a href="{{$TIMEZONE_LINK}}" target="_blank">{{$LANG_TIMEZONE_IS}} {{$TIMEZONE}}</a><br />
        <strong>{{$LANG_TIMEZONE}}:<br />{{$TIMEZONE_DATE}} &#8211; {{$TIMEZONE_TIME}}</strong>
      </small><br />
      <br /><div style="text-align:center;"><a href="rss.php?lang={{$RSS_LINK}}"><img src="xml.gif" alt="RSS Content Syndication" width="36" height="14" border="0" /></a></div>
     </div>
    </div>
    <div id="nscontent">
      {{if $PROFILE_DEFINED == 0}}
        {{if $DISPLAY_CAT_HEADER neq ""}}
          <h3 class="nsnewsheader{{$CAT}}">{{$DISPLAY_CAT_HEADER}}</h3><br />
        {{/if}}
        
        {{if (($SEARCHTERM_DEFINED == 1 or $CATSEARCH_DEFINED == 1) and $NEWSID_DEFINED == 0)}}
          <h2 class="nsneutralheader">{{$LANG_SEARCHRESULT_FOR}} &#8222;{{$SEARCHTERM}}&#8220;</h2><h4 class="ns">{{$SUM_NEWS}} {{$LANG_NEWS_FOUND}}</h4>
        {{/if}}
      
        {{section name=news loop=$NEWS_ID}}
          {{if $NEWS_CAT_HEADER[news] neq ""}}
            <h3 class="nsnewsheader{{$NEWS_CSS_COLOR[news]}}">{{$NEWS_CAT_HEADER[news]}}</h3>
          {{/if}}
        
          <p class="nsnewssubheader{{$NEWS_CSS_COLOR[news]}}">
            <big class="nspostdate">{{$NEWS_DATE[news]}}</big>
            <big class="nspostauthor">{{$LANG_NEWS_POSTED_BY}} <a href="{{$PHP_SELF}}?profile={{$NEWS_AUTHOR_ID[news]}}&#38;{{$SESSION_NAME}}={{$SESSION_ID}}">{{$NEWS_AUTHOR_NAME[news]}}</a></big>
          </p>
          <h4 class="nsheader{{$NEWS_CSS_COLOR[news]}}">{{$NEWS_HEADLINE[news]}} <span class="nshighlight">{{$NEWS_LANG_NEW[news]}}</span></h4>         
          <p class="nsnewstext{{$NEWS_CSS_COLOR[news]}}">
            {{if $NEWS_IMAGE[news] neq ""}}
              <img src="{{$PICTURE_PATH}}{{$NEWS_IMAGE[news]}}" alt="{{$NEWS_IMAGE_ALTTEXT[news]}}" class="nsnewspic" />
            {{/if}}
            {{$NEWS_TEXT[news]}}
            {{if $NEWS_SHOW_LINKS[news] == 1}}
              <br /><strong>{{$LANG_LINKS}}:</strong><br />
              {{section name=news_links loop=$NEWS_LINKS_LINK[news]}}
                &#8226; <a href="{{$NEWS_LINKS_LINK[news][news_links]}}" target="_blank" class="nsnewslink{{$NEWS_CSS_COLOR[news]}}">{{$NEWS_LINKS_TEXT[news][news_links]}}</a><br />
              {{/section}}
            {{/if}}
          </p>
          {{if ($NEWS_SOURCE_AVAILABLE[news] == 1 or $NEWS_NO_COMMENTS[news] == 0) }}
            <p class="nsnewssubheader{{$NEWS_CSS_COLOR[news]}}">
              <big class="nssource">
                {{if $NEWS_SOURCE[news] eq ""}}
                {{else}}
                  <strong>{{$LANG_SOURCE}}:</strong>
                  {{if $NEWS_SOURCE_LINK[news] eq ""}}
                    {{$NEWS_SOURCE[news]}}
                  {{else}}
                    <a href="{{$NEWS_SOURCE_LINK[news]}}" target="_blank">{{$NEWS_SOURCE[news]}}</a>
                  {{/if}}
                {{/if}}
              </big>
              <big class="nscomments">
                {{if $NEWS_NO_COMMENTS[news] == 0}}
                  <a href="{{$PHP_SELF}}?id={{$NEWS_ID[news]}}{{$HIGHLIGHT}}&#38;{{$SESSION_NAME}}={{$SESSION_ID}}" title="{{$NEWS_COMMENTS_BY[news]}}">{{$NEWS_COMMENTS_COUNT[news]}} {{$NEWS_LANG_COMMENTS[news]}}</a>
                {{/if}}
              </big>
            </p>
          {{/if}}
          <br />
          {{if $SINGLE_NEWS == 1}}
            <div class="nsquicknav">
              <span class="nsalileft"><small class="ns">{{$PREV_ARTICLE}}</small></span>
              <span class="nsalicenter">&nbsp;</span>
              <span class="nsaliright"><small class="ns">{{$NEXT_ARTICLE}}</small></span>
            </div>
            {{if $NEWS_NO_COMMENTS[news] == 0}}
              <h3 class="nsnewsheader{{$NEWS_CSS_COLOR[news]}}">{{$LANG_COMMENTS}}</h3>
            {{/if}}
            {{if $ERROR_COMMENT neq ""}}
              <div><em>{{$ERROR_COMMENT}}</em></div>
            {{/if}}         
            {{if $NEWS_NO_COMMENTS[news] == 0}}
              {{section name=comments loop=$COMMENT_TEXT}}
                {{if $COMMENT_DISPLAY_DAY[comments] == 1}}
                  <h4 class="nscommentdate">{{$COMMENT_DATE[comments]}}</h4>
                {{/if}}
                <p class="nsnewssubheader{{$NEWS_CSS_COLOR[news]}}">
                  <big class="nspostdate">{{$COMMENT_AUTHOR_AND_MAIL[comments]}}</big> 
                  <big class="nspostauthor">{{$COMMENT_TIME[comments]}}</big>
                </p>
               <p class="nsnewstext{{$NEWS_CSS_COLOR[news]}}" style="border-bottom: 1px solid #000000;">{{$COMMENT_TEXT[comments]}}</p><br />
              {{sectionelse}}
                <p class="nsnewstext{{$NEWS_CSS_COLOR[news]}}" style="border-bottom: 1px solid #000000;"><strong>{{$LANG_NO_COMMENTS}}</strong></p>
              {{/section}}
            {{/if}}
            {{if $SHOW_COMMENT_FORM == 1}}
              <br />
              <form method="post" action="{{$PHP_SELF}}" title="{{$FORM_WRITE_COMMENT}}">
                <fieldset class="ns">
                  <legend class="ns">{{$FORM_WRITE_COMMENT}}</legend><br />
                  <label for="name" class="ns">{{$FORM_NAME}}:</label><br />
                  <input type="text" id="name" name="name" size="50" value="{{$FORM_SESSION_NAME}}" accesskey="n" tabindex="8" class="ns" /><br />
                  <label for="email" class="ns">{{$FORM_EMAIL}} {{$FORM_OR}} {{$FORM_HOMEPAGE}}:</label><br />
                  <input type="text" id="email" name="email" size="50" value="{{$FORM_SESSION_EMAIL}}" accesskey="e" tabindex="9" class="ns" /><br />
                  <label for="comment" class="ns">{{$FORM_COMMENT}}:</label><br />
                  <textarea cols="40" rows="3" id="comment" name="comment" accesskey="k" tabindex="10" class="ns"></textarea><br />
                  <input type="hidden" name="commentsend" value="{{$SINGLE_NEWS_ID}}" />
                  <input type="hidden" name="id" value="{{$SINGLE_NEWS_ID}}" />
                  <input type="hidden" name="startlisting" value="{{$STARTLISTING}}" />
                  <input type="hidden" name="{{$SESSION_NAME}}" value="{{$SESSION_ID}}" />
                  <br /><input type="submit" name="submitcomment" id="submitcomment" value="{{$FORM_BUTTON_SEND}}" accesskey="s" tabindex="11" class="ns" />
                </fieldset>
              </form>
            {{/if}}
          {{/if}}
        {{/section}}
      {{else}} {{* PROFILE_DEFINED *}}
        {{if ($ERROR_MESSAGE eq "")}}
          <h2 class="nsneutralheader">{{$LANG_PROFILE}}</h2>
          <h4 class="ns">{{$AUTHOR_NAME}}</h2>
          <div class="nsprofiletext">
            {{if ($AUTHOR_PICTURE neq "")}}
              <img src="{{$PICTURE_PATH}}{{$AUTHOR_PICTURE}}" alt="Icon" align="left" class="nsnewspic" />
            {{/if}}
          {{$AUTHOR_ABSTRACT}}
          </div><br />
          <h4 class="ns">{{$LANG_LATEST_NEWS_BY}} {{$AUTHOR_NAME}}</h4>
          <div class="nsprofiletext">
            {{section name=news_author loop=$AUTHOR_LN_NEWSID}}
              <span class="nscolorbox{{$AUTHOR_LN_CATID[news_author]}}" title="{{$AUTHOR_LN_CATNAME[news_author]}}">&nbsp;&#187;&nbsp;</span> <a href="{{$PHP_SELF}}?id={{$AUTHOR_LN_NEWSID[news_author]}}&#38;{{$SESSION_NAME}}={{$SESSION_ID}}">{{$AUTHOR_LN_HEADLINE[news_author]}}</a> <small class="ns">({{$AUTHOR_LN_DATE[news_author]}})</small><br />
            {{/section}}
          </div>
        {{else}}
          <strong><em>{{$ERROR_MESSAGE}}</em></strong>
        {{/if}}
      {{/if}}  
      <br style="clear:both" />
      <span class="nsalileft">{{$PREV_PAGE}}</span>
      <span class="nsalicenter">{{$LIST_PAGES}}</span>
      <span class="nsaliright">{{$NEXT_PAGE}}</span>
      <br style="clear:both" />
    </div>
    <h6 class="ns">Newsscript and all other Content &#169; 2002 Flaimo.com</h6>
  </body>
</html>

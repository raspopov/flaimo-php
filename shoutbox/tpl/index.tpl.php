<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
    <title>ShoutBox</title>
      <meta http-equiv="MSThemeCompatible" content="Yes" />
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <meta http-equiv="Content-Style-Type" content="text/css" />
      <meta http-equiv="expires" content="{{$META_EXPIRES}}" />
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
      <strong>{{$FEEDBACK_MESSAGE}}</strong>
      {{section name=shoutbox loop=$SB_TEXT}}
        <h4>{{$SB_DATE[shoutbox]}}</h4>
        <p>
        <big>
          {{if $SB_EMAIL[shoutbox] neq ""}}
            <a href="{{$SB_EMAIL[shoutbox]}}">{{$SB_NAME[shoutbox]}}</a>
          {{else}}
            {{$SB_NAME[shoutbox]}}
          {{/if}}
        </big><br />
        <small>{{$SB_TIME[shoutbox]}}</small>
        <br />
        {{$SB_TEXT[shoutbox]}}
        </p>
      {{sectionelse}}
        <p>Keine Shouts vorhanden</p>
      {{/section}}
      {{if $SHOW_SB_FORM == 1}}
        <form action="{{$PHP_SELF}}" id="shoutbox_form" name="shoutbox_form" method="post" title="ShoutBox">
          <label for="sb_name" class="sblabel">Name:</label><br />
          <input type="text" name="sb_name" id="sb_name" class="sbinput" value = "{{$SESS_NAME}}" /><br />
          <label for="sb_mail" class="sblabel">E-Mail:</label><br />
          <input type="text" name="sb_mail" id="sb_mail" class="sbinput" value="{{$SESS_EMAIL}}" /><br />
          <label for="sb_message" class="sblabel">Message:</label><br />
          <textarea name="sb_message" id="sb_message" class="sbtextarea" cols="20" rows="3" ></textarea><br />
          <input type="hidden" name="token" value="{{$TOKEN}}" />
          <input type="submit" name="sb_submit" id="sb_submit" value="Shout Out" class="sbsumbit" />
        </form>
      {{/if}}
    </div>
  </body>
</html>

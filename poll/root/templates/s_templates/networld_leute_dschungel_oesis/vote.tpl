<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
	<head>
		<title>Umfrage - {$poll_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			<!--
			@import url("generalstyles.css");
			@import url("{$path}dschungel.css");
			-->
		</style>
		<script type="text/javascript" language="javascript">
			<!--
			 function submitVote(id) {ldelim}
			 	current_form = document.getElementById("participants");
			 	hiddenfield = document.getElementById("hiddenselection");
			 	hiddenfield.value = id;
			 	current_form.submit();
			 	return true;
			 {rdelim}
			//-->
		</script>
	</head>
	<body>
		<h1><span>Stars im Dschungel</span></h1>
		<h3>{$poll_title}</h3>
		<p><a href="#" id="post-suggestion" onClick="void window.open('/nw1/gen/slideshows/slider.php?show=greetings/leute/dschungel_tv/', 'greedings', 'width=680,height=560,menubar=0,resizable=0,toolbar=0,scrollbars=0,location=0, copyhistory=0,status=0,directories=0').focus();return false;">Vorschlag posten</a>{$poll_description}</p>

		{if $count_errors > 0}
			<ul class="errors">
			{section name=error loop=$errors}
				<li>{$errors[error]}</li>
			{/section}
			</ul>
		{/if}

		<form action="{$php_self}" method="post" id="participants">
			<input type="hidden" name="selection" id="hiddenselection" value="" />
			{section name=id loop=$option_ids}
				{if $display_active_only eq FALSE || ($option_active[id] eq TRUE && $display_active_only eq TRUE)}
					<div class="participant">
						{if $option_active[id] eq TRUE}
							<img src="{$option_image[id]}" alt="{$option_titles[id]}" class="portrait" />
							<h2>{$option_titles[id]}</h2>
							<input type="submit" name="vote" value="{$button_label}" onclick="submitVote({$option_ids[id]})" />
						{else}
							<img src="{$option_inactive_image[id]}" alt="{$option_titles[id]}" class="portrait" />
							<h2 class="inactive">{$option_titles[id]}</h2>
						{/if}
					</div>
				{/if}
			{sectionelse}
				<p>Es stehen keine Antwortmöglichkeiten für diese Umfrage zur Verfügung</p>
			{/section}
			{$form_token}
			<input type="hidden" name="poll" value="{$poll_id}" />
			<input type="hidden" name="vote" value="1" />
		</form>
		<script type="text/javascript">
		<!--
			var ts=new Date(); ts=ts.getTime();
			document.write('<img src="/cgi-bin/ivw/CP/{$cp_type}{$L1_cat}/umfrage{$L2_cat}/{$cp}?' + ts + '" width="1" height="1" alt="Zählpixel" />');
		//-->
		</script>
		<noscript>
		<img src="/cgi-bin/ivw/CP/{$cp_type}{$L1_cat}/umfrage{$L2_cat}/{$cp}" width="1" height="1" alt="Zählpixel" />
		</noscript>
	</body>
</html>
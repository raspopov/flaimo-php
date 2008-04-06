<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
	<head>
		<title>Umfrage - {$poll_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			<!--
			@import url("generalstyles.css");
			@import url("{$path}expedition.css");
			-->
		</style>
		<script type="text/javascript" language="javascript">
			<!--
			 function submitVote(id) {ldelim}
			 	current_form = document.getElementById("polloptions");
			 	hiddenfield = document.getElementById("hiddenselection");
			 	hiddenfield.value = id;
			 	current_form.submit();
			 	return true;
			 {rdelim}
			//-->
		</script>
	</head>
	<body>
		<h1 title="{$poll_title}"><span>{$poll_title}</span></h1>
		<p id="poll-description">{$poll_description}</p>
		{if $count_errors > 0}
			<ul class="errors">
			{section name=error loop=$errors}
				<li>{$errors[error]}</li>
			{/section}
			</ul>
		{/if}
		<form action="{$php_self}" method="post" id="polloptions">
				<input type="hidden" name="selection" id="hiddenselection" value="" />
				{section name=id loop=$option_ids}
					{if $display_active_only eq FALSE || ($option_active[id] eq TRUE && $display_active_only eq TRUE)}
						{if $option_active[id] eq TRUE}
							<div class="active-participant">
							<img src="{$path}{$option_ids[id]}.jpg" class="portait" alt="{$option_titles[id]} - Portrait" />
							<h2>{$option_titles[id]}</h2>
							<input type="image" src="{$path}b_d.gif" class="votebutton" name="selection" id="selection-{$option_ids[id]}" value="{$option_ids[id]}" alt="{$button_label}" onclick="submitVote({$option_ids[id]})" />
						{else}
							<div class="inactive-participant">
							<img src="{$path}{$option_ids[id]}_out.jpg" class="portait" alt="{$option_titles[id]} - Portrait" />
							<h2>{$option_titles[id]}</h2>
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
			document.write('<img src="http://www.news.at/cgi-bin/ivw/CP/c/nn/umfrage?' + ts + '" width="1" height="1" alt="Zählpixel" />');
		//-->
		</script>
		<noscript>
		<img src="http://www.news.at/cgi-bin/ivw/CP/c/nn/umfrage" width="1" height="1" alt="Zählpixel" />
		</noscript>
	</body>
</html>
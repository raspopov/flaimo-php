<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
	<head>
		<title>Umfrage - {$poll_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			<!--
			@import url("generalstyles.css");
			@import url("{$path}poll_networld_default.css");
			-->
		</style>
	</head>
	<body>
		<h4><span>News Networld</span> Umfrage</h4>
		<p>Stimmen Sie mit. Und schauen Sie, wie Österreich darüber denkt! </p>
		<h1>{$poll_title}</h1>
		<p>{$poll_description}</p>
		{if $count_errors > 0}
			<ul class="errors">
			{section name=error loop=$errors}
				<li>{$errors[error]}</li>
			{/section}
			</ul>
		{/if}

		<form action="{$php_self}" method="post">
				{section name=id loop=$option_ids}
					{if $display_active_only eq FALSE || ($option_active[id] eq TRUE && $display_active_only eq TRUE)}
						<h2>
						{if $option_active[id] eq TRUE}
							{if $multiple_choise eq TRUE}
								<input type="checkbox" name="selection[{$option_ids[id]}]" id="selection-{$option_ids[id]}" value="{$option_ids[id]}" />
							{else}
								<input type="radio" name="selection" id="selection-{$option_ids[id]}" value="{$option_ids[id]}" />
							{/if}
							<label for="selection-{$option_ids[id]}">{$option_titles[id]}</label></h2>
							{if $option_descriptions[id] != ""}
								<p class="description">{$option_descriptions[id]}&nbsp;</p>
							{/if}
						{else}
							<span class="option-placeholder"></span>
							<label for="selection-{$option_ids[id]}" class="inactive">{$option_titles[id]}</label>
							</h2>
							{if $option_descriptions[id] != ""}
								<p class="inactive-description">{$option_descriptions[id]}&nbsp;</p>
							{/if}
						{/if}
					{/if}
				{sectionelse}
					<p>Es stehen keine Antwortmöglichkeiten für diese Umfrage zur Verfügung</p>
				{/section}
			{$form_token}
			<input type="hidden" name="poll" value="{$poll_id}" />
			<input type="submit" name="vote" id="vote" value="{$button_label}" />
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
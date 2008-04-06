<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
	<head>
		<title>Umfrage - {$poll_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<h1>{$poll_title}</h1>
		<p>{$poll_description}</p>
		<p><small>Summe an Stimmen: {$sum_votes}</small></p>
		{if $show_next_revote eq TRUE}
			<p>Nächste Abstimmung in {$next_revote_min} Minuten</p>
		{/if}

		{section name=id loop=$option_ids}
			{if $display_active_only eq FALSE || ($option_active[id] eq TRUE && $display_active_only eq TRUE)}
				<h2>{$option_titles[id]}</h2>
				<p>{$option_descriptions[id]}</p>
				<p><small> Stimmen: {$option_votes[id]} ({$option_percent_exact[id]}%)</small></p>
				{if $option_active[id] eq TRUE}
					<div style="height: 10px; width: {$option_percent[id]*4}px; background-color: red;"></div>
				{else}
					<div style="height: 10px; width: {$option_percent[id]*4}px; background-color: blue;"></div>
				{/if}
				<hr />
			{/if}
		{sectionelse}
			<p>Es stehen keine Antwortmöglichkeiten für diese Umfrage zur Verfügung</p>
		{/section}

		{if $show_revote_link eq TRUE}
			<p><a href="{$php_self}?poll={$poll_id}">Abstimmen</a></p>
		{/if}
		<script language="JavaScript">
		<!--
			var ts=new Date(); ts=ts.getTime();
			document.write('<img src="/cgi-bin/ivw/CP/c/nn/umfrage?' + ts + '" width="1" height="1"><br>');
		//-->
		</script>
		<noscript>
		<img src="/cgi-bin/ivw/CP/c/nn/umfrage" width="1" height="1"><br>
		</noscript>
	</body>
</html>
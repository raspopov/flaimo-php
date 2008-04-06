<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
	<head>
		<title>Umfrage - {$poll_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			<!--
			@import url("generalstyles.css");
			@import url("{$path}poll_olympia2004_metals.css");

			body {ldelim}
				background: #fff url({$path}background.jpg) left top no-repeat;
			{rdelim}
			-->
		</style>
	</head>
	<body>
		<h1>{$poll_title}</h1>
		<p>{$poll_description}</p>

		<table id="result">
		{section name=id loop=$option_ids}
			{if $display_active_only eq FALSE || ($option_active[id] eq TRUE && $display_active_only eq TRUE)}
				<tr>
					<th><h2>{$option_titles[id]}</h2></th>
					<td>
						{if $option_active[id] eq TRUE}
							<span class="active-block" style="width: {$option_percent[id]*4+1}px;"></span>
						{else}
							<span class="block" style="width: {$option_percent[id]*4+1}px;"></span>
						{/if}
							{if $show_percent eq TRUE}
								<p class="votes">{$option_percent_exact[id]} %
								{if $show_absolute eq TRUE}
									<small>({$option_votes[id]} Stimmen)</small>
								{/if}
								</p>
							{elseif $show_absolute eq TRUE}
								<p class="votes">{$option_votes[id]} Stimmen</p>
							{/if}
					</td>
				</tr>
			{/if}
		{sectionelse}
			<p>Es stehen keine Antwortmöglichkeiten für diese Umfrage zur Verfügung</p>
		{/section}
		</table>

		{if $show_revote_link eq TRUE}
			<p id="voteagain"><a href="{$php_self}?poll={$poll_id}">» Nochmal Abstimmen »</a></p>
		{/if}
		{if $show_next_revote eq TRUE}
			<p id="nextvote">Nächste Abstimmung in {$next_revote_min} Minuten</p>
		{/if}

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
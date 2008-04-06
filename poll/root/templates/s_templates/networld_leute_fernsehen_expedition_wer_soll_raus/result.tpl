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
	</head>
	<body>
		<h1><span>{$poll_title}</span></h1>
		<p id="poll-description">{$poll_description}</p>
		<form action="{$php_self}" method="post" id="polloptions">
				{section name=id loop=$option_ids}
					{if $display_active_only eq FALSE || ($option_active[id] eq TRUE && $display_active_only eq TRUE)}
						{if $option_active[id] eq TRUE}
							<div class="active-participant">
								<img src="{$path}{$option_ids[id]}.jpg" class="portait" alt="{$option_titles[id]} - Portrait" />
								<p class="place">Platz {$option_place[id]}</p>
								<h2>{$option_titles[id]}</h2>
								<span class="active-block" style="width: {$option_percent[id]+1}px;"></span>
								<p class="percent">{$option_percent_exact[id]} %</p>
						{else}
							<div class="inactive-participant">
								<img src="{$path}{$option_ids[id]}_out.jpg" class="portait" alt="{$option_titles[id]} - Portrait" />
								<p class="place">Platz {$option_place[id]}</p>
								<h2>{$option_titles[id]}</h2>
								<span class="block" style="width: {$option_percent[id]+1}px;"></span>
								<p class="percent">{$option_percent_exact[id]} %</p>
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
		{if $show_revote_link eq TRUE}
			<p id="voteagain"><a href="{$php_self}?poll={$poll_id}"><img src="{$path}return.gif" width="250" height="41" alt="Nocheinmal Abstimmen" /></a></p>
		{/if}
		{if $show_next_revote eq TRUE}
			<p id="nextvote">Nächste Abstimmung in <span>{$next_revote_min}</span> Minuten</p>
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
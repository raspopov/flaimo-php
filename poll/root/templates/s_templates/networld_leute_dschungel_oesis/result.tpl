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
	</head>
	<body>
		<h1><span>Stars im Dschungel</span></h1>
		<h3>{$poll_title}</h3>
		<p><a href="#" id="post-suggestion" onClick="void window.open('/nw1/gen/slideshows/slider.php?show=greetings/leute/dschungel_tv/', 'greedings', 'width=680,height=560,menubar=0,resizable=0,toolbar=0,scrollbars=0,location=0, copyhistory=0,status=0,directories=0').focus();return false;">Vorschlag posten</a>{$poll_description}</p>
		<div id="participants">
		{section name=id loop=$option_ids}
			{if $display_active_only eq FALSE || ($option_active[id] eq TRUE && $display_active_only eq TRUE)}
				<div class="participant" title="{$option_description[id]}">
					{if $option_active[id] eq TRUE}
						<img src="{$option_image[id]}" alt="{$option_titles[id]}" class="portrait" />
						<h2>{$option_titles[id]}
						{if $show_percent eq TRUE}
							<small>{$option_percent_exact[id]} %
							{if $show_absolute eq TRUE}
								 ({$option_votes[id]} Stimmen)
							{/if}
							</small>
						{elseif $show_absolute eq TRUE}
							<small>{$option_votes[id]} Stimmen</small>
						{/if}
						</h2>
						<img src="{$path}pix.gif" alt="Balken" height="24" class="block" width="{$option_percent[id]*2+1}" />
					{else}
						<img src="{$option_inactive_image[id]}" alt="{$option_titles[id]}" class="portrait" />
						<h2 class="inactive">{$option_titles[id]}
						{if $show_percent eq TRUE}
							<small>{$option_percent_exact[id]} %
							{if $show_absolute eq TRUE}
								 ({$option_votes[id]} Stimmen)
							{/if}
							</small>
						{elseif $show_absolute eq TRUE}
							<small>{$option_votes[id]} Stimmen</small>
						{/if}
						</h2>
						<img src="{$path}pix_inactive.gif" alt="Balken" height="24" class="block" width="{$option_percent[id]*2+1}" />
					{/if}
				</div>
			{/if}
		{sectionelse}
			<p>Es stehen keine Antwortmöglichkeiten für diese Umfrage zur Verfügung</p>
		{/section}
		</div>

		{if $show_revote_link eq TRUE}
			<p id="voteagain"><a href="{$php_self}?poll={$poll_id}">» Nochmal Abstimmen »</a></p>
		{/if}
		{if $show_next_revote eq TRUE}
			<p id="nextvote">Nächste Abstimmung in <span>{$next_revote_min}</span> Minuten</p>
		{/if}

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
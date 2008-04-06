<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
	<head>
		<title>Umfrage - {$poll_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			<!--
			@import url("generalstyles.css");
			@import url("{$path}eberharter.css");
			-->
		</style>
	</head>
	<body>
		<h6><span>Networld Umfrage</span></h6>
		<h2><span>Österreich jagt den Superstar</span></h2>
		<div id="participants">
			<div id="pollcontent">
				<h1>{$poll_title}</h1>
				<p>{$poll_description}</p>
				<table><tr valign="top"><td>
					{section name=id loop=$option_ids max=$sum_polloptions_half}
						{if $display_active_only eq FALSE || ($option_active[id] eq TRUE && $display_active_only eq TRUE)}
							<div class="participant">
								{if $option_active[id] eq TRUE}
									<img src="{$option_image[id]}" alt="{$option_titles[id]}" class="portrait" />
									<h3>{$option_titles[id]}
									{if $show_percent eq TRUE}
										<small>{$option_percent_exact[id]} %
										{if $show_absolute eq TRUE}
											 ({$option_votes[id]} Stimmen)
										{/if}
										</small>
									{elseif $show_absolute eq TRUE}
										<small>{$option_votes[id]} Stimmen</small>
									{/if}
									</h3>
									<img src="{$path}pix.gif" alt="Balken" height="23" class="block" width="{$option_percent[id]*2+1}" />
								{else}
									<img src="{$option_inactive_image[id]}" alt="{$option_titles[id]}" class="portrait" />
									<h3 class="inactive">{$option_titles[id]}
									{if $show_percent eq TRUE}
										<small>{$option_percent_exact[id]} %
										{if $show_absolute eq TRUE}
											 ({$option_votes[id]} Stimmen)
										{/if}
										</small>
									{elseif $show_absolute eq TRUE}
										<small>{$option_votes[id]} Stimmen</small>
									{/if}
									</h3>
									<img src="{$path}pix_inactive.gif" alt="Balken" height="23" class="block" width="{$option_percent[id]*2+1}" />
								{/if}
							</div>
						{/if}
					{sectionelse}
						<p>Es stehen keine Antwortmöglichkeiten für diese Umfrage zur Verfügung</p>
					{/section}
				</td><td>
				{section name=id loop=$option_ids start=$sum_polloptions_half}
						{if $display_active_only eq FALSE || ($option_active[id] eq TRUE && $display_active_only eq TRUE)}
							<div class="participant" title="{$option_description[id]}">
								{if $option_active[id] eq TRUE}
									<img src="{$option_image[id]}" alt="{$option_titles[id]}" class="portrait" />
									<h3>{$option_titles[id]}
									{if $show_percent eq TRUE}
										<small>{$option_percent_exact[id]} %
										{if $show_absolute eq TRUE}
											 ({$option_votes[id]} Stimmen)
										{/if}
										</small>
									{elseif $show_absolute eq TRUE}
										<small>{$option_votes[id]} Stimmen</small>
									{/if}
									</h3>
									<img src="{$path}pix.gif" alt="Balken" height="23" class="block" width="{$option_percent[id]*2+1}" />
								{else}
									<img src="{$option_inactive_image[id]}" alt="{$option_titles[id]}" class="portrait" />
									<h3 class="inactive">{$option_titles[id]}
									{if $show_percent eq TRUE}
										<small>{$option_percent_exact[id]} %
										{if $show_absolute eq TRUE}
											 ({$option_votes[id]} Stimmen)
										{/if}
										</small>
									{elseif $show_absolute eq TRUE}
										<small>{$option_votes[id]} Stimmen</small>
									{/if}
									</h3>
									<img src="{$path}pix_inactive.gif" alt="Balken" height="23" class="block" width="{$option_percent[id]*2+1}" />
								{/if}
							</div>
						{/if}
					{/section}
				</td></tr></table>
			</div>
		</div>
		<div id="submit">
			{if $show_revote_link eq TRUE}
				<a href="{$php_self}?poll={$poll_id}"><img src="{$path}revote" width="163" height="25" alt="Nochmal abstimmen" /></a>
			{/if}
			{if $show_next_revote eq TRUE}
				<b id="nextvote">Nächste Abstimmung in <span>{$next_revote_min}</span> Minuten</b>
			{/if}
		</div>



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
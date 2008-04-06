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
		<form action="{$php_self}" method="post" id="participants">
			<div id="pollcontent">
				<h1>{$poll_title}</h1>
				<p>{$poll_description}</p>

				{if $count_errors > 0}
					<ul class="errors">
					{section name=error loop=$errors}
						<li>{$errors[error]}</li>
					{/section}
					</ul>
				{/if}

				<table><tr valign="top"><td>
					{section name=id loop=$option_ids max=$sum_polloptions_half}
						{if $display_active_only eq FALSE || ($option_active[id] eq TRUE && $display_active_only eq TRUE)}
							<div class="participant">
								{if $option_active[id] eq TRUE}
									<label for="selection-{$option_ids[id]}"><img src="{$option_image[id]}" alt="{$option_titles[id]}" class="portrait" /></label>
									<h3>
									{if $multiple_choise eq TRUE}
										<input type="checkbox" name="selection[{$option_ids[id]}]" id="selection-{$option_ids[id]}" value="{$option_ids[id]}" />
									{else}
										<input type="radio" name="selection" id="selection-{$option_ids[id]}" value="{$option_ids[id]}" />
									{/if}
									<label for="selection-{$option_ids[id]}">{$option_titles[id]}</label></h3>
								{else}
									<img src="{$option_inactive_image[id]}" alt="{$option_titles[id]}" class="portrait" />
									<h3 class="inactive"><span class="option-placeholder"></span>{$option_titles[id]}</h3>
								{/if}
								<p class="description"><label for="selection-{$option_ids[id]}">{$option_descriptions[id]}</label></p>
							</div>
						{/if}
					{sectionelse}
						<p>Es stehen keine Antwortmöglichkeiten für diese Umfrage zur Verfügung</p>
					{/section}
				</td><td>
					{section name=id loop=$option_ids start=$sum_polloptions_half}
						{if $display_active_only eq FALSE || ($option_active[id] eq TRUE && $display_active_only eq TRUE)}
							<div class="participant">
								{if $option_active[id] eq TRUE}
									<label for="selection-{$option_ids[id]}"><img src="{$option_image[id]}" alt="{$option_titles[id]}" class="portrait" /></label>
									<h3>
									{if $multiple_choise eq TRUE}
										<input type="checkbox" name="selection[{$option_ids[id]}]" id="selection-{$option_ids[id]}" value="{$option_ids[id]}" />
									{else}
										<input type="radio" name="selection" id="selection-{$option_ids[id]}" value="{$option_ids[id]}" />
									{/if}
									<label for="selection-{$option_ids[id]}">{$option_titles[id]}</label></h3>
								{else}
									<img src="{$option_inactive_image[id]}" alt="{$option_titles[id]}" class="portrait" />
									<h3 class="inactive"><span class="option-placeholder"></span>{$option_titles[id]}</h3>
								{/if}
								<p class="description"><label for="selection-{$option_ids[id]}">{$option_descriptions[id]}</label></p>
							</div>
						{/if}
					{/section}
				</td></tr></table>
				{$form_token}
				<input type="hidden" name="poll" value="{$poll_id}" />

			</div>
			<div id="submit">
				<input type="hidden" name="vote" value="1" />
				<input type="image" src="{$path}vote.gif" alt="{$button_label}" name="sendvote" id="sendvote" value="1" />
			</div>
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
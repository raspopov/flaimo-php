{config_load file=poll.conf}
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
		<h1><span>{$poll_title}</span></h1>
		<p>{$poll_description}</p>
		<p id="offline">{#poll_not_online#}</p>
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
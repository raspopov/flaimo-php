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
			@import url("{$path}poll_olympia2004_metals.css");

			body {ldelim}
				background: #fff url({$path}background.jpg) left top no-repeat;
			{rdelim}
			-->
		</style>
	</head>
	<body>
		<p id="offline">{#poll_not_online#}</p>
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
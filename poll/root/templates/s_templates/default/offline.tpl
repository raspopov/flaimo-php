{config_load file=poll.conf}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
	<head>
		<title>Umfrage - {$poll_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		{#poll_not_online#}
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
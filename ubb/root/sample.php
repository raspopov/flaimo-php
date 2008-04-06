<?php
$string = '
== Lorem ipsum dolor ==

Lorem ipsum dolor sit amet, [url]http://example.com/very_long_dir/and_another_long/subdir/with_even_more_subdirs/index.php?x=1&y=2[/url] consectetuer adipiscing elit, sed diam nonummy nibh [url=http://cnn.com|Lorem ipsum dolor]euismod tincidunt ut laoreet dolore magna[/url] aliquam erat volutpat.
Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis [url=http://cnn.com]nisl[/url] ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue [mail]flaimo@gmx.net[/mail] duis dolore te [mail=flaimo@gmx.net]feugait[/mail] nulla facilisi.
Lorem ipsum dolor sit amet, [mail=flaimo@gmx.net|Lorem ipsum dolor]consectetuer[/mail] adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum.

[del=2005-01-18T16:05+01:00]Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.[/del] [ins=http://www.example.com]Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero et accumsan et [d]iusto odio dignissim[/d] qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum.[/ins]


=== Lorem ipsum dolor ===

<b>Lorem ipsum dolor sit amet, consectetuer adipiscing [,]elit[/,], sed [;]diam[/;] nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
Ut wisi enim ad minim veniam, [b][i]quis nostrud[/i][/b] exerci tation ullamcorper suscipit –—― lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit [c]augue duis dolore[/c] te feugait nulla facilisi.
Lorem ipsum dolor sit amet, consectetuer adipiscing elit, [q]sed diam nonummy[/q] nibh [q=en]euismod[/q] tincidunt [q=de-at|http://www.networld.at]ut[/q] laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud "exerci" "tation" ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat [b]nulla facilisis[/b] at [i]vero[/i] et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue [ac=de|Lorem ipsum dolor]duis dolore[/ac] te [ab=de|Lorem ipsum dolor]feugait[/ab] nulla facilisi.</i> Nam liber tempor cum soluta nobis eleifend option congue nihil × -–—―−‐‑imperdiet doming id quod mazim placerat facer possim assum.
[ad]Schäffergasse 20/39
1040 Wien
Österreich[/ad]

==== Lorem [v]ipsum[/v] dolor ====

Lorem ipsum dolor sit amet, consectetuer [/b]adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.

[bq]Ut wisi enim ad [sp]minim veniam, quis nostrud exerci tation ullamcorper suscipit[/sp] lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.[/bq]

[bq=de-at|http://www.orf.at/test.jsp?x=3&y=4]Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum.[/bq]

==== Lorem ipsum dolor ====

[ul]
[x] [b]Haus[/b]
[x] [i]Baum[/i]
[x] Garten
[/ul]

==== Lorem ipsum dolor ====

[ol]
[x] Haus
[x] Baum
[x] Garten
[/ol]
';

require_once '../inc/class.ubbcode.inc.php';
$ubb_red = new ubbCode('red', false);

echo '<html><head><title>UBB</title><style type="text/css"> .spoiler { color: #fff; } .uppercase { text-transform:uppercase }</style></head><body>';

echo "\n\n\n\n" , '<h1>Raw string</h1>' , "\n\n\n\n";
echo nl2br(htmlspecialchars($string));
echo "\n\n\n\n" , '<hr />' , "\n\n\n\n";

echo "\n\n\n\n" , '<h1>UBB string</h1>' , "\n\n\n\n";
echo $ubb_red->encode($string);
echo "\n\n\n\n" , '<hr />' , "\n\n\n\n";

echo  "\n\n\n\n" , '<h1>Stripped string</h1>' , "\n\n\n\n";
echo nl2br($ubb_red->stripUBBcode(strip_tags($string)));

echo '</body></html>';
?>
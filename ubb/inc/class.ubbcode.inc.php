<?php
// utf-8 only
class ubbCode {
	protected $patterns;
	protected $group;
	protected $strip_unallowed_tags;
	protected $allowed_groups = array('red', 'com');
	protected $spoiler_title = 'Markieren Sie den unsichtbaren Text um ihn zu lesen';

	function __construct($group = 'com', $strip_unallowed_tags = FALSE) {
		$this->setGroup($group);
		$this->setPatterns();
		$this->strip_unallowed_tags = $strip_unallowed_tags;
	} // end constrcutor

	protected function setGroup($group = 'com') {
		if (in_array($group, $this->allowed_groups)) {
			$this->group = $group;
		} // end if
	} // end function

	protected function setPatterns() {
		$patterns = array(
						array(
							'pattern' => array('#\[b\](.+?)\[/b\]#ius', '<b>\\1</b>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[i\](.+?)\[/i\]#ius', '<i>\\1</i>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[c\](.+?)\[/c\]#ius', '<cite>\\1</cite>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[d\](.+?)\[/d\]#ius', '<dfn>\\1</dfn>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[v\](.+?)\[/v\]#ius', '<span class="uppercase">\\1</span>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[,\](.+?)\[/,\]#ius', '<sub>\\1</sub>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[;\](.+?)\[/;\]#ius', '<sup>\\1</sup>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[sp\](.+?)\[/sp\]#ius', '<span class="spoiler" title="' . $this->spoiler_title . '">\\1 </span>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[ad\](.+?)\[/ad\]#ius', '<address>\\1</address>'),
							'groups' => array('red'),
							'type' => 1
						),
						array(
							'pattern' => array('#\[url=(.+?)\|(.+?)\]((.|\n|\r)+?)\[/url\]#iu', '<a href="\\1" target="_blank" title="\\2">\\3</a>'),
							'groups' => array('red'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[url=(.+?)\]((.|\n|\r)+?)\[/url\]#iu', '<a href="\\1" target="_blank">\\2</a>'),
							'groups' => array('red'),
							'type' => 0
						),

						array(
							'pattern' => array('#\[url\]((http|https|ftp)://(.+?))\[/url\]#iue', "'<a href=\"\$1\" target=\"_blank\">' . ((strlen('\$1') > 40) ? substr('\$1', 0, 40) . '…': '\$1') . '</a>'"),
							'groups' => array('red'),
							'type' => 0
						),

						array(
							'pattern' => array('#\[mail=([_a-z0-9-]+(\.[_a-z0-9-]+)*@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2}|[a-z]{3}|shop|aero|coop|info|museum|name))\|(.+?)\]((.|\n|\r)+?)\[/mail\]#iu', '<a href="mailto:\\1?subject=\\5">\\6</a>'),
							'groups' => array('red'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[mail=([_a-z0-9-]+(\.[_a-z0-9-]+)*@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2}|[a-z]{3}|shop|aero|coop|info|museum|name))\]((.|\n|\r)+?)\[/mail\]#iu', '<a href="mailto:\\1">\\5</a>'),
							'groups' => array('red'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[mail\]([_a-z0-9-]+(\.[_a-z0-9-]+)*@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2}|[a-z]{3}|shop|aero|coop|info|museum|name))\[/mail\]#iu', '<a href="mailto:\\1">\\1</a>'),
							'groups' => array('red'),
							'type' => 0
						),
						array(
							'pattern' => array('#\={4}(.+?)\={4}#ius', '<h4>\\1</h4>'),
							'groups' => array('red'),
							'type' => 1
						),
						array(
							'pattern' => array('#\={3}(.+?)\={3}#ius', '<h3>\\1</h3>'),
							'groups' => array('red'),
							'type' => 1
						),
						array(
							'pattern' => array('#\={2}(.+?)\={2}#ius', '<h2>\\1</h2>'),
							'groups' => array('red'),
							'type' => 1
						),
						array(
							'pattern' => array('#\[bq=(([a-zA-Z]{2})(-[a-zA-Z]{2}){0,1})\|(.+?)\]((.|\n|\r)+?)\[/bq\]#iu', '<blockquote lang="\\1" xml:lang="\\1" cite="\\4">\\5</blockquote>'),
							'groups' => array('red'),
							'type' => 1
						),
						array(
							'pattern' => array('#\[bq=(([a-zA-Z]{2})(-[a-zA-Z]{2}){0,1})\]((.|\n|\r)+?)\[/bq\]#iu', '<blockquote lang="\\1" xml:lang="\\1">\\2</blockquote>'),
							'groups' => array('red'),
							'type' => 1
						),
						array(
							'pattern' => array('#\[bq\](.+?)\[/bq\]#ius', '<blockquote>\\1</blockquote>'),
							'groups' => array('red'),
							'type' => 1
						),
						array(
							'pattern' => array('#\[q=(([a-zA-Z]{2})(-[a-zA-Z]{2}){0,1})\|(.+?)\]((.|\n|\r)+?)\[/q\]#iu', '<q lang="\\1" xml:lang="\\1" cite="\\4">\\5</q>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[q=(([a-zA-Z]{2})(-[a-zA-Z]{2}){0,1})\]((.|\n|\r)+?)\[/q\]#iu', '<q lang="\\1" xml:lang="\\1">\\2</q>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[q\](.+?)\[/q\]#ius', '<q>\\1</q>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[ab=(([a-zA-Z]{2})(-[a-zA-Z]{2}){0,1})\|(.+?)\]((.|\n|\r)+?)\[/ab\]#iu', '<abbr lang="\\1" xml:lang="\\1" title="\\4">\\5</abbr>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[ac=(([a-zA-Z]{2})(-[a-zA-Z]{2}){0,1})\|(.+?)\]((.|\n|\r)+?)\[/ac\]#iu', '<acronym lang="\\1" xml:lang="\\1" title="\\4">\\5</acronym>'),
							'groups' => array('red', 'com'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[ul\](.+?)\[/ul\]#ius', '<ul><li>\\1</li></ul>'),
							'groups' => array('red', 'com'),
							'type' => 1
						),
						array(
							'pattern' => array('#\[ol\](.+?)\[/ol\]#ius', '<ol><li>\\1</li></ol>'),
							'groups' => array('red', 'com'),
							'type' => 1
						),
						array(
							'pattern' => array('#\[x\] #iu', '</li><li>'),
							'groups' => array('red', 'com'),
							'type' => 1
						),
						array(
							'pattern' => array('#\[ins=(.+?)\]((.|\n|\r)+?)\[/ins\]#iu', '<ins cite="\\1">\\2</ins>'),
							'groups' => array('red'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[ins\](.+?)\[/ins\]#ius', '<ins>\\1</ins>'),
							'groups' => array('red'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[del=(.+?)\]((.|\n|\r)+?)\[/del\]#iu', '<del datetime="\\1">\\2</del>'),
							'groups' => array('red'),
							'type' => 0
						),
						array(
							'pattern' => array('#\[del\](.+?)\[/del\]#ius', '<del>\\1</del>'),
							'groups' => array('red'),
							'type' => 0
						)
					);

		foreach ($patterns as &$rule) {
			if (!in_array($this->group, $rule['groups'])) {
				continue;
			} // end if
			$this->patterns[] = $rule;
		} // end foreach
	} // end function

	public function ubb2html($string = '', $inline_only = FALSE) {
		foreach ($this->patterns as &$rule) {
			if ($inline_only == TRUE && $rule['type'] > 0) {
				continue;
			} // end if

			$string = preg_replace($rule['pattern'][0], $rule['pattern'][1], $string);
		} // end foreach

		if ($this->strip_unallowed_tags == TRUE) {
			$string = $this->stripUBBcode($string);
		} // end if
		return $string;
	} // end function

	public function encode($string = '', $inline_only = FALSE) {
		$string = $this->prepareRawString($string);
		$string = $this->ubb2html($string, $inline_only);
		$string = $this->prepareFinishedString($string, $inline_only);
		if ($inline_only == FALSE) {
			$string = '<p>' . $string . '</p>';
			$string = str_replace('<h2>', '</p><h2>', $string);
			$string = str_replace('<h3>', '</p><h3>', $string);
			$string = str_replace('<h4>', '</p><h4>', $string);
			$string = str_replace('<ul>', '</p><ul>', $string);
			$string = str_replace('<ol>', '</p><ol>', $string);
			$string = str_replace('<blockquote', '</p><blockquote', $string);
			$string = str_replace('</h2>', '</h2><p>', $string);
			$string = str_replace('</h3>', '</h3><p>', $string);
			$string = str_replace('</h4>', '</h4><p>', $string);
			$string = str_replace('</ul>', '</ul><p>', $string);
			$string = str_replace('</ol>', '</ol><p>', $string);
			$string = str_replace('</blockquote>', '</blockquote><p>', $string);
		$string = str_replace("<p><br />
<br />
", '<p>', $string);
		$string = str_replace("<br />
<br />
</p>", '</p>', $string);
		$string = str_replace("<br />
<br />", '</p><p>', $string);

		$string = str_replace(array('<p></p>', '<p><br />
</p>', '<p><br />
<br />
</p>',), '', $string);
		$string = str_replace("<br />
</li>", '</li>', $string);
		$string = str_replace('<li></li>', '', $string);
		} // end if

		return $this->stripUBBcode($string);
	} // end function

	public function stripUBBcode($string = '') {
		$string = preg_replace('#\[url=(.+?)\|(.+?)\]((.|\n|\r)+?)\[/url\]#iu', '\\3', $string);
		$string = preg_replace('#\[(url|ins|del)=(.+?)\]((.|\n|\r)+?)\[/(url|ins|del)\]#iu', '\\3', $string);
		$string = preg_replace('#\[mail=([_a-z0-9-]+(\.[_a-z0-9-]+)*@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2}|[a-z]{3}|shop|aero|coop|info|museum|name))\|(.+?)\]((.|\n|\r)+?)\[/mail\]#iu', '\\6', $string);
		$string = preg_replace('#\[mail=([_a-z0-9-]+(\.[_a-z0-9-]+)*@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2}|[a-z]{3}|shop|aero|coop|info|museum|name))\]((.|\n|\r)+?)\[/mail\]#iu', '\\5', $string);
		$string = preg_replace('#\={2,4}(.+?)\={2,4}#ius', '\\1', $string);
		$string = preg_replace('#\[(bq|q|ab|ac)=(([a-zA-Z]{2})(-[a-zA-Z]{2}){0,1})\|(.+?)\]((.|\n|\r)+?)\[/(bq|q|ab|ac)\]#iu', '\\6', $string);
		$string = preg_replace('#\[bq=(([a-zA-Z]{2})(-[a-zA-Z]{2}){0,1})\]((.|\n|\r)+?)\[/bq\]#iu', '\\4', $string);
		$string = preg_replace('#\[q=(([a-zA-Z]{2})(-[a-zA-Z]{2}){0,1})\]((.|\n|\r)+?)\[/q\]#iu', '\\4', $string);
		$simple_ubbtags = array('[b]', '[/b]', '[i]', '[/i]', '[q]', '[/q]', '[url]', '[/url]', '[mail]', '[/mail]', '[c]', '[/c]', '[bq]', '[/bq]', '[ad]', '[/ad]', '[sp]', '[/sp]', '[ul]', '[/ul]', '[ol]', '[/ol]', '[x]', '[del]', '[/del]', '[ins]', '[/ins]', '[d]', '[/d]', '[,]', '[/,]', '[;]', '[/;]', '[v]', '[/v]');
		$string = str_ireplace($simple_ubbtags, '', $string);
		return $string;
	} // end function

	public function prepareRawString($string = '') {
		return htmlspecialchars(strip_tags($string), ENT_COMPAT, 'utf-8');
	} // end function

	public function prepareFinishedString($string = '', $inline_only = FALSE) {
		if ($inline_only == TRUE) {
			return $string;
		} // end if
		return (nl2br($string));
	} // end function
} // end class
?>
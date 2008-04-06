<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.27/4.0.12/5.0.0b2-dev)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2003 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| I don't have the time to read through all the licences to find out   |
//| what the exactly say. But it's simple. It's free for non commercial  |
//| projects, but as soon as you make money with it, i want my share :-) |
//| (License : Free for non-commercial use)                              |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmx.net>                             |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package RSSBuilder
* @category FLP
*/
/**
* Abstract class for getting ini preferences
*
* Tested with WAMP (XP-SP1/1.3.27/4.0.12/5.0.0b2-dev)
* Last change: 2003-07-07
*
* @desc Abstract class for the RSS classes
* @access protected
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @global array $GLOBALS['_TICKER_ini_settings']
* @abstract
* @package RSSBuilder
* @category FLP
* @version 2.000
*/
class RSSBase {

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @return void
	*/
	function __construct() {
	} // end constructor

} // end class RSSBase

//---------------------------------------------------------------------------

/**
* Class for creating a RSS file
*
* Tested with WAMP (XP-SP1/1.3.27/4.0.12/5.0.0b2-dev)
* Last change: 2003-07-07
*
* @desc Class for creating a RSS file
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @example rss_sample_script.php Sample script
* @package RSSBuilder
* @category FLP
* @version 2.000
*/
class RSSBuilder extends RSSBase {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @var string
	*/
	/**
	* encoding of the XML file
	*/
	private $encoding;

	/**
	* URL where the RSS document will be made available
	*/
	private $about;

	/**
	* title of the rss stream
	*/
	private $title;

	/**
	* description of the rss stream
	*/
	private $description;

	/**
	* publisher of the rss stream (person, an organization, or a service)
	*/
	private $publisher;

	/**
	* creator of the rss stream (person, an organization, or a service)
	*/
	private $creator;

	/**
	* creation date of the file (format: 2003-05-29T00:03:07+0200)
	*/
	private $date;

	/**
	* iso format language
	*/
	private $language;

	/**
	* copyrights for the rss stream
	*/
	private $rights;

	/**
	* URL to an small image
	*/
	private $image_link;

	/**
	* spatial location, temporal period or jurisdiction
	*
	* spatial location (a place name or geographic coordinates), temporal
	* period (a period label, date, or date range) or jurisdiction (such as a
	* named administrative entity)
	*/
	private $coverage;

	/**
	* person, an organization, or a service
	*/
	private $contributor;

	/**
	* 'hourly' | 'daily' | 'weekly' | 'monthly' | 'yearly'
	*/
	private $period;

	/**
	* date (format: 2003-05-29T00:03:07+0200)
	*
	* Defines a base date to be used in concert with updatePeriod and
	* updateFrequency to calculate the publishing schedule.
	*/
	private $base;

	/**
	* category (rss 2.0)
	*
	* @since 1.001 - 2003-05-30
	*/
	private $category;

	/**
	* compiled outputstring
	*/
	private $output;
	/**#@-*/

	/**
	* every X hours/days/weeks/...
	*
	* @var int
	*/
	private $frequency;

	/**
	* caching time in minutes (rss 2.0)
	*
	* @var int
	* @since 1.001 - 2003-05-30
	*/
	private $cache;

	/**
	* array wich all the rss items
	*
	* @var array
	*/
	private $items = array();

	/**
	* use DC data
	*
	* @var boolean
	*/
	private $use_dc_data = FALSE;

	/**
	* use SY data
	*
	* @var boolean
	*/
	private $use_sy_data = FALSE;


	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**#@+
	* @return void
	*/
	/**
	* Constructor
	*
	* @param string $encoding encoding of the xml file
	* @param string $about URL where the RSS document will be made available
	* @param string $title
	* @param string $description
	* @param string $image_link  URL
	* @uses setEncoding()
	* @uses setStringValues()
	* @uses setCache()
	*/
	public function __construct($encoding = '', $about = '', $title = '',
								$description = '', $image_link = '',
								$category = '', $cache = '') {
		$this->setEncoding($encoding);
		$this->setStringValues('about',$about);
		$this->setStringValues('title',$title);
		$this->setStringValues('description',$description);
		$this->setStringValues('image_link', $image_link);
		$this->setStringValues('category', $category);
		$this->setCache($cache);
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* add additional DC data
	*
	* @param string $publisher person, an organization, or a service
	* @param string $creator person, an organization, or a service
	* @param string $date  format: 2003-05-29T00:03:07+0200
	* @param string $language  iso-format
	* @param string $rights  copyright information
	* @param string $coverage  spatial location (a place name or geographic coordinates), temporal period (a period label, date, or date range) or jurisdiction (such as a named administrative entity)
	* @param string $contributor  person, an organization, or a service
	* @uses setStringValues()
	* @uses setLanguage()
	* @uses RSSBuilder::$use_dc_data
	*/
	public function addDCdata($publisher = '', $creator = '', $date = '',
							  $language = '',  $rights = '', $coverage = '',
							  $contributor = '') {
		$this->setStringValues('publisher',$publisher);
		$this->setStringValues('creator',$creator);
		$this->setStringValues('date',$date);
		$this->setLanguage($language);
		$this->setStringValues('rights',$rights);
		$this->setStringValues('coverage',$coverage);
		$this->setStringValues('contributor',$contributor);
		$this->use_dc_data = (boolean) TRUE;
	} // end function

	/**
	* add additional SY data
	*
	* @param string $period  'hourly' | 'daily' | 'weekly' | 'monthly' | 'yearly'
	* @param int $frequency  every x hours/days/weeks/...
	* @param string $base  format: 2003-05-29T00:03:07+0200
	* @uses setPeriod()
	* @uses setFrequency()
	* @uses setStringValues()
	* @uses RSSBuilder::$use_sy_data
	*/
	public function addSYdata($period = '', $frequency = '', $base = '') {
		$this->setPeriod($period);
		$this->setFrequency($frequency);
		$this->setStringValues('base', $base);
		$this->use_sy_data = (boolean) TRUE;
	} // end function

	/**
	* Sets the value for a class variable
	*
	* @param string $var class variable
	* @param string $value the value to be assigned
	*/
	private function setStringValues($var, $value) {
		if (!isset($this->$var) && strlen(trim($value)) > 0) {
			$this->$var = (string) trim($value);
		} // end if
	} // end function

	/**
	* Sets $encoding variable
	*
	* @param string $encoding  encoding of the xml file
	* @uses RSSBuilder::$encoding
	*/
	private function setEncoding($encoding = '') {
		if (!isset($this->encoding)) {
			$this->encoding = (string) ((strlen(trim($encoding)) > 0) ? trim($encoding) : 'UTF-8');
		} // end if
	} // end function

	/**
	* Sets $language variable
	*
	* @param string $language
	* @uses isValidLanguageCode()
	* @uses RSSBuilder::$language
	*/
	private function setLanguage($language = '') {
		if (!isset($this->language) && $this->isValidLanguageCode($language) === TRUE) {
			$this->language = (string) trim($language);
		} // end if
	} // end function

	/**
	* Sets $period variable
	*
	* @param string $period  'hourly' | 'daily' | 'weekly' | 'monthly' | 'yearly'
	* @uses RSSBuilder::$period
	*/
	private function setPeriod($period = '') {
		if (!isset($this->period) && strlen(trim($period)) > 0) {
			switch ($period) {
				case 'hourly':
				case 'daily':
				case 'weekly':
				case 'monthly':
				case 'yearly':
					$this->period = (string) trim($period);
					break;
				default:
					$this->period = (string) '';
					break;
			} // end switch
		} // end if
	} // end function

	/**
	* Sets $frequency variable
	*
	* @param int $frequency
	* @uses RSSBuilder::$frequency
	*/
	private function setFrequency($frequency = '') {
		if (!isset($this->frequency) && strlen(trim($frequency)) > 0) {
			$this->frequency = (int) $frequency;
		} // end if
	} // end function

	/**
	* Sets $cache variable
	*
	* @param int $cache
	* @uses RSSBuilder::$cache
	* @since 1.001 - 2003-05-30
	*/
	private function setCache($cache = '') {
		if (strlen(trim($cache)) > 0) {
			$this->cache = (int) $cache;
		} // end if
	} // end function
	/**#@-*/

	/**
	* Checks if a given string is a valid iso-language-code
	*
	* @param string $code String that should validated
	* @return boolean If string is valid or not
	*/
	public static final function isValidLanguageCode($code = '') {
		return (boolean) ((preg_match('(^([a-zA-Z]{2})$)',$code) > 0) ? TRUE : FALSE);
	} // end function

	/**
	* Adds another rss item to the object
	*
	* @param string $about  URL
	* @param string $title
	* @param string $link  URL
	* @param string $description (optional)
	* @param string $subject  some sort of category (optional dc value - only shows up if DC data has been set before)
	* @param string $date  format: 2003-05-29T00:03:07+0200 (optional dc value - only shows up if DC data has been set before)
	* @param string $author  some sort of category author of item
	* @param string $comments  url to comment page rss 2.0 value
	* @param string $image  optional mod_im value for dispaying a different pic for every item
	* @return void
	* @uses RSSBuilder::$items
	* @uses RSSItem
	*/
	public function addItem($about = '', $title = '', $link = '',
							$description = '', $subject = '', $date = '',
							$author = '', $comments = '', $image = '') {

		$this->items[] = new RSSItem($about, $title, $link, $description,
									 $subject, $date, $author, $comments,
									 $image);
	} // end function

	/**
	* Deletes a rss item from the array
	*
	* @param int $id id of the element in the $items array
	* @return boolean true if item was deleted
	* @uses RSSBuilder::$items
	*/
	public function deleteItem($id = -1) {
		if (array_key_exists($id, $this->items)) {
			unset($this->items[$id]);
			return (boolean) TRUE;
		} else {
			return (boolean) FALSE;
		} // end if
	} // end function

	/**
	* Returns an array with all the keys of the $items array
	*
	* @return array array with all the keys of the $items array
	* @uses RSSBuilder::$items
	*/
	protected function getItemList() {
		return (array) array_keys($this->items);
	} // end function

	/**
	* Returns the $items array
	*
	* @return array $items
	* @uses RSSBuilder::$items
	*/
	protected function &getItems() {
		return $this->items;
	} // end function

	/**
	* Returns a single rss item by ID
	*
	* @param int $id  id of the element in the $items array
	* @return mixed RSSItem or FALSE
	* @uses RSSBuilder::$items
	* @see RSSItem
	*/
	protected function getItem($id = -1) {
		if (array_key_exists($id, $this->items)) {
			return (object) $this->items[$id];
		} else {
			return (boolean) FALSE;
		} // end if
	} // end function

	/**#@+
	* @return void
	*/
	/**
	* Checks a single item if it is available and adds it to the output string
	*
	* @param string $var  class variable
	* @param string $prefix string to be added before the variable value
	* @param string $sufix string to be added after the variable value
	* @uses RSSBuilder::$output
	* @since 2.001 - 2003-07-07
	*/
	private function addSingleOutput($var = 'x', $prefix = '', $sufix = '') {
		if (isset($this->$var)) {
			$this->output .= (string) $prefix . $this->$var . $sufix . "\n";
		} // end if
	} // end function

	/**
	* creates the output based on the 0.91 rss version
	*
	* @uses createOutputV100()
	*/
	private function createOutputV090() {
		// not implemented
		$this->createOutputV100();
	} // end function

	/**
	* creates the output based on the 0.91 rss version
	*
	* @uses RSSBuilder::$output
	* @since 1.001 - 2003-05-30
	*/
	private function createOutputV091() {
		$this->output  = (string) '<!DOCTYPE rss SYSTEM "http://my.netscape.com/publish/formats/rss-0.91.dtd">' . "\n";
		$this->output .= (string) '<rss version="0.91">' . "\n";
		$this->output .= (string) '<channel>' . "\n";
		$this->addSingleOutput('rights', '<copyright>', '</copyright>');
		$this->addSingleOutput('date', '<pubDate>', '</pubDate>');
		$this->addSingleOutput('date', '<lastBuildDate>', '</lastBuildDate>');
		$this->addSingleOutput('about', '<docs>', '</docs>');
		$this->addSingleOutput('description', '<description>', '</description>');
		$this->addSingleOutput('about', '<link>', '</link>');
		$this->addSingleOutput('title', '<title>', '</title>');

		if (isset($this->image_link)) {
			$this->output .= (string) '<image>' . "\n";
			$this->output .= (string) '<title>' . $this->title . '</title>' . "\n";
			$this->output .= (string) '<url>' . $this->image_link . '</url>' . "\n";
			$this->output .= (string) '<link>' . $this->about . '</link>' . "\n";
			$this->addSingleOutput('description', '<description>', '</description>');
			$this->output .= (string) '</image>' . "\n";
		} // end if

		$this->addSingleOutput('publisher', '<managingEditor>', '</managingEditor>');
		$this->addSingleOutput('creator', '<webMaster>', '</webMaster>');
		$this->addSingleOutput('language', '<language>', '</language>');

		if (count($this->getItemList()) > 0) {
			foreach ($this->getItemList() as $id) {
				$item =& $this->items[$id];

				if (isset($item->title) && isset($item->link)) {
					$this->output .= (string) '<item>' . "\n";
					$this->output .= (string) '<title>' . $item->title . '</title>' . "\n";
					$this->output .= (string) '<link>' . $item->link . '</link>' . "\n";
					if (strlen($item->description) > 0) {
						$this->output .= (string) '<description>' . $item->description . '</description>' . "\n";
					} // end if
					$this->output .= (string) '</item>' . "\n";
				} // end if
			} // end foreach
		} // end if

		$this->output .= (string) '</channel>' . "\n";
		$this->output .= (string) '</rss>' . "\n";
	} // end function

	/**
	* creates the output based on the 1.0 rss version
	*
	* @uses RSSBuilder::$output
	*/
	private function createOutputV100() {
		$this->output  = (string) '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:im="http://purl.org/rss/1.0/item-images/" ';

		if ($this->use_dc_data === TRUE) {
			$this->output .= (string) 'xmlns:dc="http://purl.org/dc/elements/1.1/" ';
		} // end if

		if ($this->use_sy_data === TRUE) {
			$this->output .= (string) 'xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" ';
		} // end if

		$this->output .= (string) 'xmlns="http://purl.org/rss/1.0/">' . "\n";

		if (isset($this->about)) {
			$this->output .= (string) '<channel rdf:about="' . $this->about . '">' . "\n";
		} else {
			$this->output .= (string) '<channel>' . "\n";
		} // end if

		$this->addSingleOutput('title', '<title>', '</title>');
		$this->addSingleOutput('about', '<link>', '</link>');
		$this->addSingleOutput('description', '<description>', '</description>');
		// additional dc data
		$this->addSingleOutput('publisher', '<dc:publisher>', '</dc:publisher>');
		$this->addSingleOutput('creator', '<dc:creator>', '</dc:creator>');
		$this->addSingleOutput('date', '<dc:date>', '</dc:date>');
		$this->addSingleOutput('language', '<dc:language>', '</dc:language>');
		$this->addSingleOutput('rights', '<dc:rights>', '</dc:rights>');
		$this->addSingleOutput('coverage', '<dc:coverage>', '</dc:coverage>');
		$this->addSingleOutput('contributor', '<dc:contributor>', '</dc:contributor>');
		// additional SY data
		$this->addSingleOutput('period', '<sy:updatePeriod>', '</sy:updatePeriod>');
		$this->addSingleOutput('frequency', '<sy:updateFrequency>', '</sy:updateFrequency>');
		$this->addSingleOutput('base', '<sy:updateBase>', '</sy:updateBase>');

		if (isset($this->image_link)) {
			$this->output .= (string) '<image rdf:about="' . $this->image_link . '">' . "\n";
			$this->output .= (string) '<title>' . $this->title . '</title>' . "\n";
			$this->output .= (string) '<url>' . $this->image_link . '</url>' . "\n";
			$this->output .= (string) '<link>' . $this->about . '</link>' . "\n";
			if (isset($this->description)) {
				$this->output .= (string) '<description>' . $this->description . '</description>' . "\n";
			} // end if
			$this->output .= (string) '</image>' . "\n";
		} // end if

		if (count($this->getItemList()) > 0) {
			$this->output .= (string) '<items><rdf:Seq>' . "\n";
			foreach ($this->getItemList() as $id) {
				$item =& $this->items[$id];
				if (isset($item->about)) {
					$this->output .= (string) ' <rdf:li resource="' . $item->about . '" />' . "\n";
				} // end if
			} // end foreach
			$this->output .= (string) '</rdf:Seq></items>' . "\n";
		} // end if
		$this->output .= (string) '</channel>' . "\n";

		if (count($this->getItemList()) > 0) {
			foreach ($this->getItemList() as $id) {
				$item =& $this->items[$id];

				if (isset($item->title) && isset($item->link)) {
					if (isset($item->about)) {
						$this->output .= (string) '<item rdf:about="' . $item->about . '">' . "\n";
					} else {
						$this->output .= (string) '<item>' . "\n";
					} // end if

					$this->output .= (string) '<title>' . $item->title . '</title>' . "\n";
					$this->output .= (string) '<link>' . $item->link . '</link>' . "\n";

					if (isset($item->description)) {
						$this->output .= (string) '<description>' . $item->description . '</description>' . "\n";
					} // end if

					if ($this->use_dc_data === TRUE && isset($item->subject)) {
						$this->output .= (string) '<dc:subject>' . $item->subject . '</dc:subject>' . "\n";
					} // end if

					if ($this->use_dc_data === TRUE && isset($item->date)) {
						$this->output .= (string) '<dc:date>' . $item->date . '</dc:date>' . "\n";
					} // end if

					if (isset($item->image)) {
						$this->output .= (string) '<im:image>' . $item->image . '</im:image>' . "\n";
					} // end if

					$this->output .= (string) '</item>' . "\n";
				} // end if
			} // end foreach
		} // end if

		$this->output .= (string) '</rdf:RDF>';
	} // end function

	/**
	* creates the output based on the 2.0 rss draft
	*
	* @uses RSSBuilder::$output
	* @since 1.001 - 2003-05-30
	*/
	private function createOutputV200() {
		$this->output  = (string) '<rss version="2.0" xmlns:im="http://purl.org/rss/1.0/item-images/" ';

		if ($this->use_dc_data === TRUE) {
			$this->output .= (string) 'xmlns:dc="http://purl.org/dc/elements/1.1/" ';
		} // end if

		if ($this->use_sy_data === TRUE) {
			$this->output .= (string) 'xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" ';
		} // end if

		$this->output .= (string) '>' . "\n";

		$this->output .= (string) '<channel>' . "\n";

		$this->addSingleOutput('rights', '<copyright>', '</copyright>');

		$this->addSingleOutput('date', '<pubDate>', '</pubDate>');
		$this->addSingleOutput('date', '<lastBuildDate>', '</lastBuildDate>');
		$this->addSingleOutput('about', '<docs>', '</docs>');
		$this->addSingleOutput('description', '<description>', '</description>');
		$this->addSingleOutput('about', '<link>', '</link>');
		$this->addSingleOutput('title', '<title>', '</title>');

		if (isset($this->image_link)) {
			$this->output .= (string) '<image>' . "\n";
			$this->output .= (string) '<title>' . $this->title . '</title>' . "\n";
			$this->output .= (string) '<url>' . $this->image_link . '</url>' . "\n";
			$this->output .= (string) '<link>' . $this->about . '</link>' . "\n";
			$this->addSingleOutput('description', '<description>', '</description>');
			$this->output .= (string) '</image>' . "\n";
		} // end if

		if (isset($this->publisher)) {
			$this->output .= (string) '<managingEditor>' . $this->publisher . '</managingEditor>' . "\n";
		} // end if

		$this->addSingleOutput('creator', '<webMaster>', '</webMaster>');
		$this->addSingleOutput('creator', '<generator>', '</generator>');
		$this->addSingleOutput('language', '<language>', '</language>');
		$this->addSingleOutput('category', '<category>', '</category>');
		$this->addSingleOutput('cache', '<ttl>', '</ttl>');
		// additional dc data
		$this->addSingleOutput('publisher', '<dc:publisher>', '</dc:publisher>');
		$this->addSingleOutput('creator', '<dc:creator>', '</dc:creator>');
		$this->addSingleOutput('date', '<dc:date>', '</dc:date>');
		$this->addSingleOutput('language', '<dc:language>', '</dc:language>');
		$this->addSingleOutput('rights', '<dc:rights>', '</dc:rights>');
		$this->addSingleOutput('coverage', '<dc:coverage>', '</dc:coverage>');
		$this->addSingleOutput('contributor', '<dc:contributor>', '</dc:contributor>');
		// additional SY data
		$this->addSingleOutput('period', '<sy:updatePeriod>', '</sy:updatePeriod>');
		$this->addSingleOutput('frequency', '<sy:updateFrequency>', '</sy:updateFrequency>');
		$this->addSingleOutput('base', '<sy:updateBase>', '</sy:updateBase>');

		if (count($this->getItemList()) > 0) {
			foreach ($this->getItemList() as $id) {
				$item =& $this->items[$id];

				if (isset($item->title) && isset($item->link)) {
					$this->output .= (string) '<item>' . "\n";
					$this->output .= (string) '<title>' . $item->title . '</title>' . "\n";
					$this->output .= (string) '<link>' . $item->link . '</link>' . "\n";

					if (isset($item->description)) {
						$this->output .= (string) '<description>' . $item->description . '</description>' . "\n";
					} // end if

					if ($this->use_dc_data === TRUE && isset($item->subject)) {
						$this->output .= (string) '<category>' . $item->subject . '</category>' . "\n";
					} // end if

					if ($this->use_dc_data === TRUE && isset($item->date)) {
						$this->output .= (string) '<pubDate>' . $item->date . '</pubDate>' . "\n";
					} // end if

					if (isset($item->about)) {
						$this->output .= (string) '<guid>' . $item->about . '</guid>' . "\n";
					} // end if

					if (isset($item->author)) {
						$this->output .= (string) '<author>' . $item->author . '</author>' . "\n";
					} // end if

					if (isset($item->comments)) {
						$this->output .= (string) '<comments>' . $item->comments . '</comments>' . "\n";
					} // end if

					if (isset($item->image)) {
						$this->output .= (string) '<im:image>' . $item->image . '</im:image>' . "\n";
					} // end if
					$this->output .= (string) '</item>' . "\n";
				} // end if
			} // end foreach
		} // end if

		$this->output .= (string) '</channel>' . "\n";
		$this->output .= (string) '</rss>' . "\n";
	} // end function

	/**
	* creates the output
	*
	* @uses createOutputV090()
	* @uses createOutputV091()
	* @uses createOutputV200()
	* @uses createOutputV100()
	*/
	private function createOutput($version = '') {
		if (strlen(trim($version)) === 0) {
			$version = (string) '1.0';
		} // end if

		switch ($version) {
			case '0.9':
				$this->createOutputV090();
				break;
			case '0.91':
				$this->createOutputV091();
				break;
			case '2.00':
				$this->createOutputV200();
				break;
			case '1.0':
			default:
				$this->createOutputV100();
				break;
		} // end switch
	} // end function
	/**#@-*/

	/**#@+
	* @uses createOutput()
	* @return void
	* @uses RSSBuilder::$encoding
	* @uses RSSBuilder::$output
	*/
	/**
	* echos the output
	*
	* use this function if you want to directly output the rss stream
	*
	* @uses RSSBuilder::$title
	*/
	public function outputRSS($version = '') {
		if (!isset($this->output)) {
			$this->createOutput($version);
		} // end if
		header('content-type: text/xml');
		header('Content-Disposition: inline; filename=rss_' . str_replace(' ','',$this->title) . '.xml');
		$this->output = '<?xml version="1.0" encoding="' . $this->encoding . '"?>' . "\n" .
						'<!--  RSS generated by Flaimo.com RSS Builder [' .  date('Y-m-d H:i:s')  .']  --> ' . $this->output;
		echo $this->output;
	} // end function

	/**
	* returns the output
	*
	* use this function if you want to have the output stream as a string (for example to write it in a cache file)
	*/
	public function getRSSOutput($version = '') {
		if (!isset($this->output)) {
			$this->createOutput($version);
		} // end if
		return (string) '<?xml version="1.0" encoding="' . $this->encoding . '"?>' . "\n" .
						'<!--  RSS generated by Flaimo.com RSS Builder [' .  date('Y-m-d H:i:s')  .']  --> ' . $this->output;
	} // end function
	/**#@-*/
} // end class RSSBuilder

//---------------------------------------------------------------------------

/**
* single rss item object
*
* Tested with WAMP (XP-SP1/1.3.27/4.0.12/5.0.0b2-dev)
* Last change: 2003-07-07
*
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package RSSBuilder
* @category FLP
* @version 2.000
*/
class RSSItem extends RSSBase {

	/**#@+
	* @var string
	*/
	/**
	* URL
	*/
	public $about;

	/**
	* headline
	*/
	public $title;

	/**
	* URL to the full item
	*/
	public $link;

	/**
	* optional description
	*/
	public $description;

	/**
	* optional subject (category)
	*/
	public $subject;

	/**
	* optional date
	*/
	public $date;

	/**
	* author of item
	*
	* @since 1.001 - 2003-05-30
	*/
	public $author;

	/**
	* url to comments page (rss 2.0)
	*
	* @since 1.001 - 2003-05-30
	*/
	public $comments;

	/**
	* imagelink for this item (mod_im only)
	*
	* @since 1.002 - 2003-06-26
	*/
	public $image;
	/**#@-*/

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**#@+
	* @return void
	*/
	/**
	* Constructor
	*
	* @param string $about URL
	* @param string $title
	* @param string $link URL
	* @param string $description (optional)
	* @param string $subject some sort of category (optional)
	* @param string $date format: 2003-05-29T00:03:07+0200 (optional)
	* @param string $author some sort of category author of item
	* @param string $comments url to comment page rss 2.0 value
	* @param string $image optional mod_im value for dispaying a different pic for every item
	* @uses setString()
	*/
	function __construct($about = '', $title = '', $link = '', $description = '',
						 $subject = '',	$date = '',	$author = '', $comments = '',
						 $image = '') {
		$this->setString('about', $about);
		$this->setString('title', $title);
		$this->setString('link', $link);
		$this->setString('description', $description);
		$this->setString('subject', $subject);
		$this->setString('date', $date);
		$this->setString('author', $author);
		$this->setString('comments', $comments);
		$this->setString('image', $image);
	} // end constructor

	/**
	* assigns a value to a class variable
	*
	* @param string $var class variable
	* @param string $value
	*/
    function setString($var, $value = '') {
    	if (!isset($this->$var) && strlen(trim($value)) > 0) {
    		$this->$var = (string) $value;
    	} // end if
    } // end function
	/**#@-*/
} // end class RSSItem
?>
<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)                                    |
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
* @package vCard
*/
/**
* Create a vCard file for download
*
* <code>
* $vCard = new vCard($lang,$download_dir);
* $vCard->setLastName('Mustermann');
* …
* $vCard->outputFile('vcf');
* </code>
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.4/4.3.0)
* Last change: 2002-12-27
*
* @desc Create a vCard file for download
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package vCard
* @example sample_vcard.php Sample script
* @version 1.002
*/
class VCard {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access private
	* @var string
	*/
	/**
	* Output string to be written in the vCard file
	*
	* @desc Output string to be written in the vCard file
	*/
	var $output;

	/**
	* Format of the output (vcf)
	*
	* @desc Format of the output (vcf)
	*/
	var $output_format;

	var $first_name;
	var $middle_name;
	var $last_name;
	var $edu_title;
	var $addon;
	var $nickname;
	var $company;
	var $organisation;
	var $department;
	var $job_title;
	var $note;
	var $tel_work1_voice;
	var $tel_work2_voice;
	var $tel_home1_voice;
	var $tel_home2_voice;
	var $tel_cell_voice;
	var $tel_car_voice;
	var $tel_pager_voice;
	var $tel_additional;
	var $tel_work_fax;
	var $tel_home_fax;
	var $tel_isdn;
	var $tel_preferred;
	var $tel_telex;
	var $work_street;
	var $work_zip;
	var $work_city;
	var $work_region;
	var $work_country;
	var $home_street;
	var $home_zip;
	var $home_city;
	var $home_region;
	var $home_country;
	var $postal_street;
	var $postal_zip;
	var $postal_city;
	var $postal_region;
	var $postal_country;
	var $url_work;
	var $role;
	var $birthday;
	var $email;
	var $rev;
	var $lang;
	/**#@-*/

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**#@+
	* @access private
	* @since 1.000 - 2002-/10-20
	*/
	/**
	* Constructor
	*
	* Only job is to set all the variablesnames
	*
	* @desc Constructor
	* @param string $downloaddir
	* @param string $lang
	* @return void
	*/
	function VCard($downloaddir = '', $lang = '') {
		$this->download_dir   = (string) ((strlen(trim($downloaddir)) > 0) ? $downloaddir : 'vcarddownload');
		$this->card_filename  = (string) time() . '.vcf';
		$this->rev            = (string) date('Ymd\THi00\Z',time());
		$this->setLanguage($lang);

        if ($this->checkDownloadDir() == FALSE) {
			die('error creating download directory');
		} // end if
	} // end function


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Checks if the download directory exists, else trys to create it
	*
	* @desc Checks if the download directory exists, else trys to create it
	* @return boolean
	*/
	function checkDownloadDir() {
		if (!is_dir($this->download_dir)) {
			return (boolean) ((!mkdir($this->download_dir, 0700)) ? FALSE : TRUE);
		} else {
			return (boolean) TRUE;
		} // end if
	} // end function

	/**
	* Set Language (iso code) for the Strings in the vCard file
	*
	* @desc
	* @param string $isocode
	* @return void
	* @uses isValidLanguageCode()
	*/
	function setLanguage($isocode = '') {
		$this->lang = (string) (($this->isValidLanguageCode($isocode) == TRUE) ? ';LANGUAGE=' . $isocode : '');
	} // end function

	/**
	* Encodes a string for QUOTE-PRINTABLE
	*
	* @desc Encodes a string for QUOTE-PRINTABLE
	* @param string $quotprint  String to be encoded
	* @return string Encodes string
	* @author Harald Huemer <harald.huemer@liwest.at>
	*/
	function quotedPrintableEncode($quotprint) {
		/*
		//beim Mac Umlaute nicht kodieren !!!! sonst Fehler beim Import
		if ($progid == 3)
		  {
		  $quotprintenc = preg_replace("~([\x01-\x1F\x3D\x7F-\xBF])~e", "sprintf('=%02X', ord('\\1'))", $quotprint);
		  return($quotprintenc);
		  }
		//bei Windows und Linux alle Sonderzeichen kodieren
		else
		  {*/
		$quotprint = (string) str_replace('\r\n',chr(13) . chr(10),$quotprint);
		$quotprint = (string) str_replace('\n',chr(13) . chr(10),$quotprint);
		$quotprint = (string) preg_replace("~([\x01-\x1F\x3D\x7F-\xFF])~e", "sprintf('=%02X', ord('\\1'))", $quotprint);
		$quotprint = (string) str_replace('\=0D=0A','=0D=0A',$quotprint);
		return (string) $quotprint;
	} // end function

	/**
	* Checks if a given string is a valid iso-language-code
	*
	* @desc Checks if a given string is a valid iso-language-code
	* @param string $code  String that should validated
	* @return boolean $isvalid  If string is valid or not
	*/
	function isValidLanguageCode($code) { // PHP5: protected
		return (boolean) ((preg_match('(^([a-zA-Z]{2})((_|-)[a-zA-Z]{2})?$)',trim($code)) > 0) ? TRUE : FALSE);
	} // end function
	/**#@-*/

	/**#@+
	* @param string $input
	* @return void
	* @access public
	* @since 1.000 - 2002-10-20
	*/
	/**
	* Set the persons first name
	*
	* @desc Set the persons first name
	*/
	function setFirstName($input) {
		$this->first_name = (string) $input;
	} // end function

	/**
	* Set the persons middle name(s)
	*
	* @desc Set the persons middle name(s)
	*/
	function setMiddleName($input) {
		$this->middle_name = (string) $input;
	} // end function

	/**
	* Set the persons last name
	*
	* @desc Set the persons last name
	*/
	function setLastName($input) {
		$this->last_name = (string) $input;
	} // end function

	/**
	* Set the persons title (Doctor,…)
	*
	* @desc Set the persons title (Doctor,…)
	*/
	function setEducationTitle($input) {
		$this->edu_title = (string) $input;
	} // end function

	/**
	* Set the persons addon (jun., sen.,…)
	*
	* @desc Set the persons addon (jun., sen.,…)
	*/
	function setAddon($input) {
		$this->addon = (string) $input;
	} // end function

	/**
	* Set the persons nickname
	*
	* @desc Set the persons nickname
	*/
	function setNickname($input) {
		$this->nickname = (string) $input;
	} // end function

	/**
	* Set the company name for which the person works for
	*
	* @desc Set the company name for which the person works for
	*/
	function setCompany($input) {
		$this->company = (string) $input;
	} // end function

	/**
	* Set the organisations name for which the person works for
	*
	* @desc Set the organisations name for which the person works for
	*/
	function setOrganisation($input) {
		$this->organisation = (string) $input;
	} // end function

	/**
	* Set the department name of company for which the person works for
	*
	* @desc Set the department name of company for which the person works for
	*/
	function setDepartment($input) {
		$this->department = (string) $input;
	} // end function

	/**
	* Set the persons job title
	*
	* @desc Set the persons job title
	*/
	function setJobTitle($input) {
		$this->job_title = (string) $input;
	} // end function

	/**
	* Set additional notes for that person
	*
	* @desc Set additional notes for that person
	*/
	function setNote($input) {
		$this->note = (string) $input;
	} // end function

	/**
	* Set telephone number (Work 1)
	*
	* @desc Set telephone number (Work 1)
	*/
	function setTelephoneWork1($input) {
		$this->tel_work1_voice = (string) $input;
	} // end function

	/**
	* Set telephone number (Work 2)
	*
	* @desc Set telephone number (Work 2)
	*/
	function setTelephoneWork2($input) {
		$this->tel_work2_voice = (string) $input;
	} // end function

	/**
	* Set telephone number (Home 1)
	*
	* @desc Set telephone number (Home 1)
	*/
	function setTelephoneHome1($input) {
		$this->tel_home1_voice = (string) $input;
	} // end function

	/**
	* Set telephone number (Home 2)
	*
	* @desc Set telephone number (Home 2)
	*/
	function setTelephoneHome2($input) {
		$this->tel_home2_voice = (string) $input;
	} // end function

	/**
	* Set cellphone number
	*
	* @desc Set cellphone number
	*/
	function setCellphone($input) {
		$this->tel_cell_voice = (string) $input;
	} // end function


	/**
	* Set carphone number
	*
	* @desc Set carphone number
	*/
	function setCarphone($input) {
		$this->tel_car_voice = (string) $input;
	} // end function

	/**
	* Set pager number
	*
	* @desc Set pager number
	*/
	function setPager($input) {
		$this->tel_pager_voice = (string) $input;
	} // end function

	/**
	* Set additional phone number
	*
	* @desc Set additional phone number
	*/
	function setAdditionalTelephone($input) {
		$this->tel_additional = (string) $input;
	} // end function

	/**
	* Set fax number (Work)
	*
	* @desc Set fax number (Work)
	*/
	function setFaxWork($input) {
		$this->tel_work_fax = (string) $input;
	} // end function

	/**
	* Set fax number (Home)
	*
	* @desc Set fax number (Home)
	*/
	function setFaxHome($input) {
		$this->tel_work_home = (string) $input;
	} // end function


	/**
	* Set ISDN (phone) number
	*
	* @desc Set ISDN (phone) number
	*/
	function setISDN($input) {
		$this->tel_isdn = (string) $input;
	} // end function

	/**
	* Set preferred phone number
	*
	* @desc Set preferred phone number
	*/
	function setPreferredTelephone($input) {
		$this->tel_preferred = (string) $input;
	} // end function

	/**
	* Set telex number
	*
	* @desc Set telex number
	*/
	function setTelex($input) {
		$this->tel_telex = (string) $input;
	} // end function


	/**
	* Set streetname (Work Address)
	*
	* @desc Set streetname (Work Address)
	*/
	function setWorkStreet($input) {
		$this->work_street = (string) $input;
	} // end function

	/**
	* Set ZIP code (Work Address)
	*
	* @desc Set ZIP code (Work Address)
	*/
	function setWorkZIP($input) {
		$this->work_zip = (string) $input;
	} // end function

	/**
	* Set city (Work Address)
	*
	* @desc Set city (Work Address)
	*/
	function setWorkCity($input) {
		$this->work_city = (string) $input;
	} // end function

	/**
	* Set region (Work Address)
	*
	* @desc Set region (Work Address)
	*/
	function setWorkRegion($input) {
		$this->work_region = (string) $input;
	} // end function

	/**
	* Set country (Work Address)
	*
	* @desc Set country (Work Address)
	*/
	function setWorkCountry($input) {
		$this->work_country = (string) $input;
	} // end function


	/**
	* Set streetname (Home Address)
	*
	* @desc Set streetname (Home Address)
	*/
	function setHomeStreet($input) {
		$this->home_street = (string) $input;
	} // end function

	/**
	* Set ZIP code (Home Address)
	*
	* @desc Set ZIP code (Home Address)
	*/
	function setHomeZIP($input) {
		$this->home_zip = (string) $input;
	} // end function

	/**
	* Set city (Home Address)
	*
	* @desc Set city (Home Address)
	*/
	function setHomeCity($input) {
		$this->home_city = (string) $input;
	} // end function

	/**
	* Set region (Home Address)
	*
	* @desc Set region (Home Address)
	*/
	function setHomeRegion($input) {
		$this->home_region = (string) $input;
	} // end function

	/**
	* Set country (Home Address)
	*
	* @desc Set country (Home Address)
	*/
	function setHomeCountry($input) {
		$this->home_country = (string) $input;
	} // end function


	/**
	* Set streetname (Postal Address)
	*
	* @desc Set streetname (Postal Address)
	*/
	function setPostalStreet($input) {
		$this->postal_street = (string) $input;
	} // end function

	/**
	* Set ZIP code (Postal Address)
	*
	* @desc Set ZIP code (Postal Address)
	*/
	function setPostalZIP($input) {
		$this->postal_zip = (string) $input;
	} // end function

	/**
	* Set city (Postal Address)
	*
	* @desc Set city (Postal Address)
	*/
	function setPostalCity($input) {
		$this->postal_city = (string) $input;
	} // end function

	/**
	* Set region (Postal Address)
	*
	* @desc Set region (Postal Address)
	*/
	function setPostalRegion($input) {
		$this->postal_region = (string) $input;
	} // end function

	/**
	* Set country (Postal Address)
	*
	* @desc Set country (Postal Address)
	*/
	function setPostalCountry($input) {
		$this->postal_country = (string) $input;
	} // end function


	/**
	* Set URL (Work)
	*
	* @desc Set URL (Work)
	*/
	function setURLWork($input) {
		$this->url_work = (string) $input;
	} // end function

	/**
	* Set role (Student,…)
	*
	* @desc Set role (Student,…)
	*/
	function setRole($input) {
		$this->role = (string) $input;
	} // end function


	/**
	* Set birthday
	*
	* @desc Set birthday
	*/
	function setBirthday($timestamp) {
		$this->birthday = (int) date('Ymd',$timestamp);
	} // end function


	/**
	* Set eMail address
	*
	* @desc Set eMail address
	*/
	function setEMail($input) {
		$this->email = (string) $input;
	} // end function
	/**#@-*/

	/**#@+
	* @access public
	* @since 1.000 - 2002-10-20
	*/
	/**
	* Generates the string to be written in the file later on
	*
	* @desc Generates the string to be written in the file later on
	* @param string $format  vcf
	* @return void
	* @see getCardOutput()
	* @see writeCardFile()
	* @uses quotedPrintableEncode()
	*/
	function generateCardOutput($format) {
		$this->output_format = (string) $format;
		if ($this->output_format == 'vcf') {
			$this->output  = (string) "BEGIN:VCARD\r\n";
			$this->output .= (string) "VERSION:2.1\r\n";
			$this->output .= (string) "N;ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($this->last_name . ";" . $this->first_name . ";" . $this->middle_name . ";" . $this->addon) . "\r\n";
			$this->output .= (string) "FN;ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($this->first_name . " " . $this->middle_name . " " . $this->last_name . " " . $this->addon) . "\r\n";
			if (strlen(trim($this->nickname)) > 0) {
				$this->output .= (string) "NICKNAME;ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($this->nickname) . "\r\n";
			} // end if
			$this->output .= (string) "ORG" . $this->lang . ";ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($this->organisation) . ";" . $this->quotedPrintableEncode($this->department) . "\r\n";
			if (strlen(trim($this->job_title)) > 0) {
				$this->output .= (string) "TITLE" . $this->lang . ";ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($this->job_title) . "\r\n";
			} // end if
			if (strlen(trim($this->note)) > 0) {
				$this->output .= (string) "NOTE" . $this->lang . ";ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($this->note) . "\r\n";
			} // end if
			if (strlen(trim($this->tel_work1_voice)) > 0) {
				$this->output .= (string) "TEL;WORK;VOICE:" . $this->tel_work1_voice . "\r\n";
			} // end if
			if (strlen(trim($this->tel_work2_voice)) > 0) {
				$this->output .= (string) "TEL;WORK;VOICE:" . $this->tel_work1_voice . "\r\n";
			} // end if
			if (strlen(trim($this->tel_home1_voice)) > 0) {
				$this->output .= (string) "TEL;HOME;VOICE:" . $this->tel_home1_voice . "\r\n";
			} // end if
			if (strlen(trim($this->tel_cell_voice)) > 0) {
				$this->output .= (string) "TEL;CELL;VOICE:" . $this->tel_cell_voice . "\r\n";
			} // end if
			if (strlen(trim($this->tel_car_voice)) > 0) {
				$this->output .= (string) "TEL;CAR;VOICE:" . $this->tel_car_voice . "\r\n";
			} // end if
			if (strlen(trim($this->tel_additional)) > 0) {
				$this->output .= (string) "TEL;VOICE:" . $this->tel_additional . "\r\n";
			} // end if
			if (strlen(trim($this->tel_pager_voice)) > 0) {
				$this->output .= (string) "TEL;PAGER;VOICE:" . $this->tel_pager_voice . "\r\n";
			} // end if
			if (strlen(trim($this->tel_work_fax)) > 0) {
				$this->output .= (string) "TEL;WORK;FAX:" . $this->tel_work_fax . "\r\n";
			} // end if
			if (strlen(trim($this->tel_home_fax)) > 0) {
				$this->output .= (string) "TEL;HOME;FAX:" . $this->tel_home_fax . "\r\n";
			} // end if
			if (strlen(trim($this->tel_home2_voice)) > 0) {
				$this->output .= (string) "TEL;HOME:" . $this->tel_home2_voice . "\r\n";
			} // end if
			if (strlen(trim($this->tel_isdn)) > 0) {
				$this->output .= (string) "TEL;ISDN:" . $this->tel_isdn . "\r\n";
			} // end if
			if (strlen(trim($this->tel_preferred)) > 0) {
				$this->output .= (string) "TEL;PREF:" . $this->tel_preferred . "\r\n";
			} // end if
			$this->output .= (string) "ADR;WORK:;" . $this->company . ";" . $this->work_street . ";" . $this->work_city . ";" . $this->work_region . ";" . $this->work_zip . ";" . $this->work_country . "\r\n";
			$this->output .= (string) "LABEL;WORK;ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($this->company) . "=0D=0A" . $this->quotedPrintableEncode($this->work_street) . "=0D=0A" . $this->quotedPrintableEncode($this->work_city) . ", " . $this->quotedPrintableEncode($this->work_region) . " " . $this->quotedPrintableEncode($this->work_zip) . "=0D=0A" . $this->quotedPrintableEncode($this->work_country) . "\r\n";
			$this->output .= (string) "ADR;HOME:;" . $this->home_street . ";" . $this->home_city . ";" . $this->home_region . ";" . $this->home_zip . ";" . $this->home_country . "\r\n";
			$this->output .= (string) "LABEL;HOME;ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($this->home_street) . "=0D=0A" . $this->quotedPrintableEncode($this->home_city) . ", " . $this->quotedPrintableEncode($this->home_region) . " " . $this->quotedPrintableEncode($this->home_zip) . "=0D=0A" . $this->quotedPrintableEncode($this->home_country) . "\r\n";
			$this->output .= (string) "ADR;POSTAL:;" . $this->postal_street . ";" . $this->postal_city . ";" . $this->postal_region . ";" . $this->postal_zip . ";" . $this->postal_country . "\r\n";
			$this->output .= (string) "LABEL;POSTAL;ENCODING=QUOTED-PRINTABLE:" . $this->quotedPrintableEncode($this->postal_street) . "=0D=0A" . $this->quotedPrintableEncode($this->postal_city) . ", " . $this->quotedPrintableEncode($this->postal_region) . " " . $this->quotedPrintableEncode($this->postal_zip) . "=0D=0A" . $this->quotedPrintableEncode($this->postal_country) . "\r\n";
			if (strlen(trim($this->url_work)) > 0) {
				$this->output .= (string) "URL;WORK:" . $this->url_work . "\r\n";
			} // end if
			if (strlen(trim($this->role)) > 0) {
				$this->output .= (string) "ROLE" . $this->lang . ":" . $this->role . "\r\n";
			} // end if
			if (strlen(trim($this->birthday)) > 0) {
				$this->output .= (string) "BDAY:" . $this->birthday . "\r\n";
			} // end if
			if (strlen(trim($this->email)) > 0) {
				$this->output .= (string) "EMAIL;PREF;INTERNET:" . $this->email . "\r\n";
			} // end if
			if (strlen(trim($this->tel_telex)) > 0) {
				$this->output .= (string) "EMAIL;TLX:" . $this->tel_telex . "\r\n";
			} // end if
			$this->output .= (string) "REV:" . $this->rev . "\r\n";
			$this->output .= (string) "END:VCARD\r\n";
		} // end if output_format == 'vcf'
	} // end function

	/**
	* Loads the string into the variable if it hasn’t been set before
	*
	* @desc Loads the string into the variable if it hasn’t been set before
	* @param string $format only vcf so far
	* @return string $output
	* @uses generateCardOutput()
	* @see writeCardFile()
	*/
	function getCardOutput($format) {
		if (!isset($this->output) || $this->output_format != $format) {
			$this->generateCardOutput($format);
		} // end if
		return (string) $this->output;
	} // end function

	/**
	* Writes the string into the file and saves it to the download directory
	*
	* @desc Writes the string into the file and saves it to the download directory
	* @return void
	* @see generateCardOutput()
	* @see getCardOutput()
	* @uses deleteOldFiles()
	*/
	function writeCardFile() {
		if (!isset($this->output)) {
			$this->generateCardOutput();
		} // end if
		$handle = fopen($this->download_dir . '/' . $this->card_filename, 'w');
		fputs($handle, $this->output);
		fclose($handle);
		$this->deleteOldFiles(30);
		if (isset($handle)) {
			unset($handle);
		}
	} // end function


	/**
	* Sends the right header information and outputs the generated content to
	* the browser
	*
	* @desc Sends the right header information
	* @param string $format only vcf so far
	* @return void
	* @uses getCardOutput()
	* @since 1.02 - 2002-12-23
	*/
	function outputFile($format = 'vcf') {
		if ($format == 'vcf') {
			header('Content-Type: text/x-vcard');
			header('Content-Disposition: attachment; filename=vCard_' . date('Y-m-d_H-m-s') . '.vcf');
			echo $this->getCardOutput('vcf');
		} // end if
	} // end function

	/**
	* Returns the full path to the saved file where it can be downloaded.
	*
	* Can be used for “header(Location:…”
	*
	* @desc Returns the full path to the saved file where it can be downloaded.
	* @return string  Full http path
	*/
	function getCardFilePath() {
		$path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
		$port = (string) (($_SERVER['SERVER_PORT'] != 80) ? ':' . $_SERVER['SERVER_PORT'] : '' );
		return (string) 'http://' . $_SERVER['SERVER_NAME'] . $port . $path_parts["dirname"] . '/' . $this->download_dir . '/' . $this->card_filename;
	} // end function
	/**#@-*/

	/**
	* Writes the string into the file and saves it to the download directory
	*
	* @desc Writes the string into the file and saves it to the download directory
	* @param (int) $time Minimum age of the files (in seconds) before files get deleted
	* @return void
	* @see writeCardFile()
	* @access private
	* @since 1.000 - 2002-10-20
	*/
	function deleteOldFiles($time = 300) {
		if (!is_int($time) || $time < 1) {
			$time = (int) 300;
		} // end if
		$handle = opendir($this->download_dir);
		while ($file = readdir($handle)) {
			if (!eregi("^\.{1,2}$",$file) && !is_dir($this->download_dir . '/' . $file) && eregi("\.vcf",$file) && ((time() - filemtime($this->download_dir . '/' . $file)) > $time)) {
				unlink($this->download_dir . '/' . $file);
			} // end if
		} // end while
		closedir($handle);
		if (isset($handle)) {
			unset($handle);
		}
		if (isset($file)) {
			unset($file);
		}
	} // end function
} // end class vCard
?>
<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.27/4.0.12/4.3.2)                                    |
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
* @package i18n
* @category FLP
*/
/**
* Including the Language class
*/
@require_once 'class.Translator.inc.php';
/**
* Including the User class
*/
@require_once 'class.I18NUser.inc.php';
/**
* Including the FormatNumber class
*/
@require_once 'class.FormatNumber.inc.php';
/**
* Including the abstract base class
*/
@require_once 'class.I18N.inc.php';
/**
* Converting and formating of measure units
*
* Converts measure units between Système International d’Unités (SI)
* and U.S. Customary System (USCS). Possible Units are “Linear”,
* “Surface”, “Capacity”, “Cooking”, “Liquid” and “Temperature”.
* Start with creating a new Object:
* <code>
* $ms = (object) new Measure('si','uscs').
* </code>
* First parameter is the input format for all functions. The secound
* one the output format. If the secound one is missing, the class
* tries to determine the measure system by the Language Object.
* All functions have the same structure:
* $ms->function($input, $input_format, $output_format). The fist
* parameter is the value which you would like to convert. The other
* two define what the measure unit (for the input value) is and what
* unit should be taken to format the output number. The last two
* parameter are integer values. Please scroll to the specific funtion
* to see the int value für the unit you need. For example
* $ms->->surface(16,6,1) would convert (16) square kilometers to
* (172222566) square feet. Only Exception is the Temperature function
* which only needs one value, since it's only celsius and fahrenheit.
* Further there is a function Unit, which you can place in your script
* AFTER you’ve used one of the other functions above to output the unit
* name. $ms->unit(0) outputs nothing, $ms->unit(1) outputs the short
* unit name, $ms->unit(2) the long version.
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-04
*
* @desc Converting and formating of measure units
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @category FLP
* @version 1.060
*/
class Measure extends I18N {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access private
	*/
	/**
	* Array with the possible measuring systems
	*
	* @desc Array with the possible measuring systems
	* @var array
	*/
	var $systems = array('si'   => 'Système International',
						 'uscs' => 'US Customary System');

	/**
	* $system code for the function $input values
	*
	* @desc $system code for the function $input values
	* @var string
	*/
	var $input = 'si';

	/**
	* $system code for the functions output format
	*
	* @desc $system code for the functions output format
	* @var string
	*/
	var $output;

	/**
	* $holds the unit code for a converted value
	*
	* @desc $holds the unit code for a converted value
	* @var string
	*/
	var $display_format;

	/**
	* Holds the language object
	*
	* @desc Holds the language object
	* @var object
	*/
	var $lg;

	/**
	* Holds the user object
	*
	* @desc Holds the user object
	* @var object
	*/
	var $user;

	/**
	* Holds the FormatNumber object
	*
	* @desc Holds the FormatNumber object
	* @var object
	*/
	var $fn;

	/**
	* l10n measure system
	*
	* @desc l10n measure system
	* @var string
	*/
	var $measure_system = 'si';
	/**#@-*/

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**#@+
	* @access private
	* @return void
	*/
	/**
	* Constructor
	*
	* Sets the array with the possible measuring systems and sets
	* the input and output system.
	*
	* @desc Constructor
	* @param string $input  Measuring system for the input values
	* @param string $output  Measuring system for the returned output values
	* @uses I18N::I18N()
	* @uses setInput()
	* @uses setOutput()
	*/
	function Measure($input = '', $output = '') {
		parent::I18N();
		$this->setInput($input);
		$this->setOutput($output);
	} // end function


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Reads the default settings for numbers and dates from the settings file if necessary
	*
	* @desc Reads the default settings for numbers and dates from the settings file if necessary
	* @uses i18n::readL10NINIsettings()
	* @uses Translator::getLanguageFilePath()
	* @uses Translator::getLocale()
	* @since 1.055 - 2003-04-22
	*/
	function readDefaultL10NSettings() {
        $this->loadLanguageClass();
        parent::readL10NINIsettings($this->lg->getLocale());
        if (isset($GLOBALS[$this->l10n_globalname])) {
            $this->measure_system	=& $GLOBALS[$this->l10n_globalname]['Measure']['measure_system'];
    	} // end if
	} // end function

	/**
	* Sets the input variable
	*
	* @desc Sets the input variable
	* @param string $input
	* @see $input
	* @uses isValidMeasureSystem()
	*/
	function setInput($input) {
		if (strlen(trim($input)) === 0) {
			parent::readINIsettings();
        	if (isset($GLOBALS[$this->i18n_globalname])) {
				$this->input =& $GLOBALS[$this->i18n_globalname]['Measure']['default_input_system'];
			} // end if
		} elseif ($this->isValidMeasureSystem($input) === TRUE) {
			$this->input = (string) $input;
		} else {
			die('Measure Class: Wrong $input -> ' . $input);
			exit;
		} // end if
	} // end function

	/**
	* Sets the output variable
	*
	* @desc Sets the output variable
	* @param string $output
	* @see $output
	* @uses loadUserClass()
	* @uses isValidMeasureSystem()
	* @uses i18nUser::getPreferedTimezone()
	* @uses loadLanguageClass()
	* @uses Language::getLang()
	*/
	function setOutput($output) {
		$this->loadUserClass();
		if ($this->isValidMeasureSystem($this->user->getPreferedMeasureSystem()) === TRUE) {
			$this->output = (string) $this->user->getPreferedMeasureSystem();
		} elseif ($this->isValidMeasureSystem($output) === TRUE) {
			$this->output = (string) $output;
		} else {
			$this->readDefaultL10NSettings();
			$this->output =& $this->measure_system;
		} // end if
	} // end function
	/**#@-*/

	/**
	* Checks if a given system is a valid measuring system
	*
	* @desc Checks if a given system is a valid measuring system
	* @param string $system
	* @return boolean $return
	* @access private
	* @see $systems
	*/
	function isValidMeasureSystem($system) {
		return (boolean) ((array_key_exists($system, $this->systems)) ? TRUE : FALSE);
	} // end function

	/**
	* Returns the name of the output-measure system
	*
	* @desc Returns the name of the output-measure system
	* @return string $systems
	* @access public
	* @see getInput()
	* @since 1.010 - 2003-01-06
	*/
	function &getOutput() {
		return (string) $this->systems[$this->output];
	} // end function

	/**
	* Returns the name of the input-measure system
	*
	* @desc Returns the name of the input-measure system
	* @return string $systems
	* @access public
	* @see getOutput()
	* @since 1.010 - 2003-01-06
	*/
	function &getInput() {
		return (string) $this->systems[$this->input];
	} // end function

	/**
	* Returns the array with available measure systems
	*
	* @desc Returns the array with available measure systems
	* @return string $systems
	* @access public
	* @since 1.043 - 2003-02-13
	*/
	function &getSystems() {
		return (array) $this->systems;
	} // end function

	/**
	* Validates if a given measure unit is within the right (integer) range
	*
	* @desc Validates if a given measure unit is within the right (integer) range
	* @param int $choises
	* @param int $format
	* @return int $format
	* @access private
	*/
	function validunit($choises, $format) {
		$choises = (int) $choises;
		if (!preg_match('(^[0-' . $choises . ']$)',$format)) {
			$format = (int) 0;
		} // end if
		return (int) $format;
	} // end function


	/**#@+
	* @access public
	* @param float $input
	* @param int $format
	* @uses validunit()
	* @return float
	*/
	/**
	* Converts a linear value to mm|in
	*
	* (input/output)$format:
	* 0: mm|in,
	* 1: cm|ft,
	* 2: dm|yd,
	* 3: m|fur,
	* 4: km|mi,
	*
	* @desc Converts a linear value to mm|in
	* @see linear()
	*/
	function linearDownsize($input = 0, $format = 0) {
		$input 	= (float) $input;
		$format = (int) $this->validunit(4, $format);

		if ($this->input === 'si') {
			$div = (array) array(1, 10, 100, 1000, 1000000);
		} elseif ($this->input === 'uscs') {
			$div = (array) array(1, 12, 36, 7920, 63360);
		} // end if
		return (float) $input*$div[$format];
	} // end function

	/**
	* Converts a surface value to mm²|sq in
	*
	* (input)$format:
	* 0: mm²|sq in,
	* 1: cm²|sq ft,
	* 2: dm²|sq yd,
	* 3: m²|sq rd,
	* 4: a|acre,
	* 5: ha|sq mi,
	* 6: km²|sq mi
	*
	* @desc Converts a surface value to mm²|sq in
	* @see surface()
	*/
	function surfaceDownsize($input = 0, $format = 0) {
		$input 	= (float) $input;
		$format = (int) $this->validunit(6, $format);
		if ($this->input === 'si') {
			$div = (array) array(1, 100, 10000, 1000000, 100000000, 10000000000, 1000000000000);
		} elseif ($this->input === 'uscs') {
			$div = (array) array(1, 12, 36, 7920, 63360, 63360, 63360);
		} // end if
		return (float) $input*$div[$format];
	} // end function

	/**
	* Converts a capacity value to mm³|cu in
	*
	* (input)$format:
	* 0: mm³|cu in,
	* 1: cm³|cu ft,
	* 2: dm³|cu yd,
	* 3: m³|acre fd,
	* 4: km³|cu mi
	*
	* @desc Converts a capacity value to mm³|cu in
	* @see capacity()
	*/
	function capacityDownsize($input = 0, $format = 0) {
		$input 	= (float) $input;
		$format = (int) $this->validunit(4, $format);
		if ($this->input === 'si') {
			$div = (array) array(1, 1000, 1000000, 1000000000, 1000000000000);
		} elseif ($this->input === 'uscs') {
			$div = (array) array(1, 1728, 46656, 75271680, 254358061056000);
		} // end if
		return (float) $input*$div[$format];
	} // end function

	/**
	* Converts a cooking value to teaspoon (is)|teaspoon (uscs)
	*
	* (input)$format:
	* 0: teaspoon (is)|teaspoon (uscs),
	* 1: tablespoon (is)|tablespoon (uscs),
	* 2: tablespoon (is)|cup
	*
	* @desc Converts a cooking value to teaspoon (is)|teaspoon (uscs)
	* @see cooking()
	*/
	function cookingDownsize($input = 0, $format = 0) {
		$input 	= (float) $input;
		$format = (int) $this->validunit(2, $format);
		if ($this->input === 'si') {
			$div = (array) array(1, 3, 3);
		} elseif ($this->input === 'uscs') {
			$div = (array) array(1, 3, 48);
		} // end if
		return (float) $input*$div[$format];
	} // end function

	/**
	* Converts a weight value to mg|grain (no british weights!)
	*
	* (input)$format:
	* 0: mg|grain,
	* 1: cg|dr,
	* 2: dg|oz,
	* 3: g|lb,
	* 4: dag|stone,
	* 5: kg|cwt,
	* 6: ton_is|ton_us
	*
	* @desc Converts a weight value to mg|grain (no british weights!)
	* @see weight()
	*/
	function weightDownsize($input = 0, $format = 0) {
		$input 	= (float) $input;
		$format = (int) $this->validunit(6, $format);
		if ($this->input === 'si') {
			$div = (array) array(1, 10, 100, 1000, 10000, 1000000, 1000000000);
		} elseif ($this->input === 'uscs') {
			$div = (array) array(1, 27.34375, 437.5, 7000, 98000, 700000, 14000000);
		} // end if
		return (float) $input*$div[$format];
	} // end function

	/**
	* Converts a liquid value to ml|min
	*
	* (input)$format:
	* 0: ml|min,
	* 1: cl|fldr,
	* 2: dl|floz,
	* 3: l|gi,
	* 4: dal|pt,
	* 5: hl|qt,
	* 6: hl|gal,
	* 7: hl|barrel
	*
	* @desc Converts a liquid value to ml|min
	* @see liquid()
	*/
	function liquidDownsize($input = 0, $format = 0) {
		$input 	= (float) $input;
		$format = (int) $this->validunit(7, $format);
		if ($this->input === 'si') {
			$div = (array) array(1, 10, 100, 1000, 10000, 100000, 100000, 100000);
		} elseif ($this->input === 'uscs') {
			$div = (array) array(1, 7680, 61440, 245760, 983040, 1966080, 7864320, 330301440);
		} // end if
		return (float) $input*$div[$format];
	} // end function
	/**#@-*/

	/**#@+
	* @access public
	* @param float $input  float value
	* @param int $input_format  Integer value (see description)
	* @param int $output_format  Integer value (see description)
	* @uses validunit()
	* @return float $output
	*/
	/**
	* Converts a Linear value from the input system to the output system.
	*
	* (input/output)$format:
	* 0: mm|in,
	* 1: cm|ft,
	* 2: dm|yd,
	* 3: m|fur,
	* 4: km|mi,
	*
	* @desc Converts a Linear value from the input system to the output system.
	* @uses linearDownsize()
	*/
	function linear($input = 0, $input_format = 0, $output_format = 0) {
		$input 		= (float) $this->linearDownsize($input, $input_format);
		$format		= (int) $this->validunit(4, $output_format);

		if ($this->input === 'si' && $this->output === 'si') {
			$formats 	= (array) array('mm', 'cm', 'dm', 'm', 'km');
			$div 		= (array) array(1, 10, 100, 1000, 1000000);
			$output 	= (float) $input/$div[$format];
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$formats 	= (array) array('in', 'ft', 'yd', 'fur', 'mi');
			$div 		= (array) array(25.4, 304.8, 914.4, 201168, 1609344);
			$output 	= (float) $input/$div[$format];
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$formats 	= (array) array('mm', 'cm', 'dm', 'm', 'km');
			$div 		= (array) array(25.4, 2.54, 0.254, 0.0254, 0.0000254);
			$output 	= (float) $input*$div[$format];
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$formats 	= (array) array('in', 'ft', 'yd', 'fur', 'mi');
			$div 		= (array) array(1, 12, 36, 7920, 63360);
			$output 	= (float) $input/$div[$format];
		} // end uscs & uscs

		$this->display_format = (string) $formats[$format];
		return (float) $output;
	} // end function

	/**
	* Converts a Surface value from the input system to the output system.
	*
	* (input/output)$format:
	* 0: mm²|sq in,
	* 1: cm²|sq ft,
	* 2: dm²|sq yd,
	* 3: m²|sq rd,
	* 4: a|acre,
	* 5: ha|sq mi,
	* 6: km²|sq mi
	*
	* @desc Converts a Surface value from the input system to the output system.
	* @uses surfaceDownsize()
	*/
	function surface($input = 0, $input_format = 0, $output_format = 0) {
		$input 	= (float) $this->surfaceDownsize($input, $input_format);
		$format = (int) $this->validunit(6, $output_format);

		if ($this->input === 'si' && $this->output === 'si') {
			$formats 	= (array) array('mm2', 'cm2', 'dm2', 'm2', 'a', 'ha', 'km2');
			$div 		= (array) array(1, 100, 10000, 1000000, 100000000, 10000000000, 1000000000000);
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$formats 	= (array) array('sqin', 'sqft', 'sqyd', 'sqrd', 'acre', 'sqmi', 'sqmi');
			$div 		= (array) array(645.16, 92903.04, 836127.36, 25292853, 4046856400, 2589988100000, 2589988100000);
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$formats 	= (array) array('mm2', 'cm2', 'dm2', 'm2', 'a', 'ha', 'km2');
			$div 		= (array) array(0.0015500031, 0.15500031, 15.500031, 1550.0031, 155000.31, 15500031, 1550003100);
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$formats 	= (array) array('sqin', 'sqft', 'sqyd', 'sqrd', 'acre', 'sqmi', 'sqmi');
			$div 		= (array) array(1, 144, 1296, 39204, 6272640, 4014489600, 4014489600);
		} // end uscs & uscs

		$this->display_format = (string) $formats[$format];
		return (float) $input/$div[$format];
	} // end function

	/**
	* Converts a Capacity value from the input system to the output system.
	*
	* (input/output) $format:
	* 0: mm³|cu in,
	* 1: cm³|cu ft,
	* 2: dm³|cu yd,
	* 3: m³|acre fd,
	* 4: km³|cu mi
	*
	* @desc Converts a Capacity value from the input system to the output system.
	* @uses capacityDownsize()
	*/
	function capacity($input = 0, $input_format = 0, $output_format = 0) {
		$input 	= (float) $this->capacityDownsize($input, $input_format);
		$format = (int) $this->validunit(4, $output_format);

		if ($this->input === 'si' && $this->output === 'si') {
			$formats 	= (array) array('mm3', 'cm3', 'dm3', 'm3', 'km3');
			$div 		= (array) array(1, 1000, 1000000, 1000000000, 1000000000000);
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$formats 	= (array) array('cuin', 'cuft', 'cuyd', 'acrefd', 'cumi');
			$div 		= (array) array(16387.064, 28316847, 764554860, 1233481800000, 4168181825440579584);
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$formats 	= (array) array('mm3', 'cm3', 'dm3', 'm3', 'km3');
			$div 		= (array) array(0.000061023744, 0.061023744, 61.023744, 61023.744, 61023744);
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$formats 	= (array) array('cuin', 'cuft', 'cuyd', 'acrefd', 'cumi');
			$div 		= (array) array(1, 1728, 46656, 75271680, 254358061056000);
		} // end uscs & uscs

		$this->display_format = (string) $formats[$format];
		return (float) $input/$div[$format];
	} // end function

	/**
	* Converts a Cooking value from the input system to the output system.
	*
	* (input)$format:
	* 0: teaspoon (is)|teaspoon (uscs),
	* 1: tablespoon (is)|tablespoon (uscs),
	* 2: tablespoon (is)|cup
	*
	* @desc Converts a Cooking value from the input system to the output system.
	* @uses cookingDownsize()
	*/
	function cooking($input = 0, $input_format = 0, $output_format = 0) {
		$input 	= (float) $this->cookingDownsize($input, $input_format);
		$format = (int) $this->validunit(2, $format);

		if ($this->input === 'si' && $this->output === 'si') {
			$formats 	= (array) array('tsp_is', 'tbs_is', 'tbs_is');
			$div 		= (array) array(1, 3, 3);
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$formats 	= (array) array('tsp_uscs', 'tbs_uscs', 'cup');
			$div 		= (array) array(0.98578432, 2.957353, 47.317647);
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$formats 	= (array) array('tsp_is', 'tbs_is', 'tbs_is');
			$div 		= (array) array(1.0144207, 3.043262, 3.043262);
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$formats 	= (array) array('tsp_uscs', 'tbs_uscs', 'cup');
			$div 		= (array) array(1, 3, 48);
		} // end uscs & uscs

		$this->display_format = (string) $formats[$format];
		return (float) $input/$div[$format];
	} // end function

	/**
	* Converts a Weight value from the input system to the output system.
	*
	* (input)$format:
	* 0: mg|grain,
	* 1: cg|dr,
	* 2: dg|oz,
	* 3: g|lb,
	* 4: dag|stone,
	* 5: kg|cwt,
	* 6: ton_is|ton_us
	*
	* @desc Converts a Weight value from the input system to the output system.
	* @uses weightDownsize()
	*/
	function weight($input = 0, $input_format = 0, $output_format = 0) {
		$input = (float) $this->weightDownsize($input, $input_format);
		$format = (int) $this->validunit(6, $output_format);

		if ($this->input === 'si' && $this->output === 'si') {
			$formats 	= (array) array('mg', 'cg', 'dg', 'g', 'dag', 'kg', 'ton_is');
			$div 		= (array) array(1, 10, 100, 1000, 10000, 1000000, 1000000000);
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$formats 	= (array) array('grain', 'dr', 'oz', 'lb', 'stone', 'cwt_us', 'ton_us');
			$div 		= (array) array(64.79891, 1771.8452, 28349.523, 453592.37, 6350293.2, 45359237, 907184740);
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$formats 	= (array) array('mg', 'cg', 'dg', 'g', 'dag', 'kg', 'ton_is');
			$div 		= (array) array(0.015432358, 0.15432358, 1.5432358, 15.432358, 154.32358, 15432.358, 15432358);
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$formats 	= (array) array('grain', 'dr', 'oz', 'lb', 'stone', 'cwt_us', 'ton_us');
			$div 		= (array) array(1, 27.34375, 437.5, 7000, 98000, 700000, 14000000);
		} // end uscs & uscs

		$this->display_format = (string) $formats[$format];
		return (float) $input/$div[$format];
	} // end function

	/**
	* Converts a Liquid value from the input system to the output system.
	*
	* (input)$format:
	* 0: ml|min,
	* 1: cl|fldr,
	* 2: dl|floz,
	* 3: l|gi,
	* 4: dal|pt,
	* 5: hl|qt,
	* 6: hl|gal,
	* 7: hl|barrel
	*
	* @desc Converts a Liquid value from the input system to the output system.
	* @uses liquidDownsize()
	*/
	function liquid($input = 0, $input_format = 0, $output_format = 0) {
		$input = (float) $this->liquidDownsize($input, $input_format);
		$format = (int) $this->validunit(7, $output_format);

		if ($this->input === 'si' && $this->output === 'si') {
			$formats 	= (array) array('ml', 'cl', 'dl', 'l', 'dal', 'hl', 'hl', 'hl');
			$div 		= (array) array(1, 10, 100, 1000, 10000, 100000, 100000, 100000);
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$formats 	= (array) array('min', 'fldr', 'floz', 'gi', 'pt', 'qt', 'gal', 'barrel');
			$div 		= (array) array(0.00048133998891762809516053073702394, 3.6966912, 29.57353, 118.29412, 473.17647, 946.35295, 3785.4118, 158987.29);
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$formats 	= (array) array('ml', 'cl', 'dl', 'l', 'dal', 'hl', 'hl', 'hl');
			$div 		= (array) array(2077.5336, 20775.336, 207753.36, 2077533.6, 20775336, 207753360, 207753360, 207753360);
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$formats 	= (array) array('min', 'fldr', 'floz', 'gi', 'pt', 'qt', 'gal', 'barrel');
			$div 		= (array) array(1, 7680, 61440, 245760, 983040, 1966080, 7864320, 330301440);
		} // end uscs & uscs

		$this->display_format = (string) $formats[$format];
		return (float) $input/$div[$format];
	} // end function
	/**#@-*/

	/**
	* Converts a Temperature value from the input system to the output system.
	*
	* @desc Converts a Temperature value from the input system to the output system.
	* @param float $input  float value
	* @return float $output
	* @access public
	*/
	function temperature($input = 0) {
		$input = (float) $input;
		if ($this->input === 'si' && $this->output === 'si') {
			$this->display_format 	= 'C';
			$output 				= (float) $input;
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$this->display_format 	= 'F';
			$output 				= (float) ((($input/5)*9)+32);
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$this->display_format 	= 'C';
			$output 				= (float) ((($input-32)/9)*5);
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$this->display_format 	= 'F';
			$output 				= (float) $input;
		} // end uscs & uscs
		return (float) $output;
	} // end function


	/**
	* Returns the unit name for a value returned by a previous measure function.
	*
	* show_ms:
	* 0 = none
	* 1 = short unit name
	* 2 = long unit name
    * 3 = short unit name with <abbr> tag around
	*
	* @desc Returns the unit name for a value returned by a previous measure function.
	* @param int $show_ms
	* @return string ms
	* @access public
	* @uses loadLanguageClass()
	* @uses Language::getLang()
	* @uses Translator::__()
	*/
	function unit($show_ms = 3) {
		if (!preg_match('(^[0-3]$)',$show_ms)) {
			$show_ms = (int) 0;
		} // end if
		if ($show_ms === 1) {
			$this->loadLanguageClass();
			$ms = (string) $this->lg->__($this->display_format . '_short', 'lang_classMeasure');
		} elseif ($show_ms === 2) {
			$this->loadLanguageClass();
			$ms = (string) $this->lg->__($this->display_format . '_long', 'lang_classMeasure');
		} elseif ($show_ms === 3) {
			$this->loadLanguageClass();
			$ms = (string) '<abbr title="' . $this->lg->__($this->display_format . '_long', 'lang_classMeasure') . '" lang="' . $this->lg->getLang() . '" xml:lang="' . $this->lg->getLang() . '">' . $this->lg->__($this->display_format . '_short') . '</abbr>';
        } else {
			$ms = (string) '';
		} // end if
		return (string) $ms;
	} // end function

	/**
	* Changes the language of the translator object
	*
	* @desc Changes the language of the translator object
	* @param string $locale  iso-code
	* @return void
	* @access public
	* @uses loadLanguageClass()
	* @uses Translator::changeLocale()
	* @since 1.055 - 2003-04-20
	*/
	function changeLocale($locale) {
		$this->loadLanguageClass();
		$this->lg->changeLocale($locale);
	} // end function

	/**#@+
	* @access private
	*/
	/**
	* Set’s the language variable if it hasn’t been set before
	*
	* @desc Set’s the language variable if it hasn’t been set before
	* @return object $lg Language
	* @see loadUserClass()
	* @see loadFormatNumberClass()
	* @uses Translator
	*/
	function loadLanguageClass() {
		if (!isset($this->lg)) {
			$this->lg = (object) new Translator('','lang_classMeasure');
		} // end if
	} // end function

	/**
	* Set’s the user variable if it hasn’t been set before
	*
	* @desc Set’s the user variable if it hasn’t been set before
	* @return object $user  User
	* @see loadLanguageClass()
	* @see loadFormatNumberClass()
	* @uses User
	*/
	function loadUserClass() {
		if (!isset($this->user)) {
			$this->user = (object) new i18nUser();
		} // end if
	} // end function

	/**
	* Set’s the fn variable if it hasn’t been set before
	*
	* @desc Set’s the fn variable if it hasn’t been set before
	* @return object $fn  FormatNumber object
	* @see loadLanguageClass()
	* @see loadUserClass()
	* @uses FormatNumber
	* @since 1.051 - 2003-02-26
	*/
	function loadFormatNumberClass() {
		if (!isset($this->fn)) {
			$this->fn = (object) new FormatNumber('');
		} // end if
	} // end function
	/**#@-*/

	/**
	* Formats float
	*
	* @desc Formats float
	* @param float $real The number
	* @param int $digits number of digits to be displayed
	* @return string $output formated percent number
	* @access public
	* @uses loadLanguageClass()
	* @uses Language::getLang()
	* @since 1.010 - 2003-01-06
	*/
	function number($real = 0, $digits = 2) {
		$this->loadFormatNumberClass();
		return $this->fn->number($real, $digits);
	} // end function
} // end class Measure
?>

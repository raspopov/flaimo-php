<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

// funktionen auskommentieren bei umrechungsfunktionen (für mich selber :-)   )

/**
* provides methods for converting between metric and us measure systems
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3.1
*/
class I18Nmeasure extends I18Nbase {

	/**
	* @var object holds a I18Ntranslator object
	*/
	protected $translator;

	/**
	* @var string namespaces needed for translating strings in this class
	*/
	protected $namespace = 'lang_classMeasure';

	/**
	* @var array possible measuring systems
	*/
	protected $systems = array('si', 'uscs');

	/**
	* @var string $system code for the function $input values
	*/
	protected $input = 'si';

	/**
	* @var string $system code for the functions output format
	*/
	protected $output;

	/**
	* @param string $input  Measuring system for the input values
	* @param string $output  Measuring system for the returned output values
	* @param object $locale I18Nlocale
	* @return void
	* @uses I18Nbase::getI18NfactoryLocale()
	* @uses I18Ntranslator
	* @uses I18Nmeasure::$translator
	* @uses I18Nmeasure::setOutput()
	* @uses I18Nmeasure::setInput()
	*/
	public function __construct($input = FALSE, $output = FALSE, &$locale = NULL) {
		if (!($locale instanceOf I18Nlocale)) {
			$locale =& parent::getI18NfactoryLocale();
		} // end if
		$this->translator = new I18Ntranslator($this->namespace, $locale);
		$this->setInput($input);
		$this->setOutput($output);
	} // end constructor

	/**
	* Checks if a given system is a valid measuring system
	* @param string $system
	* @uses I18Nmeasure::$systems
	* @return boolean
	*/
	protected function isValidMeasureSystem($system = '') {
		return (boolean) ((in_array($system, $this->systems)) ? TRUE : FALSE);
	} // end function

	/**
	* Sets the input variable
	* @param string $input
	* @return boolean
	* @uses I18Nmeasure::isValidMeasureSystem()
	* @uses I18Nmeasure::$input
	*/
	protected function setInput($input = FALSE) {
			if ($this->isValidMeasureSystem($input) === TRUE) {
				$this->input = $input;
				return (boolean) TRUE;
		    } // end if
		    return (boolean) FALSE;
	} // end function

	/**
	* Sets the output variable
	* @param string $output
	* @return boolean
	* @uses I18Nmeasure::isValidMeasureSystem()
	* @uses I18Nmeasure::$output
	* @uses I18Nbase::getI18Nuser()
	* @uses I18Nuser::getPrefMeasureSystem()
	* @uses I18Nmeasure::$translator
	* @uses I18Nlocale::getL10Nsetting()
	*/
	protected function setOutput($output = FALSE) {
			if ($this->isValidMeasureSystem($output) === TRUE) {
				$this->output = $output;
				return (boolean) TRUE;
		    } // end if

		    if (parent::getI18Nuser()->getPrefMeasureSystem() != FALSE) {
		    	$this->output =& parent::getI18Nuser()->getPrefMeasureSystem();
		    	return (boolean) TRUE;
		    } // end if

		    $this->output =& $this->translator->getTranslatorLocale()->getL10Nsetting('measure_system');
		    return (boolean) TRUE;
	} // end function

	/**
	* Returns the name of the output-measure system
	* @return string $output
	* @see getInput()
	*/
	public function getOutput() {
		return $this->output;
	} // end function

	/**
	* Returns the name of the input-measure system
	* @return string $input
	* @see getOutput()
	*/
	public function getInput() {
		return $this->input;
	} // end function

	/**
	* Returns the array with available measure systems
	* @return array $systems
	*/
	public function getFormats() {
		return $this->systems;
	} // end function

	/**
	* Validates if a given measure unit is within the right (integer) range
	* @param int $choises
	* @param int $format
	* @return int $format
	*/
	protected static function validunit($choises, $format) {
		$choises = (int) $choises;
		if ($format < 0 || $format > $choises) {
			return (int) 0;
		} // end if
		return (int) $format;
	} // end function

	/**#@+
	* @param float $input
	* @param int $format
	* @uses I18Nmeasure::validunit()
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
	*/
	protected function linearDownsize($input = 0, $format = 0) {
		$input 	= (float) $input;
		$format = (int) $this->validunit(4, $format);

		if ($this->input === 'si') {
			$div = array(1, 10, 100, 1000, 1000000);
		} elseif ($this->input === 'uscs') {
			$div = array(1, 12, 36, 7920, 63360);
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
	*/
	protected function surfaceDownsize($input = 0, $format = 0) {
		$input 	= (float) $input;
		$format = (int) $this->validunit(6, $format);
		if ($this->input === 'si') {
			$div = array(1, 100, 10000, 1000000, 100000000, 10000000000, 1000000000000);
		} elseif ($this->input === 'uscs') {
			$div = array(1, 12, 36, 7920, 63360, 63360, 63360);
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
	*/
	protected function capacityDownsize($input = 0, $format = 0) {
		$input 	= (float) $input;
		$format = (int) $this->validunit(4, $format);
		if ($this->input === 'si') {
			$div = array(1, 1000, 1000000, 1000000000, 1000000000000);
		} elseif ($this->input === 'uscs') {
			$div = array(1, 1728, 46656, 75271680, 254358061056000);
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
	*/
	protected function cookingDownsize($input = 0, $format = 0) {
		$input 	= (float) $input;
		$format = (int) $this->validunit(2, $format);
		if ($this->input === 'si') {
			$div = array(1, 3, 3);
		} elseif ($this->input === 'uscs') {
			$div = array(1, 3, 48);
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
	*/
	protected function weightDownsize($input = 0, $format = 0) {
		$input 	= (float) $input;
		$format = (int) $this->validunit(6, $format);
		if ($this->input === 'si') {
			$div = array(1, 10, 100, 1000, 10000, 1000000, 1000000000);
		} elseif ($this->input === 'uscs') {
			$div = array(1, 27.34375, 437.5, 7000, 98000, 700000, 14000000);
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
	*/
	protected function liquidDownsize($input = 0, $format = 0) {
		$input 	= (float) $input;
		$format = (int) $this->validunit(7, $format);
		if ($this->input === 'si') {
			$div = array(1, 10, 100, 1000, 10000, 100000, 100000, 100000);
		} elseif ($this->input === 'uscs') {
			$div = array(1, 7680, 61440, 245760, 983040, 1966080, 7864320, 330301440);
		} // end if
		return (float) $input*$div[$format];
	} // end function
	/**#@-*/

	/**#@+
	* @param float $input  float value
	* @param int $input_format  Integer value (see description)
	* @param int $output_format  Integer value (see description)
	* @uses I18Nmeasure::validunit()
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
	* @uses I18Nmeasure::linearDownsize()
	*/
	public function linear($input = 0, $input_format = 0, $output_format = 0) {
		$input 		= (float) $this->linearDownsize($input, $input_format);
		$format		= (int) $this->validunit(4, $output_format);

		if ($this->input === 'si' && $this->output === 'si') {
			$formats 	= array('mm', 'cm', 'dm', 'm', 'km');
			$div 		= array(1, 10, 100, 1000, 1000000);
			$output 	= (float) $input/$div[$format];
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$formats 	= array('in', 'ft', 'yd', 'fur', 'mi');
			$div 		= array(25.4, 304.8, 914.4, 201168, 1609344);
			$output 	= (float) $input/$div[$format];
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$formats 	= array('mm', 'cm', 'dm', 'm', 'km');
			$div 		= array(25.4, 2.54, 0.254, 0.0254, 0.0000254);
			$output 	= (float) $input*$div[$format];
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$formats 	= array('in', 'ft', 'yd', 'fur', 'mi');
			$div 		= array(1, 12, 36, 7920, 63360);
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
	* @uses I18Nmeasure::surfaceDownsize()
	*/
	public function surface($input = 0, $input_format = 0, $output_format = 0) {
		$input 	= (float) $this->surfaceDownsize($input, $input_format);
		$format = (int) $this->validunit(6, $output_format);

		if ($this->input === 'si' && $this->output === 'si') {
			$formats 	= array('mm2', 'cm2', 'dm2', 'm2', 'a', 'ha', 'km2');
			$div 		= array(1, 100, 10000, 1000000, 100000000, 10000000000, 1000000000000);
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$formats 	= array('sqin', 'sqft', 'sqyd', 'sqrd', 'acre', 'sqmi', 'sqmi');
			$div 		= array(645.16, 92903.04, 836127.36, 25292853, 4046856400, 2589988100000, 2589988100000);
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$formats 	= array('mm2', 'cm2', 'dm2', 'm2', 'a', 'ha', 'km2');
			$div 		= array(0.0015500031, 0.15500031, 15.500031, 1550.0031, 155000.31, 15500031, 1550003100);
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$formats 	= array('sqin', 'sqft', 'sqyd', 'sqrd', 'acre', 'sqmi', 'sqmi');
			$div 		= array(1, 144, 1296, 39204, 6272640, 4014489600, 4014489600);
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
	* @uses I18Nmeasure::capacityDownsize()
	*/
	public function capacity($input = 0, $input_format = 0, $output_format = 0) {
		$input 	= (float) $this->capacityDownsize($input, $input_format);
		$format = (int) $this->validunit(4, $output_format);

		if ($this->input === 'si' && $this->output === 'si') {
			$formats 	= array('mm3', 'cm3', 'dm3', 'm3', 'km3');
			$div 		= array(1, 1000, 1000000, 1000000000, 1000000000000);
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$formats 	= array('cuin', 'cuft', 'cuyd', 'acrefd', 'cumi');
			$div 		= array(16387.064, 28316847, 764554860, 1233481800000, 4168181825440579584);
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$formats 	= array('mm3', 'cm3', 'dm3', 'm3', 'km3');
			$div 		= array(0.000061023744, 0.061023744, 61.023744, 61023.744, 61023744);
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$formats 	= array('cuin', 'cuft', 'cuyd', 'acrefd', 'cumi');
			$div 		= array(1, 1728, 46656, 75271680, 254358061056000);
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
	* @uses I18Nmeasure::cookingDownsize()
	*/
	public function cooking($input = 0, $input_format = 0, $output_format = 0) {
		$input 	= (float) $this->cookingDownsize($input, $input_format);
		$format = (int) $this->validunit(2, $format);

		if ($this->input === 'si' && $this->output === 'si') {
			$formats 	= array('tsp_is', 'tbs_is', 'tbs_is');
			$div 		= array(1, 3, 3);
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$formats 	= array('tsp_uscs', 'tbs_uscs', 'cup');
			$div 		= array(0.98578432, 2.957353, 47.317647);
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$formats 	= array('tsp_is', 'tbs_is', 'tbs_is');
			$div 		= array(1.0144207, 3.043262, 3.043262);
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$formats 	= array('tsp_uscs', 'tbs_uscs', 'cup');
			$div 		= array(1, 3, 48);
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
	* @uses I18Nmeasure::weightDownsize()
	*/
	public function weight($input = 0, $input_format = 0, $output_format = 0) {
		$input = (float) $this->weightDownsize($input, $input_format);
		$format = (int) $this->validunit(6, $output_format);

		if ($this->input === 'si' && $this->output === 'si') {
			$formats 	= array('mg', 'cg', 'dg', 'g', 'dag', 'kg', 'ton_is');
			$div 		= array(1, 10, 100, 1000, 10000, 1000000, 1000000000);
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$formats 	= array('grain', 'dr', 'oz', 'lb', 'stone', 'cwt_us', 'ton_us');
			$div 		= array(64.79891, 1771.8452, 28349.523, 453592.37, 6350293.2, 45359237, 907184740);
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$formats 	= array('mg', 'cg', 'dg', 'g', 'dag', 'kg', 'ton_is');
			$div 		= array(0.015432358, 0.15432358, 1.5432358, 15.432358, 154.32358, 15432.358, 15432358);
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$formats 	= array('grain', 'dr', 'oz', 'lb', 'stone', 'cwt_us', 'ton_us');
			$div 		= array(1, 27.34375, 437.5, 7000, 98000, 700000, 14000000);
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
	* @uses I18Nmeasure::liquidDownsize()
	*/
	public function liquid($input = 0, $input_format = 0, $output_format = 0) {
		$input = (float) $this->liquidDownsize($input, $input_format);
		$format = (int) $this->validunit(7, $output_format);

		if ($this->input === 'si' && $this->output === 'si') {
			$formats 	= array('ml', 'cl', 'dl', 'l', 'dal', 'hl', 'hl', 'hl');
			$div 		= array(1, 10, 100, 1000, 10000, 100000, 100000, 100000);
		} // end si & si
		elseif ($this->input === 'si' && $this->output === 'uscs') {
			$formats 	= array('min', 'fldr', 'floz', 'gi', 'pt', 'qt', 'gal', 'barrel');
			$div 		= array(0.00048133998891762809516053073702394, 3.6966912, 29.57353, 118.29412, 473.17647, 946.35295, 3785.4118, 158987.29);
		} // end si & uscs
		elseif ($this->input === 'uscs' && $this->output === 'si') {
			$formats 	= array('ml', 'cl', 'dl', 'l', 'dal', 'hl', 'hl', 'hl');
			$div 		= array(2077.5336, 20775.336, 207753.36, 2077533.6, 20775336, 207753360, 207753360, 207753360);
		} // end uscs & si
		elseif ($this->input === 'uscs' && $this->output === 'uscs') {
			$formats 	= array('min', 'fldr', 'floz', 'gi', 'pt', 'qt', 'gal', 'barrel');
			$div 		= array(1, 7680, 61440, 245760, 983040, 1966080, 7864320, 330301440);
		} // end uscs & uscs

		$this->display_format = (string) $formats[$format];
		return (float) $input/$div[$format];
	} // end function
	/**#@-*/

	/**
	* Converts a Temperature value from the input system to the output system.
	* @param float $input float value
	* @return float
	* @uses I18Nmeasure::$display_format
	* @uses I18Nmeasure::$output
	*/
	public function temperature($input = 0) {
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
	* @param int $show_ms
	* @return string $ms
	* @uses I18Nmeasure::$translator
	* @uses I18Nmeasure::::$display_format
	*/
	public function unit($show_ms = 3) {
		if ($show_ms < 0 || $show_ms > 3) {
			$show_ms = (int) 0;
		} // end if
		$show_ms = (int) $show_ms;
		$ms = '';

		if ($show_ms === 1) {
			$ms = $this->translator->_($this->display_format . '_short', 'lang_classMeasure');
		} elseif ($show_ms === 2) {
			$ms = $this->translator->_($this->display_format . '_long', 'lang_classMeasure');
		} elseif ($show_ms === 3) {
			$locale =& $this->translator->getTranslatorLocale()->getI18Nlocale();
			$ms = '<abbr title="' . $this->translator->_($this->display_format . '_long', 'lang_classMeasure') . '" xml:lang="' . $locale . '">' . $this->translator->_($this->display_format . '_short', 'lang_classMeasure') . '</abbr>';
		} // end if
		return $ms;
	} // end function
} // end class I18NMeasure
?>
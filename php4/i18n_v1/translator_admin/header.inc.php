<?php
ob_start();
session_start();

function getCountryName($iso) {
	$languages = array(
						'aa' => 'Afar',
						'ab' => 'Abkhazian',
						'af' => 'Afrikaans',
						'am' => 'Amharic',
						'ar' => 'Arabic',
						'as' => 'Assamese',
						'ay' => 'Aymara',
						'az' => 'Azerbaijani',
						'ba' => 'Bashkir',
						'be' => 'Byelorussian',
						'bg' => 'Bulgarian',
						'bh' => 'Bihari',
						'bi' => 'Bislama',
						'bn' => 'Bengali; Bangla',
						'bo' => 'Tibetan',
						'br' => 'Breton',
						'ca' => 'Catalan',
						'co' => 'Corsican',
						'cs' => 'Czech',
						'cy' => 'Welsh',
						'da' => 'Danish',
						'de' => 'German',
						'de_at' => 'Austrian German',
						'de_ch' => 'Swiss German',
						'dz' => 'Bhutani',
						'el' => 'Greek',
						'en' => 'English',
						'en_uk' => 'English UK',
						'en_ie' => 'English Ireland',
                        'en_us' => 'English USA',
						'eo' => 'Esperanto',
						'es' => 'Spanish',
						'et' => 'Estonian',
						'eu' => 'Basque',
						'fa' => 'Persian',
						'fi' => 'Finnish',
						'fj' => 'Fiji',
						'fo' => 'Faeroese',
						'fr' => 'French',
						'fy' => 'Frisian',
						'ga' => 'Irish',
						'gd' => 'Scots Gaelic',
						'gl' => 'Galician',
						'gn' => 'Guarani',
						'gu' => 'Gujarati',
						'ha' => 'Hausa',
						'hi' => 'Hindi',
						'hr' => 'Croatian',
						'hu' => 'Hungarian',
						'hy' => 'Armenian',
						'ia' => 'Interlingua',
						'ie' => 'Interlingue',
						'ik' => 'Inupiak',
						'in' => 'Indonesian',
						'is' => 'Icelandic',
						'it' => 'Italian',
						'iw' => 'Hebrew',
						'ja' => 'Japanese',
						'ji' => 'Yiddish',
						'jw' => 'Javanese',
						'ka' => 'Georgian',
						'kk' => 'Kazakh',
						'kl' => 'Greenlandic',
						'km' => 'Cambodian',
						'kn' => 'Kannada',
						'ko' => 'Korean',
						'ks' => 'Kashmiri',
						'ku' => 'Kurdish',
						'ky' => 'Kirghiz',
						'la' => 'Latin',
						'ln' => 'Lingala',
						'lo' => 'Laothian',
						'lt' => 'Lithuanian',
						'lv' => 'Latvian, Lettish',
						'mg' => 'Malagasy',
						'mi' => 'Maori',
						'mk' => 'Macedonian',
						'ml' => 'Malayalam',
						'mn' => 'Mongolian',
						'mo' => 'Moldavian',
						'mr' => 'Marathi',
						'ms' => 'Malay',
						'mt' => 'Maltese',
						'my' => 'Burmese',
						'na' => 'Nauru',
						'ne' => 'Nepali',
						'nl' => 'Dutch',
						'no' => 'Norwegian',
						'oc' => 'Occitan',
						'om' => '(Afan) Oromo',
						'or' => 'Oriya',
						'pa' => 'Punjabi',
						'pl' => 'Polish',
						'ps' => 'Pashto, Pushto',
						'pt' => 'Portuguese',
						'qu' => 'Quechua',
						'rm' => 'Rhaeto-Romance',
						'rn' => 'Kirundi',
						'ro' => 'Romanian',
						'ru' => 'Russian',
						'rw' => 'Kinyarwanda',
						'sa' => 'Sanskrit',
						'sd' => 'Sindhi',
						'sg' => 'Sangro',
						'sh' => 'Serbo-Croatian',
						'si' => 'Singhalese',
						'sk' => 'Slovak',
						'sl' => 'Slovenian',
						'sm' => 'Samoan',
						'sn' => 'Shona',
						'so' => 'Somali',
						'sq' => 'Albanian',
						'sr' => 'Serbian',
						'ss' => 'Siswati',
						'st' => 'Sesotho',
						'su' => 'Sundanese',
						'sv' => 'Swedish',
						'sw' => 'Swahili',
						'ta' => 'Tamil',
						'te' => 'Tegulu',
						'tg' => 'Tajik',
						'th' => 'Thai',
						'ti' => 'Tigrinya',
						'tk' => 'Turkmen',
						'tl' => 'Tagalog',
						'tn' => 'Setswana',
						'to' => 'Tonga',
						'tr' => 'Turkish',
						'ts' => 'Tsonga',
						'tt' => 'Tatar',
						'tw' => 'Twi',
						'uk' => 'Ukrainian',
						'ur' => 'Urdu',
						'uz' => 'Uzbek',
						'vi' => 'Vietnamese',
						'vo' => 'Volapuk',
						'wo' => 'Wolof',
						'xh' => 'Xhosa',
						'yo' => 'Yoruba',
						'zh' => 'Chinese',
						'zu' => 'Zulu'
					  );

	if (array_key_exists($iso, $languages))	 {
		return (string) $languages[$iso];
	} else {
		return (string) $iso;
	}

}



include_once('class.ReloadPreventer.inc.php');

if (file_exists('../flp_settings.ini')) {
		$this->translator_settings = (array) parse_ini_file('../flp_settings.ini', TRUE);
		$host = 			(string) $this->translator_settings['Translator']['host'];
		$user = 			(string) $this->translator_settings['Translator']['user'];
		$password = 		(string) $this->translator_settings['Translator']['password'];
		$database = 		(string) $this->translator_settings['Translator']['database'];
		$db_table = 		(string) $this->translator_settings['Translator']['translation_table'];
	} else {
		$host = 			(string) 'localhost';
		$user = 			(string) 'root';
		$password = 		(string) '';
		$database = 		(string) 'translator_testdb';
		$db_table = 		(string) 'flp_translator';
	} // end if

$conn = mysql_pconnect($host, $user, $password) or die ('Connection not possible! => ' . mysql_error());
mysql_select_db($database) or die ('Couldn\'t connect to "' . $database . '" => ' . mysql_error());
?>

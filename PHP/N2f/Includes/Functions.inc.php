<?php

	/**
	 * Determines if executing environment is
	 * recognized as Windows based.
	 * 
	 * @return bool True if PHP_OS starts with 'WIN', false otherwise.
	 */
	function env_is_windows() {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			return true;
		}

		return false;
	}

	/**
	 * Creates a unique identifier.
	 * 
	 * @return string Unique identifier.
	 */
	function env_get_guid() {
		if (function_exists('com_create_guid')) {
			return com_create_guid();
		} else {
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = chr(123)// "{"
				.substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12)
				.chr(125);// "}"
			return $uuid;
		}
	}

	/**
	 * Returns the list of supported locales.
	 * 
	 * @return string[] List of supported locales.
	 */
	function env_get_locales() {
		return array(
			'af-ZA', 'am-ET', 'ar-AE', 'ar-BH', 'ar-DZ', 'ar-EG', 'ar-IQ', 'ar-JO', 'ar-KW', 'ar-LB', 'ar-LY', 'ar-MA', 'arn-CL', 'ar-OM', 'ar-QA', 'ar-SA', 'ar-SY', 'ar-TN',
			'ar-YE', 'as-IN', 'az-Cyrl-AZ', 'az-Latn-AZ', 'ba-RU', 'be-BY', 'bg-BG', 'bn-BD', 'bn-IN', 'bo-CN', 'br-FR', 'bs-Cyrl-BA', 'bs-Latn-BA', 'ca-ES', 'co-FR', 'cs-CZ',
			'cy-GB', 'da-DK', 'de-AT', 'de-CH', 'de-DE', 'de-LI', 'de-LU', 'dsb-DE', 'dv-MV', 'el-GR', 'en-029', 'en-AU', 'en-BZ', 'en-CA', 'en-GB', 'en-IE', 'en-IN', 'en-JM',
			'en-MY', 'en-NZ', 'en-PH', 'en-SG', 'en-TT', 'en-US', 'en-ZA', 'en-ZW', 'es-AR', 'es-BO', 'es-CL', 'es-CO', 'es-CR', 'es-DO', 'es-EC', 'es-ES', 'es-GT', 'es-HN',
			'es-MX', 'es-NI', 'es-PA', 'es-PE', 'es-PR', 'es-PY', 'es-SV', 'es-US', 'es-UY', 'es-VE', 'et-EE', 'eu-ES', 'fa-IR', 'fi-FI', 'fil-PH', 'fo-FO', 'fr-BE', 'fr-CA',
			'fr-CH', 'fr-FR', 'fr-LU', 'fr-MC', 'fy-NL', 'ga-IE', 'gd-GB', 'gl-ES', 'gsw-FR', 'gu-IN', 'ha-Latn-NG', 'he-IL', 'hi-IN', 'hr-BA', 'hr-HR', 'hsb-DE', 'hu-HU',
			'hy-AM', 'id-ID', 'ig-NG', 'ii-CN', 'is-IS', 'it-CH', 'it-IT', 'iu-Cans-CA', 'iu-Latn-CA', 'ja-JP', 'ka-GE', 'kk-KZ', 'kl-GL', 'km-KH', 'kn-IN', 'kok-IN', 'ko-KR',
			'ky-KG', 'lb-LU', 'lo-LA', 'lt-LT', 'lv-LV', 'mi-NZ', 'mk-MK', 'ml-IN', 'mn-MN', 'mn-Mong-CN', 'moh-CA', 'mr-IN', 'ms-BN', 'ms-MY', 'mt-MT', 'nb-NO', 'ne-NP',
			'nl-BE', 'nl-NL', 'nn-NO', 'nso-ZA', 'oc-FR', 'or-IN', 'pa-IN', 'pl-PL', 'prs-AF', 'ps-AF', 'pt-BR', 'pt-PT', 'qut-GT', 'quz-BO', 'quz-EC', 'quz-PE', 'rm-CH',
			'ro-RO', 'ru-RU', 'rw-RW', 'sah-RU', 'sa-IN', 'se-FI', 'se-NO', 'se-SE', 'si-LK', 'sk-SK', 'sl-SI', 'sma-NO', 'sma-SE', 'smj-NO', 'smj-SE', 'smn-FI', 'sms-FI',
			'sq-AL', 'sr-Cyrl-BA', 'sr-Cyrl-CS', 'sr-Cyrl-ME', 'sr-Cyrl-RS', 'sr-Latn-BA', 'sr-Latn-CS', 'sr-Latn-ME', 'sr-Latn-RS', 'sv-FI', 'sv-SE', 'sw-KE', 'syr-SY', 'ta-IN',
			'te-IN', 'tg-Cyrl-TJ', 'th-TH', 'tk-TM', 'tn-ZA', 'tr-TR', 'tt-RU', 'tzm-Latn-DZ', 'ug-CN', 'uk-UA', 'ur-PK', 'uz-Cyrl-UZ', 'uz-Latn-UZ', 'vi-VN', 'wo-SN', 'xh-ZA',
			'yo-NG', 'zh-CN', 'zh-HK', 'zh-MO', 'zh-SG', 'zh-TW', 'zu-ZA'
		);
	}

	/**
	 * Parses a collection of arguments into an organized
	 * collection.  Pairs of arguments are put together
	 * while toggle elements (ie, -enable) are given a
	 * value of true.  Case sensitivity can be optionally
	 * disabled.
	 * 
	 * @param array $args Array of arguments to parse.
	 * @param bool $caseInsensitive Optional argument to disable case sensitivity in resulting array.
	 * @return array Array of organized argument values.
	 */
	function env_parse_params(array $args, $caseInsensitive = false) {
		$len = count($args);
		$assoc = array();

		for ($i = 0; $i < $len; ++$i) {
			if ($args[$i][0] == '-' && strlen($args[$i]) > 1) {
				$key = substr($args[$i], ($args[$i][1] == '-') ? 2 : 1);

				if (stripos($key, '=') !== false && strpos($key, '=') != strlen($key)) {
					$parts = explode('=', $key, 2);
					$assoc[($caseInsensitive) ? strtolower($parts[0]) : $parts[0]] = $parts[1];
				} else if (stripos($key, '-') !== false && strpos($key, '-') != strlen($key)) {
					$parts = explode('-', $key, 2);
					$assoc[($caseInsensitive) ? strtolower($parts[0]) : $parts[0]] = $parts[1];
				} else if (($i + 1) < $len) {
					$assoc[($caseInsensitive) ? strtolower($key) : $key] = ($args[$i + 1][0] != '-') ? $args[++$i] : true;
				} else {
					$assoc[($caseInsensitive) ? strtolower($key) : $key] = true;
				}
			} else {
				if (stripos($args[$i], '=') !== false) {
					$parts = explode('=', $args[$i], 2);
					$assoc[($caseInsensitive) ? strtolower($parts[0]) : $parts[0]] = $parts[1];
				} else {
					$assoc[($caseInsensitive) ? strtolower($args[$i]) : $args[$i]] = true;
				}
			}
		}

		return $assoc;
	}

<?php

	namespace N2f;

	/**
	 * Class that holds several helpful JSON methods.
	 *
	 * A class built to aid in the use of JSON within
	 * your scripts. Some code adapted from:
	 * https://github.com/GerHobbelt/nicejson-php
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class JsonHelper {
		/**
		 * Creates a new JsonHelper instance.
		 * 
		 * @return void
		 */
		public function __construct() {
			return;
		}

		/**
		 * Method to decode a JSON string.
		 * 
		 * @param string $Data JSON string to decode.
		 * @return mixed Decoded object or array.
		 */
		public function Decode($Data) {
			return json_decode($Data);
		}

		/**
		 * Method to decode a JSON string into
		 * an array.
		 * 
		 * @param string $Data JSON string to decode.
		 * @return array Decoded array.
		 */
		public function DecodeAssoc($Data) {
			return json_decode($Data, true);
		}

		/**
		 * Encodes data into a JSON string.
		 * 
		 * @param mixed $Data Data to encode.
		 * @param bool $Prettify Whether or not to produce formatted output.
		 * @return string JSON string version of $Data.
		 */
		public function Encode($Data = null, $Prettify = false) {
			$Json = ($Data === null) ? $this->_Data : $Data;

			if (!$Prettify) {
				return stripslashes(json_encode($Json));
			}

			if (phpversion() && phpversion() >= 5.4) {
				return json_encode($Json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			}

			$Json = stripslashes(json_encode($Json));
			$result = '';
			$ind = 0;
			$len = strlen($Json);
			$indStr = "\t";
			$endStr = "\n";
			$prevChar = '';
			$outOfQuotes = true;

			for ($i = 0; $i < $len; ++$i) {
				$char = substr($Json, $i, 1);

				if ($char == '"' && $prevChar != '\\') {
					$outOfQuotes = !$outOfQuotes;
				} else if (($char == '}' || $char == ']') && $outOfQuotes) {
					$result .= $endStr;
					$ind--;

					for ($j = 0; $j < $ind; $j++) {
						$result .= $indStr;
					}
				} else if ($outOfQuotes && false !== strpos(" \t\r\n", $char)) {
					continue;
				}

				$result .= $char;

				if ($char == ':' && $outOfQuotes) {
					$result .= ' ';
				}

				if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
					$result .= $endStr;
	
					if ($char == '{' || $char == '[') {
						$ind++;
					}
	
					for ($j = 0; $j < $ind; $j++) {
						$result .= $indStr;
					}
				}

				$prevChar = $char;
			}

			return $result;
		}

		/**
		 * Encodes data into a JSON string with
		 * formatted output.
		 * 
		 * @param mixed $Data Data to encode.
		 * @return string JSON string version of $Data.
		 */
		public function EncodePretty($Data = null) {
			return $this->Encode($Data, true);
		}
	}

?>
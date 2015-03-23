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
		private $_IsAssoc;
		private $_Data;

		public function __construct() {
			$this->_IsAssoc = false;

			return $this;
		}

		public function Decode($Data) {
			$this->_Data = json_decode($Data);

			return $this->_Data;
		}

		public function DecodeAssoc($Data) {
			$this->_Data = json_decode($Data, true);
			$this->_IsAssoc = true;

			return $this->_Data;
		}

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
	
					for ($j = 0; $j < $pos; $j++) {
						$result .= $indStr;
					}
				}

				$prevChar = $char;
			}

			return $result;
		}

		public function EncodePretty($Data = null) {
			return $this->Encode($Data, true);
		}

		public function GetData() {
			return $this->_Data;
		}

		public function SetData($Data) {
			if (is_string($Data)) {
				return $this;
			}

			$this->_Data = $Data;

			return $this;
		}
	}

?>
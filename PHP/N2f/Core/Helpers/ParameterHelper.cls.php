<?php

	namespace N2f;

	/**
	 * Class to give basic type casting for parameters.
	 *
	 * Helps to give basic type casting and by extension
	 * sanitization to an array of parameters.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class ParameterHelper {
		private $parameters = array();
		private $isValid = false;

		public function __construct(array $Params = null) {
			if ($Params === null || count($Params) < 1) {
				return;
			}

			$this->parameters = $Params;
			$this->isValid = true;

			return;
		}

		public function NumValues() {
			return count($this->parameters);
		}

		public function HasValue($Key) {
			return array_key_exists($Key, $this->parameters);
		}

		public function GetInt($Key, $Default = null) {
			if (!$this->isValid || !array_key_exists($Key, $this->parameters)) {
				return $Default;
			}

			return intval($this->parameters[$Key]);
		}

		public function GetFloat($Key, $Default = null) {
			if (!$this->isValid || !array_key_exists($Key, $this->parameters)) {
				return $Default;
			}

			return floatval($this->parameters[$Key]);
		}

		public function GetDouble($Key, $Default = null) {
			if (!$this->isValid || !array_key_exists($Key, $this->parameters)) {
				return $Default;
			}

			return doubleval($this->parameters[$Key]);
		}

		public function GetBool($Key, $Default = null) {
			if (!$this->isValid || !array_key_exists($Key, $this->parameters)) {
				return $Default;
			}

			return boolval($this->parameters[$Key]);
		}

		public function GetJson($Key, $AsArray = false, $Default = null) {
			if (!$this->isValid || !array_key_exists($Key, $this->parameters)) {
				return $Default;
			}

			return json_decode($this->parameters[$Key], $AsArray);
		}

		public function GetString($Key, $Default = null) {
			if (!$this->isValid || !array_key_exists($Key, $this->parameters)) {
				return $Default;
			}

			return strval($this->parameters[$Key]);
		}

		public function GetRaw($Key, $Default = null) {
			if (!$this->isValid || ($Key !== null && !array_key_exists($Key, $this->parameters))) {
				return $Default;
			}

			if ($Key === null) {
				return $this->parameters;
			}

			return $this->parameters[$Key];
		}
	}

?>
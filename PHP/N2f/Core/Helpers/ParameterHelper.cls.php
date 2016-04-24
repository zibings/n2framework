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
	 * @copyright 2014-2016 Zibings.com
	 * @package N2F
	 */
	class ParameterHelper {
		/**
		 * Array of parameters.
		 * 
		 * @var array
		 */
		private $parameters = array();
		/**
		 * Whether or not the instance is valid.
		 * 
		 * @var bool
		 */
		private $isValid = false;

		/**
		 * Creates a new ParameterHelper instance.
		 * 
		 * @param array $Params Array of parameters to dispense.
		 * @return void
		 */
		public function __construct(array $Params = null) {
			if ($Params === null || count($Params) < 1) {
				return;
			}

			$this->parameters = $Params;
			$this->isValid = true;

			return;
		}

		/**
		 * Returns the number of values in the parameter list.
		 * 
		 * @return int Number of parameters.
		 */
		public function NumValues() {
			return count($this->parameters);
		}

		/**
		 * Check if a value exists within the parameter list.
		 * 
		 * @param string $Key String value of key to compare against.
		 * @return bool True if key exists in parameter list, false otherwise.
		 */
		public function HasValue($Key) {
			return array_key_exists($Key, $this->parameters);
		}

		/**
		 * Returns a parameter cast as an integer.
		 * 
		 * @param string $Key String value of key to retrieve.
		 * @param int $Default Optional default value.
		 * @return int Integer value of key or default value if not present.
		 */
		public function GetInt($Key, $Default = null) {
			if (!$this->isValid || !array_key_exists($Key, $this->parameters)) {
				return $Default;
			}

			return intval($this->parameters[$Key]);
		}

		/**
		 * Returns a parameter cast as a float.
		 * 
		 * @param string $Key String value of key to retrieve.
		 * @param float $Default Optional default value.
		 * @return float Float value of key or default value if not present.
		 */
		public function GetFloat($Key, $Default = null) {
			if (!$this->isValid || !array_key_exists($Key, $this->parameters)) {
				return $Default;
			}

			return floatval($this->parameters[$Key]);
		}

		/**
		 * Returns a parameter cast as a double.
		 * 
		 * @param string $Key String value of key to retrieve.
		 * @param double $Default Optional default value.
		 * @return double Double value of key or default value if not present.
		 */
		public function GetDouble($Key, $Default = null) {
			if (!$this->isValid || !array_key_exists($Key, $this->parameters)) {
				return $Default;
			}

			return doubleval($this->parameters[$Key]);
		}

		/**
		 * Returns a parameter cast as a bool.
		 * 
		 * @param string $Key String value of key to retrieve.
		 * @param bool $Default Optional default value.
		 * @return bool Bool value of key or default value if not present.
		 */
		public function GetBool($Key, $Default = null) {
			if (!$this->isValid || !array_key_exists($Key, $this->parameters)) {
				return $Default;
			}

			return boolval($this->parameters[$Key]);
		}

		/**
		 * Returns a parameter cast as decoded JSON
		 * data.
		 * 
		 * @param string $Key String value of key to retrieve.
		 * @param bool $AsArray Toggle returning as an array.
		 * @param mixed $Default Optional default value.
		 * @return mixed Mixed value of key or default value if not present.
		 */
		public function GetJson($Key, $AsArray = false, $Default = null) {
			if (!$this->isValid || !array_key_exists($Key, $this->parameters)) {
				return $Default;
			}

			return json_decode($this->parameters[$Key], $AsArray);
		}

		/**
		 * Returns a parameter cast as a string.
		 * 
		 * @param string $Key String value of key to retrieve.
		 * @param string $Default Optional default value.
		 * @return string String value of key or default value if not present.
		 */
		public function GetString($Key, $Default = null) {
			if (!$this->isValid || !array_key_exists($Key, $this->parameters)) {
				return $Default;
			}

			return strval($this->parameters[$Key]);
		}

		/**
		 * Returns a raw parameter value.
		 * 
		 * @param string $Key String value of key to retrieve.
		 * @param mixed $Default Optional default value.
		 * @return mixed Mixed value of key or default value if not present.
		 */
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

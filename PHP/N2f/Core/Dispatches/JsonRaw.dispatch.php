<?php

	namespace N2f;

	/**
	 * Dispatch type for raw JSON information.
	 *
	 * Dispatch object that provides basic
	 * breakdown of JSON data.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2016 Zibings.com
	 * @package N2F
	 */
	class JsonRawDispatch extends DispatchBase {
		/**
		 * Internal instance of a \N2f\JsonHelper
		 * 
		 * @var \N2f\JsonHelper
		 */
		protected $_JsonHelper;
		/**
		 * The raw JSON string.
		 * 
		 * @var string
		 */
		protected $_RawData;
		/**
		 * Array of raw data decoded into
		 * default and associative formats.
		 * 
		 * @var array
		 */
		protected $_Parsed;

		/**
		 * Instantiates a new JsonRawDispatch instance.
		 * 
		 * @return \N2f\JsonRawDispatch
		 */
		public function __construct() {
			$this->MakeConsumable();

			$this->_JsonHelper = null;
			$this->_RawData = null;
			$this->_Parsed = null;

			return;
		}

		/**
		 * Initializes the JsonRawDispatch instance.
		 * 
		 * $Input can be either a string of JSON formatted data
		 * or an array of a JSON string and an optional \N2f\JsonHelper
		 * instance.
		 * 
		 * @param mixed $Input String or array of string and optional JsonHelper.
		 * @return void
		 */
		public function Initialize($Input) {
			if ($Input === null || (!is_array($Input) && strlen($Input) < 1)) {
				return;
			}

			if (is_array($Input) && (count($Input) < 1 || !array_key_exists('json', $Input))) {
				return;
			}

			if (is_array($Input)) {
				$this->_RawData = $Input['Json'];
				$this->_JsonHelper = (array_key_exists('JsonHelper', $Input) && $Input['JsonHelper'] instanceof JsonHelper) ? $Input['JsonHelper'] : new JsonHelper();
			} else {
				$this->_RawData = $Input;
				$this->_JsonHelper = new JsonHelper();
			}

			$this->_Parsed = array(
				'default' => $this->_JsonHelper->Decode($this->_RawData),
				'assoc' => $this->_JsonHelper->DecodeAssoc($this->_RawData)
			);

			if ($this->_Parsed['default'] === null) {
				// though this is theoretically a valid JSON value, we are going to
				// use it to mean that there wasn't actually any JSON, thus making
				// a JSON dispatch useless
				return;
			}

			$this->MakeValid();

			return;
		}

		/**
		 * Returns the default decoded JSON data in the
		 * appropriate format (object or array).
		 * 
		 * @return mixed
		 */
		public function GetDecoded() {
			return $this->_Parsed['default'];
		}

		/**
		 * Returns an associative array of the decoded
		 * JSON data.
		 * 
		 * @return array
		 */
		public function GetDecodedAssoc() {
			return $this->_Parsed['assoc'];
		}

		/**
		 * Returns the internal instance of an \N2f\JsonHelper.
		 * 
		 * @return \N2f\JsonHelper
		 */
		public function GetJsonHelper() {
			return $this->_JsonHelper;
		}

		/**
		 * Returns the raw JSON string provided during
		 * initialization.
		 * 
		 * @return string
		 */
		public function GetRaw() {
			return $this->_RawData;
		}
	}

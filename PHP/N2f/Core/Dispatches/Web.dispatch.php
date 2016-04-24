<?php

	namespace N2f;

	/**
	 * Dispatch type for non-json web requests.
	 *
	 * Dispatch object that provides basic information
	 * for requests made through a web server which
	 * are not JSON.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2016 Zibings.com
	 * @package N2F
	 */
	class WebDispatch extends DispatchBase {
		/**
		 * Instance of RequestHelper.
		 * 
		 * @var \N2f\RequestHelper
		 */
		protected $_RequestHelper;
		/**
		 * Type of web request.
		 * 
		 * @var \N2f\RequestType
		 */
		protected $_RequestType;
		/**
		 * Array of files from request (if present).
		 * 
		 * @var array
		 */
		protected $_Files;
		/**
		 * Request data array, comprised of primary and fallback sources.
		 * 
		 * @var array
		 */
		protected $_Data;

		/**
		 * Creates a new instance of a WebDispatch.
		 * 
		 * @return void
		 */
		public function __construct() {
			$this->MakeConsumable();

			$this->_RequestHelper = null;
			$this->_Files = array();
			$this->_Data = array(
				'primary' => new ParameterHelper(),
				'fallback' => new ParameterHelper()
			);

			return;
		}

		/**
		 * Initializes a WebDispatch instance.
		 * 
		 * @param \N2f\RequestHelper $Input A RequestHelper instance used for initialization.
		 * @return void
		 */
		public function Initialize($Input) {
			if ($Input === null || !($Input instanceof RequestHelper)) {
				return;
			}

			$ServerVars = $Input->GetEnv(EnvironmentInfo::SERVER);
			$FileVars = $Input->GetEnv(EnvironmentInfo::FILES);

			$this->_Data['primary'] = $Input->GetInput();
			$this->_RequestHelper = $Input;

			switch ($ServerVars->GetString('REQUEST_METHOD')) {
				case 'PUT':
					$this->_RequestType = new RequestType(RequestType::PUT);
					
					break;
				case 'POST':
					$this->_RequestType = new RequestType(RequestType::POST);

					$this->_Data['fallback'] = $this->_Data['primary'];
					$this->_Data['primary'] = $Input->GetInput(RequestType::POST);
					
					break;
				case 'GET':
					$this->_RequestType = new RequestType(RequestType::GET);

					$this->_Data['fallback'] = $this->_Data['primary'];
					$this->_Data['primary'] = $Input->GetInput(RequestType::GET);
					
					break;
				case 'HEAD':
					$this->_RequestType = new RequestType(RequestType::HEAD);
					
					break;
				case 'DELETE':
					$this->_RequestType = new RequestType(RequestType::DELETE);
					
					break;
				case 'OPTIONS':
					$this->_RequestType = new RequestType(RequestType::OPTIONS);
					
					break;
				default:
					$this->_RequestType = new RequestType(RequestType::ERROR);
					
					break;
			}

			if ($FileVars->NumValues() > 0) {
				foreach (array_values($FileVars->GetRaw(null)) as $FILE) {
					if (array_key_exists('name', $FILE)) {
						$this->_Files = $FileVars->GetRaw(null);

						break;
					}
				}
			}

			$this->MakeValid();

			return;
		}

		/**
		 * Returns true or false based on the presence of the parameter value.
		 * 
		 * @param mixed $Key String or integer representing the value's key.
		 * @return bool True if key exists in either primary or fallback source, false if not found.
		 */
		public function ParamExists($Key) {
			return $this->_Data['primary']->HasValue($Key) || $this->_Data['fallback']->HasValue($Key);
		}

		/**
		 * Returns the current request type.
		 * 
		 * @return \N2f\RequestType RequestType value for this dispatch.
		 */
		public function GetRequestType() {
			return $this->_RequestType;
		}

		/**
		 * Returns all parameters from the request.
		 * 
		 * @return \N2f\ParameterHelper ParameterHelper with data from primary and fallback sources merged if possible.
		 */
		public function GetParams() {
			if ($this->_Data['primary']->NumValues() < 1 && $this->_Data['fallback']->NumValues() < 1) {
				return new ParameterHelper();
			}

			if ($this->_Data['primary']->NumValues() < 1) {
				return $this->_Data['fallback'];
			} else if ($this->_Data['fallback']->NumValues() < 1) {
				return $this->_Data['primary'];
			} else {
				return new ParameterHelper(array_merge($this->_Data['fallback']->GetRaw(null), $this->_Data['primary']->GetRaw(null)));
			}
		}

		/**
		 * Returns the requested environment information.
		 * 
		 * @param \N2f\EnvironmentInfo $EnvInfo Optional environment info specifier (returns SERVER when not specified).
		 * @return \N2f\ParameterHelper ParameterHelper including environment information.
		 */
		public function GetEnv($EnvInfo = null) {
			return $this->_RequestHelper->GetEnv($EnvInfo);
		}

		/**
		 * Returns true or false based on the presence of uploaded files.
		 * 
		 * @return bool True if there are uploaded files, false otherwise.
		 */
		public function HasFiles() {
			return count($this->_Files) > 0;
		}

		/**
		 * Returns the raw $_FILES array (if present in request).
		 * 
		 * @return array Array from $_FILES environment variable.
		 */
		public function GetFiles() {
			return $this->_Files;
		}
	}

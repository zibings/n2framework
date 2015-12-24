<?php

	namespace N2f;

	/**
	 * Dispatch type for web JSON requests.
	 *
	 * Dispatch object that provides information
	 * related to JSON requests from the web.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class JsonDispatch extends DispatchBase {
		/**
		 * Instance of RequestHelper.
		 * 
		 * @var \N2f\RequestHelper
		 */
		protected $_RequestHelper;
		/**
		 * Type of JSON request.
		 * 
		 * @var \N2f\RequestType
		 */
		protected $_RequestType;
		/**
		 * Request data container.
		 * 
		 * @var \N2f\ParameterHelper
		 */
		protected $_Data;

		/**
		 * Create an instance of JsonDispatch.
		 * 
		 * @return void
		 */
		public function __construct() {
			$this->MakeConsumable();

			$this->_RequestHelper = null;
			$this->_Data = new ParameterHelper();

			return;
		}

		/**
		 * Initializes a JsonDispatch instance.
		 * 
		 * @param \N2f\RequestHelper $Input RequestHelper instance to initialize dispatch.
		 * @return void
		 */
		public function Initialize($Input) {
			if ($Input === null || !($Input instanceof RequestHelper)) {
				return;
			}

			$this->_Data = $Input->GetInput();
			$this->_RequestHelper = $Input;

			switch ($_SERVER['REQUEST_METHOD']) {
				case 'PUT':
					$this->_RequestType = new RequestType(RequestType::PUT);
					
					break;
				case 'POST':
					$this->_RequestType = new RequestType(RequestType::POST);
					
					break;
				case 'GET':
					$this->_RequestType = new RequestType(RequestType::GET);
					
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

			$this->MakeValid();

			return;
		}

		/**
		 * Returns true or false based on the presence of the parameter value.
		 * 
		 * @param mixed $Key String or integer representing the value's key.
		 * @return bool True if key exists in data container.
		 */
		public function ParamExists($Key) {
			return $this->_Data->HasValue($Key);
		}

		/**
		 * Returns the current request type.
		 * 
		 * @return \N2f\RequestType The current RequestType for the dispatch.
		 */
		public function GetRequestType() {
			return $this->_RequestType;
		}

		/**
		 * Returns all parameters from the request.
		 * 
		 * @return \N2f\ParameterHelper The data container for the dispatch.
		 */
		public function GetParams() {
			return $this->_Data;
		}

		/**
		 * Returns the requested environment information.
		 * 
		 * @param \N2f\EnvironmentInfo $EnvInfo Optional environment info specifier (returns SERVER when not specified).
		 * @return \N2f\ParameterHelper A new data container for the specified environment info.
		 */
		public function GetEnv($EnvInfo = null) {
			return $this->_RequestHelper->GetEnv($EnvInfo);
		}
	}

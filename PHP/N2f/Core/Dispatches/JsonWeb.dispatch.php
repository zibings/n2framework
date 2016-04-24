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
	 * @copyright 2014-2016 Zibings.com
	 * @package N2F
	 */
	class JsonWebDispatch extends JsonRawDispatch {
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
		 * Create an instance of JsonWebDispatch.
		 * 
		 * @return void
		 */
		public function __construct() {
			parent::__construct();

			$this->_RequestHelper = null;

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

			if (!$Input->IsJson()) {
				return;
			}

			$this->_RequestHelper = $Input;
			$server = $Input->GetEnv(new EnvironmentInfo(EnvironmentInfo::SERVER));

			if (!$server->HasValue('REQUEST_METHOD')) {
				return;
			}

			switch (strtoupper($server->GetString('REQUEST_METHOD'))) {
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

			parent::Initialize($Input->GetInput());

			return;
		}

		/**
		 * Returns the current request type.
		 * 
		 * @return \N2f\RequestType The current RequestType for the dispatch.
		 */
		public function GetRequestType() {
			return $this->_RequestType;
		}
	}

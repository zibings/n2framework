<?php

	namespace N2f;

	/**
	 * Class to help with handling request input.
	 *
	 * Helps to manage and sanitize request input
	 * for all base systems.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class RequestHelper {
		/**
		 * Static instance of input string.
		 * 
		 * @var string
		 */
		private static $_InputString = '';
		/**
		 * Static instance of whether or not request
		 * is JSON.
		 * 
		 * @var bool
		 */
		private static $_IsJson = null;

		/**
		 * Retrieves and checks certain pieces of
		 * a request to determine if the request
		 * is JSON.
		 * 
		 * @return bool
		 */
		public function IsJson() {
			if (RequestHelper::$_IsJson !== null) {
				return RequestHelper::$_IsJson;
			}

			RequestHelper::$_IsJson = false;

			if (!empty($_FILES)) {
				return false;
			}

			try {
				if (empty(RequestHelper::$_InputString)) {
					RequestHelper::ReadInput();
				}

				if (empty(RequestHelper::$_InputString)) {
					return false;
				}

				json_decode(trim(RequestHelper::$_InputString), true);

				if ((RequestHelper::$_InputString[0] == '{' || RequestHelper::$_InputString[0] == '[') && json_last_error() == JSON_ERROR_NONE) {
					RequestHelper::$_IsJson = true;

					return true;
				}

				return false;
			} catch (Exception $e) {
				return false;
			}
		}

		/**
		 * Returns a ParameterHelper with the specified
		 * input information.
		 * 
		 * @param mixed $Type Optional type of input to return (returns REQUEST if not specified).
		 * @return \N2f\ParameterHelper The current ParameterHelper instance.
		 */
		public function GetInput($Type = null) {
			if ($Type === null && $this->IsJson()) {
				return new ParameterHelper(json_decode(trim(RequestHelper::$_InputString), true));
			}

			switch ($Type) {
				case RequestType::POST:
					return new ParameterHelper($_POST);
				case RequestType::GET:
					return new ParameterHelper($_GET);
				default:
					return new ParameterHelper($_REQUEST);
			}
		}

		/**
		 * Returns a ParameterHelper with the specified
		 * environment information.
		 * 
		 * @param \N2f\EnvironmentInfo $EnvInfo Optional type of environment info to return (returns SERVER if not specified).
		 * @return \N2f\ParameterHelper The current ParameterHelper instance.
		 */
		public function GetEnv($EnvInfo = null) {
			switch ($EnvInfo) {
				case EnvironmentInfo::COOKIE:
					return new ParameterHelper($_COOKIE);
				case EnvironmentInfo::ENV:
					return new ParameterHelper($_ENV);
				case EnvironmentInfo::FILES:
					return new ParameterHelper($_FILES);
				case EnvironmentInfo::SERVER:
				default:
					return new ParameterHelper($_SERVER);
			}
		}

		/**
		 * Internal method to read the input string.
		 * 
		 * @return \N2f\ReturnHelper A ReturnHelper instance with extra state information.
		 */
		protected static function ReadInput() {
			$Ret = new ReturnHelper();

			if (!empty(RequestHelper::$_InputString)) {
				$Ret->SetMessage("Trying to read input when input is already read.");
			} else {
				try {
					if ((RequestHelper::$_InputString = @file_get_contents("php://input")) !== false) {
						$Ret->SetGud();
					} else {
						$Ret->SetMessage("Failed to read file.");
					}
				} catch (Exception $e) {
					RequestHelper::$_InputString = '';
				}
			}

			return $Ret;
		}
	}

<?php

	namespace N2f;

	/**
	 * Dispatch type for calls through CLI.
	 *
	 * Dispatch object that provides basic information from when
	 * script was called via CLI.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class CliDispatch extends DispatchBase {
		/**
		 * Whether or not the executing environment
		 * is Windows based.
		 * 
		 * @var bool
		 */
		protected $_IsWindows;
		/**
		 * Collection of arguments.
		 * 
		 * @var array
		 */
		protected $_ArgInfo;
		/**
		 * Instance of ConsoleHelper.
		 * 
		 * @var \N2f\ConsoleHelper
		 */
		protected $_Ch;

		/**
		 * Creates a new CliDispatch instance.
		 * Instance is consumable.
		 * 
		 * @return void
		 */
		public function __construct() {
			$this->MakeConsumable();

			return;
		}

		/**
		 * Initializes a CliDispatch instance. Must
		 * include a 'ConsoleHelper' or 'argv' element.
		 * 
		 * @param array $Input Array of initialization information.
		 * @return void
		 */
		public function Initialize($Input) {
			if ($Input === null || !is_array($Input) || count($Input) < 1) {
				return;
			}

			if (array_key_exists('argv', $Input)) {
				$this->_Ch = new ConsoleHelper(count($Input['argv']), $Input['argv']);
				$this->_ArgInfo['env'] = $Input;
			} else if (array_key_exists('ConsoleHelper', $Input) && $Input['ConsoleHelper'] instanceof ConsoleHelper) {
				$this->_Ch = $Input['ConsoleHelper'];
				$this->_ArgInfo['env'] = $Input;
			} else {
				$this->_Ch = new ConsoleHelper(count($Input), $Input);
				$this->_ArgInfo['env'] = array('argv' => $Input);
			}

			$this->_IsWindows = \env_is_windows();
			$this->_CalledDateTime = new \DateTime('now', new \DateTimeZone('UTC'));

			$this->MakeValid();

			return;
		}

		/**
		 * Returns whether or not executing environment
		 * is Windows based.
		 * 
		 * @return bool True if PHP_OS starts with WIN, false otherwise.
		 */
		public function IsWindows() {
			return $this->_IsWindows;
		}

		/**
		 * Returns the array of parameters.
		 * 
		 * @return array Array of command line parameters.
		 */
		public function GetParameters() {
			return $this->_Ch->Parameters();
		}

		/**
		 * Returns the environment parmeters.
		 * 
		 * @return array Array of environment parameters.
		 */
		public function GetEnvParameters() {
			return $this->_ArgInfo['env'];
		}

		/**
		 * Returns an associative array of the command
		 * line parameters.
		 * 
		 * @return array Associative array of command line parameters.
		 */
		public function GetAssocParameters() {
			return $this->_Ch->Parameters(true);
		}

		/**
		 * Returns the ConsoleHelper instance.
		 * 
		 * @return \N2f\ConsoleHelper
		 */
		public function GetConsoleHelper() {
			return $this->_Ch;
		}
	}

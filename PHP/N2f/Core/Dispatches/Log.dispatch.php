<?php

	namespace N2f;

	/**
	 * Dispatch type for log calls.
	 *
	 * Dispatch object that provides information
	 * related to single log calls.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2016 Zibings.com
	 * @package N2F
	 */
	class LogDispatch extends DispatchBase {
		/**
		 * Log level of entry.
		 * 
		 * @var int
		 */
		protected $_Level;
		/**
		 * String value of entry.
		 * 
		 * @var string
		 */
		protected $_Message;
		/**
		 * UNIX timestamp for entry creation.
		 * 
		 * @var int
		 */
		protected $_Time;

		/**
		 * Creates a new LogDispatch instance.
		 * 
		 * @return void
		 */
		public function __construct() {
			return;
		}

		/**
		 * Returns the log level of the entry.
		 * 
		 * @return int Integer representing the log level flag.
		 */
		public function GetLevel() {
			return $this->_Level;
		}

		/**
		 * Returns the message of the entry.
		 * 
		 * @return string String value of entry message.
		 */
		public function GetMessage() {
			return $this->_Message;
		}

		/**
		 * Returns the UNIX timestamp of the entry.
		 * 
		 * @return int UNIX timestamp of entry.
		 */
		public function GetTime() {
			return $this->_Time;
		}

		/**
		 * Initializes a LogDispatch instance. Must include
		 * 'Level', 'Message', and 'Time' elements.
		 * 
		 * @param array $Input Array of input information.
		 * @return void
		 */
		public function Initialize($Input) {
			if ($Input === null || !is_array($Input) || count($Input) != 3) {
				return;
			}

			if (!array_key_exists('Level', $Input) || !array_key_exists('Message', $Input) || !array_key_exists('Time', $Input)) {
				return;
			}

			$this->_Level = $Input['Level'];
			$this->_Message = $Input['Message'];
			$this->_Time = $Input['Time'];

			$this->MakeValid();

			return;
		}
	}

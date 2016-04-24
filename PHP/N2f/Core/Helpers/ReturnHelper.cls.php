<?php

	namespace N2f;

	/**
	 * Class to make for more descriptive return values.
	 *
	 * Simple class for including extra information when
	 * a function/method returns.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2016 Zibings.com
	 * @package N2F
	 */
	class ReturnHelper {
		/**
		 * Collection of messages for instance.
		 * 
		 * @var array
		 */
		private $_Messages;
		/**
		 * Collection of results for instance.
		 * 
		 * @var array
		 */
		private $_Results;
		/**
		 * Current status for instance. Default
		 * is BAD.
		 * 
		 * @var int
		 */
		private $_Status;

		const BAD = 0;
		const GUD = 1;

		/**
		 * Creates a new ReturnHelper instance. Defaults
		 * status to BAD.
		 * 
		 * @return void
		 */
		public function __construct() {
			$this->_Messages = array();
			$this->_Results = array();
			$this->_Status = self::BAD;

			return;
		}

		/**
		 * Returns whether or not the status is bad.
		 * 
		 * @return bool True if status is BAD, false otherwise.
		 */
		public function IsBad() {
			return !$this->_Status;
		}

		/**
		 * Alias of IsGud() for those with stricter
		 * english requirements.
		 * 
		 * @return bool True if status is GUD, false otherwise.
		 */
		public function IsGood() {
			return $this->IsGud();
		}

		/**
		 * Returns whether or not the status is good.
		 * 
		 * @return bool True if status is GUD, false otherwise.
		 */
		public function IsGud() {
			return $this->_Status === self::GUD;
		}

		/**
		 * Returns any messages the instance contains.
		 * 
		 * @return array|null Array of messages if available, null otherwise.
		 */
		public function GetMessages() {
			if (count($this->_Messages) < 1) {
				return null;
			}

			return $this->_Messages;
		}

		/**
		 * Returns any results the instance contains.
		 * 
		 * @return mixed|null Results if available, null otherwise.
		 */
		public function GetResults() {
			if (count($this->_Results) < 1) {
				return null;
			} else if (count($this->_Results) == 1) {
				return $this->_Results[0];
			}

			return $this->_Results;
		}

		/**
		 * Returns whether or not the instance contains
		 * messages.
		 * 
		 * @return bool True if messages are present, false otherwise.
		 */
		public function HasMessages() {
			return count($this->_Messages) > 0;
		}

		/**
		 * Adds a message to the instance.
		 * 
		 * @param string $Message String value of message to add.
		 * @return \N2f\ReturnHelper The current ReturnHelper instance.
		 */
		public function SetMessage($Message) {
			$this->_Messages[] = $Message;

			return $this;
		}

		/**
		 * Adds multiple messages to the instance.
		 * 
		 * @param array $Messages Array of string values to add.
		 * @return \N2f\ReturnHelper The current ReturnHelper instance.
		 */
		public function SetMessages(array $Messages) {
			if (count($Messages) < 1) {
				return $this;
			}

			foreach (array_values($Messages) as $Msg) {
				$this->_Messages[] = $Msg;
			}

			return $this;
		}

		/**
		 * Adds a result to the instance.
		 * 
		 * @param mixed $Result Result to add to instance.
		 * @return \N2f\ReturnHelper The current ReturnHelper instance.
		 */
		public function SetResult($Result) {
			$this->_Results[] = $Result;

			return $this;
		}

		/**
		 * Adds multiple results to the instance.
		 * 
		 * @param array $Results Array of results to add.
		 * @return \N2f\ReturnHelper The current ReturnHelper instance.
		 */
		public function SetResults(array $Results) {
			if (count($Results) < 1) {
				return $this;
			}

			foreach (array_values($Results) as $Res) {
				$this->_Results[] = $Res;
			}

			return $this;
		}

		/**
		 * Sets the instance status to BAD.
		 * 
		 * @return \N2f\ReturnHelper The current ReturnHelper instance.
		 */
		public function SetBad() {
			$this->_Status = self::BAD;

			return $this;
		}

		/**
		 * Alias of SetGud() for those with stricter
		 * english requirements.
		 * 
		 * @return \N2f\ReturnHelper The current ReturnHelper instance.
		 */
		public function SetGood() {
			return $this->SetGud();
		}

		/**
		 * Sets the instance status to GUD.
		 * 
		 * @return \N2f\ReturnHelper The current ReturnHelper instance.
		 */
		public function SetGud() {
			$this->_Status = self::GUD;

			return $this;
		}

		/**
		 * Sets the instance status.
		 * 
		 * @param int $Status Integer value of new instance status.
		 * @return \N2f\ReturnHelper The current ReturnHelper instance.
		 */
		public function SetStatus($Status) {
			$this->_Status = intval($Status);

			return $this;
		}
	}

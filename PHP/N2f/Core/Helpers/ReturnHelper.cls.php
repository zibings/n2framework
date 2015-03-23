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
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class ReturnHelper {
		private $_Messages;
		private $_Results;
		private $_Status;

		const BAD = 0;
		const GUD = 1;

		public function __construct() {
			$this->_Messages = array();
			$this->_Results = array();
			$this->_Status = self::BAD;

			return $this;
		}

		public function IsBad() {
			return !$this->_Status;
		}

		public function IsGood() {
			return $this->IsGud();
		}

		public function IsGud() {
			return $this->_Status;
		}

		public function GetMessages() {
			if (count($this->_Messages) < 1) {
				return null;
			}

			return $this->_Messages;
		}

		public function GetResults() {
			if (count($this->_Results) < 1) {
				return null;
			} else if (count($this->_Results) == 1) {
				return $this->_Results[0];
			}

			return $this->_Results;
		}

		public function HasMessages() {
			return count($this->_Messages) > 0;
		}

		public function SetMessage($Message) {
			$this->_Messages[] = $Message;

			return $this;
		}

		public function SetMessages(array $Messages) {
			if (count($Messages) < 1) {
				return $this;
			}

			foreach (array_values($Messages) as $Msg) {
				$this->_Messages[] = $Msg;
			}

			return $this;
		}

		public function SetResult($Result) {
			$this->_Results[] = $Result;

			return $this;
		}

		public function SetResults(array $Results) {
			if (count($Results) < 1) {
				return $this;
			}

			foreach (array_values($Results) as $Res) {
				$this->_Results[] = $Res;
			}

			return $this;
		}

		public function SetBad() {
			$this->_Status = self::BAD;

			return $this;
		}

		public function SetGood() {
			return $this->SetGud();
		}

		public function SetGud() {
			$this->_Status = self::GUD;

			return $this;
		}

		public function SetStatus($Status) {
			$this->_Status = intval($Status);

			return $this;
		}
	}

?>
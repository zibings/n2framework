<?php

	namespace N2f;

	/**
	 * Abstract for all dispatch messages in system.
	 *
	 * Abstract that defines required functionality for all dispatch
	 * objects in system.  Dispatch objects which implement can be
	 * more complex based on needs.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	abstract class DispatchBase {
		/**
		 * Whether or not the dispatch can be consumed.
		 * 
		 * @var bool
		 */
		protected $_IsConsumable = false;
		/**
		 * Whether or not the dispatch can hold one or multiple results.
		 * 
		 * @var bool
		 */
		protected $_IsStateful = false;
		/**
		 * Whether or not the dispatch has been consumed (if consumable).
		 * 
		 * @var bool
		 */
		protected $_IsConsumed = false;
		/**
		 * The set of results (if any) for the dispatch.
		 * 
		 * @var array
		 */
		private $_Results = array();
		/**
		 * Whether or not the dispatch is valid.
		 * 
		 * @var bool
		 */
		protected $_IsValid = false;
		/**
		 * The DateTime when the dispatch was marked as valid.
		 * 
		 * @var \DateTime
		 */
		private $_CalledDateTime;

		/**
		 * If the dispatch is consumable, marks it as consumed.
		 * 
		 * @return bool True if the dispatch is both consumable and not already marked as consumed.
		 */
		public function Consume() {
			if ($this->_IsConsumable && !$this->_IsConsumed) {
				$this->_IsConsumed = true;

				return true;
			}

			return false;
		}

		/**
			* Returns a DateTime object representing the date and time
			* the dispatch was initialized, localized to UTC.
			* 
			* @return \DateTime DateTime representing moment when dispatch was marked valid.
			*/
		public function GetCalledDateTime() {
			return $this->_CalledDateTime;
		}

		/**
		 * Returns any results from the dispatch processing.
		 * 
		 * @return array|null Array of results if present, null otherwise.
		 */
		public function GetResults() {
			if (count($this->_Results) < 1) {
				return null;
			}

			return $this->_Results;
		}

		/**
		 * Method to initialize the dispatch with necessary information
		 * for processing.
		 * 
		 * @param mixed $Input Input information for processing.
		 */
		abstract public function Initialize($Input);

		/**
		 * Returns whether or not dispatch is consumable.
		 * 
		 * @return bool True if consumable, false if not.
		 */
		public function IsConsumable() {
			return $this->_IsConsumable;
		}

		/**
		 * Returns whether or not dispatch has been consumed.
		 * 
		 * @return bool True if consumed, false if not.
		 */
		public function IsConsumed() {
			return $this->_IsConsumed;
		}

		/**
		 * Returns whether or not dispatch is stateful.
		 * 
		 * @return bool True if stateful, false if not.
		 */
		public function IsStateful() {
			return $this->_IsStateful;
		}

		/**
		 * Returns whether or not dispatch is valid for processing.
		 * 
		 * @return bool True if valid, false if not.
		 */
		public function IsValid() {
			return $this->_IsValid;
		}

		/**
		 * Sets the dispatch as consumable.
		 * 
		 * @return \N2f\DispatchBase The current dispatch.
		 */
		protected function MakeConsumable() {
			$this->_IsConsumable = true;

			return $this;
		}

		/**
		 * Sets the dispatch as stateful.
		 * 
		 * @return \N2f\DispatchBase The current dispatch.
		 */
		protected function MakeStateful() {
			$this->_IsStateful = true;

			return $this;
		}

		/**
		 * Sets the dispatch as valid.
		 * 
		 * @return \N2f\DispatchBase The current dispatch.
		 */
		protected function MakeValid() {
			$this->_CalledDateTime = new \DateTime('now', new \DateTimeZone('UTC'));
			$this->_IsValid = true;

			return $this;
		}

		/**
		 * Returns the number of results.
		 * 
		 * @return int Number of results, if any.
		 */
		public function NumResults() {
			return count($this->_Results);
		}

		/**
		 * Sets a result for the dispatch. If dispatch
		 * is stateful, the result is added to the array.
		 * If the dispatch is not stateful, the result
		 * is set as the sole result.
		 * 
		 * @param mixed $Result The result to set for the dispatch.
		 * @return \N2f\DispatchBase The current dispatch.
		 */
		public function SetResult($Result) {
			if (!$this->_IsStateful) {
				$this->_Results = array($Result);
			} else {
				$this->_Results[] = $Result;
			}

			return $this;
		}
	}

<?php

	namespace N2f;

	/**
	 * Dispatch type for shutdown of execution.
	 *
	 * Dispatch object that provides basic information
	 * for shutdown information.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2016 Zibings.com
	 * @package N2F
	 */
	class ShutdownDispatch extends DispatchBase {
		/**
		 * Time that dispatch was initialized.
		 * 
		 * @var \DateTime
		 */
		protected $_EndDateTime;

		/**
		 * Creates a new instance of ShutdownDispatch.
		 * 
		 * @return void
		 */
		public function __construct() {
			return;
		}

		/**
		 * Initializes a ShutdownDispatch instance.
		 * 
		 * @param mixed $Input Unused initialization method, null is rquired.
		 * @return void
		 */
		public function Initialize($Input) {
			if ($Input !== null) {
				return;
			}

			$this->_EndDateTime = new \DateTime('now', new \DateTimeZone('UTC'));
			$this->MakeValid();

			return;
		}

		/**
		 * Returns the time the dispatch was initialized.
		 * 
		 * @return \DateTime Time the dispatch was initialized.
		 */
		public function GetEndDateTime() {
			return $this->_EndDateTime;
		}
	}

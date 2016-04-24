<?php

	namespace N2f;

	/**
	 * Dispatch type for configuration calls.
	 *
	 * Dispatch object that provides information
	 * when a config is started via CLI.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2016 Zibings.com
	 * @package N2F
	 */
	class ConfigDispatch extends CliDispatch {
		/**
		 * Whether or not the session is interactive.
		 * Interactive sessions do not set specific
		 * values via the command line arguments.
		 * 
		 * @var bool
		 */
		protected $_IsInteractive = true;
		/**
		 * Name of the extension being configured.
		 * 
		 * @var string
		 */
		protected $_Ext = '';

		/**
		 * Creates a new ConfigDispatch instance. Instance
		 * is consumable.
		 * 
		 * @return void
		 */
		public function __construct() {
			$this->MakeConsumable();

			return;
		}

		/**
		 * Returns the name of the extension being configured.
		 * 
		 * @return string String value of extension name.
		 */
		public function GetExt() {
			return $this->_Ext;
		}

		/**
		 * Initializes a ConfigDispatch instance. Must have
		 * either a 'argv' or 'ConsoleHelper' element as well
		 * as a command line parameter 'ext' to determine
		 * which extension is being configured.
		 * 
		 * @param array $Input Array of initialization information.
		 * @return void
		 */
		public function Initialize($Input) {
			parent::Initialize($Input);

			if (!$this->IsValid()) {
				return;
			}

			if ($this->_Ch->NumArgs() > 3) {
				$this->_IsInteractive = false;
			}

			if ($this->IsValid()) {
				if ($this->_Ch->HasArg('ext')) {
					$tmp = $this->_Ch->Parameters(true);
					$this->_Ext = $tmp['ext'];
				} else {
					$this->_IsValid = false;
				}
			}

			return;
		}

		/**
		 * Returns whether or not the config is interactive.
		 * Interactive configs are those without specific
		 * setting values supplied via command line.
		 * 
		 * @return bool True if interactive, false otherwise.
		 */
		public function IsInteractive() {
			return $this->_IsInteractive;
		}
	}

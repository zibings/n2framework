<?php

	namespace N2f;

	/**
	 * Dispatch type for extension calls.
	 * 
	 * Dispatch object that provides information
	 * when an extension management CLI call is made.
	 * 
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class ExtensionDispatch extends CliDispatch {
		/**
		 * The current action to perform.
		 * 
		 * @var string
		 */
		protected $_Action;
		/**
		 * The extension to perform the action upon.
		 * 
		 * @var string
		 */
		protected $_Ext;
		/**
		 * Instance of FileHelper.
		 * 
		 * @var \N2f\FileHelper
		 */
		protected $_Fh;

		/**
		 * Creates a new ExtensionDispatch instance.
		 * 
		 * @return void
		 */
		public function __construct() {
			return;
		}

		/**
		 * Initializes an ExtensionDispatch instance. Must
		 * include a 'ConsoleHelper' element.
		 * 
		 * @param array $Input Array of initialization information.
		 * @return void
		 */
		public function Initialize($Input) {
			if ($Input === null || !is_array($Input) || !array_key_exists('ConsoleHelper', $Input) || !array_key_exists('Config', $Input)) {
				return;
			}

			parent::Initialize($Input);

			if (!$this->IsValid()) {
				return;
			}

			$this->_IsValid = false;

			$Params = $Input['ConsoleHelper']->Parameters(true);
			$Cfg = (is_array($Input['Config'])) ? new Config($Input['Config']) : $Input['Config'];
			$this->_Fh = (array_key_exists('FileHelper', $Input)) ? $Input['FileHelper'] : new FileHelper();

			if (count($Params) != 4) {
				return;
			}

			$Keys = array_keys($Params);

      $this->_Action = $Keys[2];
      $this->_Ext = $Keys[3];

			if ($this->_Action != 'add' && $this->_Action != 'remove' && $this->_Action != 'create') {
				return;
			}

			if ($this->_Action != 'create' && !($this->_Fh->FolderExists($Cfg->ExtensionDirectory . "{$this->_Ext}") && $this->_Fh->FileExists($Cfg->ExtensionDirectory . "{$this->_Ext}/{$this->_Ext}.cfg"))) {
				return;
			}

			$this->MakeValid();

			return;
		}

		/**
		 * Retrieves the current requested action.
		 * 
		 * @return string Key for the requested action.
		 */
		public function GetAction() {
			return $this->_Action;
		}
		
		/**
		 * Retrieves the current extension to act upon.
		 * 
		 * @return string Name for the extension.
		 */
		public function GetExtension() {
			return $this->_Ext;
		}

		/**
		 * Retrieves the FileHelper supplied to the Dispatch.
		 * 
		 * @return \N2f\FileHelper
		 */
		public function GetFileHelper() {
			return $this->_Fh;
		}
	}

?>
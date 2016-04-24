<?php

	namespace N2f;

	/**
	 * Dispatch type for generation calls.
	 * 
	 * Dispatch object that provides information
	 * when a generate CLI call is made.
	 * 
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2016 Zibings.com
	 * @package N2F
	 */
	class GenerateDispatch extends CliDispatch {
		/**
		 * Instance of FileHelper.
		 * 
		 * @var \N2f\FileHelper
		 */
		protected $_Fh;

		/**
		 * Creates a new GenerateDispatch instance.
		 * 
		 * @return void
		 */
		public function __construct() {
			return;
		}

		/**
		 * Initialies a GenerateDispatch instance. Must include
		 * 'ConsoleHelper' element and optionmally an 'argv'
		 * element for the base CliDispatch. Only marked valid
		 * if the Cli parameters also include a 'type' element.
		 * 
		 * @param array $Input Array of input information.
		 * @return void
		 */
		public function Initialize($Input) {
			if ($Input === null || !is_array($Input) || !array_key_exists('ConsoleHelper', $Input)) {
				return;
			}

			parent::Initialize($Input);

			if (!$this->IsValid()) {
				return;
			}

			$this->_IsValid = false;

			if (!$this->_Ch->HasArg('type')) {
				return;
			}

			$this->_Fh = (array_key_exists('FileHelper', $Input) && $Input['FileHelper'] instanceof FileHelper) ? $Input['FileHelper'] : new FileHelper();
			$this->MakeValid();

			return;
		}

		/**
		 * Returns the type of entity that need generated.
		 * 
		 * @return string String value of entity to generate.
		 */
		public function GetEntityType() {
			$params = $this->_Ch->Parameters(true);

			return strtolower($params['type']);
		}

		/**
		 * Returns the FileHelper instance.
		 * 
		 * @return \N2f\FileHelper
		 */
		public function GetFileHelper() {
			return $this->_Fh;
		}
	}

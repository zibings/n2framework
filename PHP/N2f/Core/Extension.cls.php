<?php

	namespace N2f;

	/**
	 * Class that describes an extension.
	 *
	 * Class used to describe an extension for
	 * reference by the N2f class.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class Extension {
		/**
		 * The name of the extension.
		 * 
		 * @var string
		 */
		private $_Name;
		/**
		 * The author's name of the extension.
		 * 
		 * @var string
		 */
		private $_Author;
		/**
		 * The version of the extension.
		 * 
		 * @var string
		 */
		private $_Version;
		/**
		 * The base file of the extension.
		 * 
		 * @var string
		 */
		private $_BaseFile;
		/**
		 * Array of automatically included files.
		 * 
		 * @var array
		 */
		private $_AutoIncludes;

		/**
		 * Creates a new Extension instance.
		 * 
		 * @param string $Name Extension name value.
		 * @param string $Author Extension author value.
		 * @param string $Version Extension version value.
		 * @return void
		 */
		public function __construct($Name, $Author, $Version) {
			$this->_Name = $Name;
			$this->_Author = $Author;
			$this->_Version = $Version;
			$this->_BaseFile = $Name;
			$this->_AutoIncludes = array();

			return;
		}

		/**
		 * Retrieves the extension name.
		 * 
		 * @return string Extension name value.
		 */
		public function GetName() {
			return $this->_Name;
		}

		/**
		 * Retrieves the extension author.
		 * 
		 * @return string Extension author value.
		 */
		public function GetAuthor() {
			return $this->_Author;
		}

		/**
		 * Retreives the extension version.
		 * 
		 * @return string Extension version value.
		 */
		public function GetVersion() {
			return $this->_Version;
		}

		/**
		 * Retrieves the extension base file.
		 * 
		 * @return string Extension base file value.
		 */
		public function GetBaseFile() {
			return $this->_BaseFile;
		}

		/**
		 * Retrieves the extension auto includes.
		 * 
		 * @return array Extension auto include array.
		 */
		public function GetAutoIncludes() {
			return $this->_AutoIncludes;
		}

		/**
		 * Sets the extension base file.
		 * 
		 * @param string $BaseFile String value for extension base file.
		 * @return void
		 */
		public function SetBaseFile($BaseFile) {
			$this->_BaseFile = $BaseFile;

			return;
		}

		/**
		 * Sets the extension auto includes.
		 * 
		 * @param array $AutoIncludes Array of auto includes.
		 * @return void
		 */
		public function SetAutoIncludes(array $AutoIncludes) {
			if (count($AutoIncludes) < 1) {
				return;
			}

			$this->_AutoIncludes = $AutoIncludes;

			return;
		}
	}

?>
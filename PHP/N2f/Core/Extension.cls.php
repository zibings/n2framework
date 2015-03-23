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
		private $_Name;
		private $_Author;
		private $_Version;
		private $_BaseFile;
		private $_AutoIncludes;

		public function __construct($Name, $Author, $Version) {
			$this->_Name = $Name;
			$this->_Author = $Author;
			$this->_Version = $Version;
			$this->_BaseFile = $Name;
			$this->_AutoIncludes = array();

			return;
		}

		public function GetName() {
			return $this->_Name;
		}

		public function GetAuthor() {
			return $this->_Author;
		}

		public function GetVersion() {
			return $this->_Version;
		}

		public function GetBaseFile() {
			return $this->_BaseFile;
		}

		public function GetAutoIncludes() {
			return $this->_AutoIncludes;
		}

		public function SetBaseFile($BaseFile) {
			$this->_BaseFile = $BaseFile;

			return;
		}

		public function SetAutoIncludes(array $AutoIncludes) {
			if (count($AutoIncludes) < 1) {
				return;
			}

			$this->_AutoIncludes = $AutoIncludes;

			return;
		}
	}

?>
<?php

	namespace N2f;

	/**
	 * Class for auto loading namespaces.
	 *
	 * This class maps namespaces to folders and/or files
	 * using the PSR-0 and PSR-4 standards.
	 *
	 * @version 1.0
	 * @author Chris Butcher <c.butcher@hotmail.com>
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class AutoLoader {
		/**
		 * An associative array where the key is a namespace prefix and the value
		 * is an array of base directories for classes in that namespace.
		 *
		 * @var array
		 */
		protected static $_namespaces = array();

		/**
		 * An associative array where the key is the class name, and the value
		 * is a specific file.
		 *
		 * @var array
		 */
		protected static $_mapped = array();

		/**
		 * Register the SPL autoload method.
		 *
		 * @return bool
		 */
		public function Register() {
			return spl_autoload_register(array($this, 'LoadClass'));
		}

		/**
		 * Unregister the SPL autoload method.
		 *
		 * @return bool
		 */
		public function Unregister() {
			return spl_autoload_unregister(array($this, 'LoadClass'));
		}

		/**
		 * Map a namespace to a specific file.
		 *
		 * @param string $namespace
		 * @param string $file
		 *
		 * @return AutoLoader
		 */
		public function AddClassMap($namespace, $file) {
			if (empty($namespace) || !strstr($namespace, '\\')) {
				return $this;
			}

			if (!file_exists($file)) {
				return $this;
			}

			self::$_mapped[$namespace] = $file;

			return $this;
		}

		/**
		 * Map a namespace to a specific directory.
		 *
		 * @param string $namespace
		 * @param string $folder
		 * @param bool   $prepend
		 *
		 * @return AutoLoader
		 */
		public function AddNamespace($namespace, $folder, $prepend = false) {
			if (empty($namespace)) {
				return $this;
			}

			if (!array_key_exists($namespace, self::$_namespaces)) {
				self::$_namespaces[$namespace] = array();
			}

			$namespace = trim($namespace, '\\');
			$folder = trim($folder, '.'.DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $namespace);

			if ($prepend) {
				array_unshift(self::$_namespaces[$namespace], $folder);
			} else {
				array_push(self::$_namespaces[$namespace], $folder);
			}

			return $this;
		}

		public function LoadClass($class) {
			$class = trim($class, '\\');

			// Attempt to load the file from a pre-determined namespace mapping.
			if (($file = $this->LoadMappedClass($class)) !== false) {
				return $this->LoadFile($file);
			}

			// Separate the filename from the namespace
			$pos = strrpos($class, '\\');
			$filename = substr($class, $pos + 1);
			$namespace = substr($class, 0, strrpos($class, '\\'.$filename));

			// Attempt to load the file using the PSR-4 standards
			if (($file = $this->FindFileByPsr4($namespace, $filename)) !== false) {
				return $this->LoadFile($file);
			}

			// Attempt to load the file using the PSR-0 standards
			if (($file = $this->FindFileByPsr0($namespace, $filename)) !== false) {
				return $this->LoadFile($file);
			}

			return false;
		}

		public function LoadMappedClass($class) {
			if (!array_key_exists($class, self::$_mapped)) {
				return false;
			}

			return self::$_mapped[$class];
		}

		public function FindFileByPsr0($namespace, $filename) {
			/* The main difference between PSR-4 and PSR-0, is that PSR-0 replaces
			 * the underscore with a namespace separator.
			 */
			$filename = str_replace('_', '\\', $filename);

			$file = $this->FindFileByPsr4($namespace, $filename);

			return $file;
		}

		public function FindFileByPsr4($namespace, $filename) {
			if (!array_key_exists($namespace, self::$_namespaces)) {
				return false;
			}

			$return = false;

			for ($i = 0; $i < count(self::$_namespaces[$namespace]); $i++) {
				$folder = self::$_namespaces[$namespace][$i];
				$file = $folder . DIRECTORY_SEPARATOR . $filename.'.php';

				if (file_exists($file)) {
					$return = $file;
					break;
				}
			}

			return $return;
		}

		protected function LoadFile($file) {
			if (!file_exists($file)) {
				return false;
			}

			require_once($file);

			return true;
		}
	}

?>
<?php

	namespace N2f;

	/**
	 * Class for auto loading namespaces.
	 *
	 * This class maps namespaces to folders and/or files
	 * using the PSR-0 and PSR-4 standards.
	 *
	 * @version 1.0
	 * @author Chris Butcher
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
		protected static $namespaces = array();

		/**
		 * An associative array where the key is the fully qualified class name, and the value
		 * is a specific file.
		 *
		 * @var array
		 */
		protected static $mapped = array();

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
		 * Map a class to a specific file.
		 *
		 * @param string $class   The fully qualified name of the class eg( My\Awesome\Class )
		 * @param string $file    The location of the file on the filesystem eg( /vendors/my/awesome/Class.php )
		 *
		 * @return AutoLoader
		 */
		public function AddClassMap($class, $file) {
			if (empty($class) || !strstr($class, '\\')) {
				return $this;
			}

			if (!file_exists($file)) {
				return $this;
			}

			self::$mapped[$class] = $file;

			return $this;
		}

		/**
		 * Maps a namespace to a specific folder.
		 * Namespaces can be mapped to multiple folders.
		 *
		 * @param string $namespace   The namespace that is going to be mapped.
		 * @param string $folder      Location of where to look for the files
		 * @param bool   $prepend     Gives the mapped folder priority
		 *
		 * @return AutoLoader
		 */
		public function AddNamespace($namespace, $folder, $prepend = false) {
			if (empty($namespace)) {
				return $this;
			}

			if (!array_key_exists($namespace, self::$namespaces)) {
				self::$namespaces[$namespace] = array();
			}

			$namespace = trim($namespace, '\\');
			$folder = trim($folder, '.'.DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $namespace);

			if ($prepend) {
				array_unshift(self::$namespaces[$namespace], $folder);
			} else {
				array_push(self::$namespaces[$namespace], $folder);
			}

			return $this;
		}

		/**
		 * Attempt to load a file by using the following methods in order:
		 *      1. Using a mapped class
		 *      2. Using PSR-4 auto-loading standards
		 *      3. Using PSR-0 auto-loading standards
		 *
		 * This method will return false if all three methods fail to return a file location.
		 *
		 * @param string $class   The fully qualified class name including namespace eg( My\Super\Awesome\Class )
		 *
		 * @return bool
		 */
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

		/**
		 * Attempt to load a class that is mapped to a specific location.
		 * When there is no mapped class, then false will be returned.
		 *
		 * @param string $class   The fully qualified class name including namespace eg( My\Super\Awesome\Class )
		 *
		 * @return bool
		 */
		public function LoadMappedClass($class) {
			if (!array_key_exists($class, self::$mapped)) {
				return false;
			}

			return self::$mapped[$class];
		}

		/**
		 * Attempt to locate the the file using PSR-0 auto-loading standards.
		 * This method will return the full path of the files location, or false if nothing was found.
		 *
		 * For more information about PSR-0:
		 *      http://www.php-fig.org/psr/psr-0/
		 *      PSR-0 has been deprecated as of October 21st, 2014
		 *
		 * @param string $namespace   The namespace, excluding the class name eg( My\Super\Awesome )
		 * @param string $filename    The class name, which should match the physical files name eg ( Class.php )
		 *
		 * @return bool|string
		 */
		public function FindFileByPsr0($namespace, $filename) {
			/* The main difference between PSR-4 and PSR-0, is that PSR-0 replaces
			 * the underscore with a namespace separator.
			 */
			$filename = str_replace('_', '\\', $filename);

			$file = $this->FindFileByPsr4($namespace, $filename);

			return $file;
		}

		/**
		 * Attempt to locate the the file using PSR-4 auto-loading standards.
		 * This method will return the full path of the files location, or false if nothing was found.
		 *
		 * For more information about PSR-4:
		 *      http://www.php-fig.org/psr/psr-4/
		 *
		 * @param string $namespace   The namespace, excluding the class name eg( My\Super\Awesome )
		 * @param string $filename    The class name, which should match the physical files name eg ( Class.php )
		 *
		 * @return bool|string
		 */
		public function FindFileByPsr4($namespace, $filename) {
			if (!array_key_exists($namespace, self::$namespaces)) {
				return false;
			}

			$return = false;

			for ($i = 0; $i < count(self::$namespaces[$namespace]); $i++) {
				$folder = self::$namespaces[$namespace][$i];
				$file = $folder . DIRECTORY_SEPARATOR . $filename.'.php';

				if (file_exists($file)) {
					$return = $file;
					break;
				}
			}

			return $return;
		}

		/**
		 * Attempts to load the specified file.
		 *
		 * @param string $file   The full path and filename to the files location. eg ( /vendors/my/awesome/Class.php )
		 *
		 * @return bool
		 */
		protected function LoadFile($file) {
			if (!file_exists($file)) {
				return false;
			}

			require_once($file);

			return true;
		}
	}

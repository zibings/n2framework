<?php

	namespace N2f;

	/**
	 * Class for comparing project versions.
	 *
	 * This class is instantiated with an owning version that is then compared
	 * against other versions. Acceptable wildcards are ' * ' and ' x ', both of
	 * which represent a range of zero to nine.
	 *
	 * @version 1.0
	 * @author Chris Butcher
	 * @copyright 2014-2016 Zibings.com
	 * @package N2F
	 */
	class VersionHelper {
		/**
		 * The full version that we are checking against.
		 *
		 * @var string
		 */
		protected $full;

		/**
		 * The major release number.
		 *
		 * @var int
		 */
		protected $major;

		/**
		 * The minor release number.
		 *
		 * @var int
		 */
		protected $minor;

		/**
		 * The patch number.
		 *
		 * @var int
		 */
		protected $patch;

		/**
		 * List of all the wildcards that can be used.
		 *
		 * @var array
		 */
		protected $wildcards = array('*', 'x');

		/**
		 * Instantiate a new version comparison object.
		 *
		 * @param string|float|int $version   The owning version of this object.
		 * @return void
		 */
		public function __construct($version) {
			$this->setVersion($version);

			return;
		}

		/**
		 * Sets (or resets) the internal version to use
		 * for comparison.
		 * 
		 * @param string|float|int $version The owning version for this object.
		 * @return void
		 */
		public function setVersion($version) {
			$this->full = (string) $version;

			$version = $this->separate($this->full);

			$this->major = $version['major'];
			$this->minor = $version['minor'];
			$this->patch = $version['patch'];

			return;
		}

		/**
		 * Check to see if the owning version is greater than the supplied version.
		 *
		 * @param string|float|int $version
		 *
		 * @return bool
		 */
		public function greaterThan($version) {
			return $this->compare($version) === 1;
		}

		/**
		 * Check to see if the owning version is greater than or equal to the supplied version.
		 *
		 * @param string|float|int $version
		 *
		 * @return bool
		 */
		public function greaterThanEqualTo($version) {
			$ret = $this->compare($version);

			return ($ret === 1 || $ret === 0);
		}

		/**
		 * Check to see if the owning version is less than the supplied version.
		 *
		 * @param string|float|int $version
		 *
		 * @return bool
		 */
		public function lessThan($version) {
			return $this->compare($version) === -1;
		}

		/**
		 * Check to see if the owning version is less than or equal to the supplied version.
		 *
		 * @param string|float|int $version
		 *
		 * @return bool
		 */
		public function lessThanEqualTo($version) {
			$ret = $this->compare($version);

			return ($ret === -1 || $ret === 0);
		}

		/**
		 * Check to see if the owning version is equal to the supplied version.
		 *
		 * @param string|float|int $version
		 *
		 * @return bool
		 */
		public function equalTo($version) {
			return $this->compare($version) === 0;
		}

		/**
		 * Compares the current version against the supplied version.
		 *
		 * Returns 1  for greater than
		 * Returns 0  for equal too
		 * Returns -1 for less than
		 *
		 * @param string|int|float $version
		 *
		 * @return int
		 */
		public function compare($version) {
			$version = $this->separate($version);

			if ($this->isWildcard($this->major) || $this->isWildcard($version['major'])) {
				return 0;
			} else if ($this->major > $version['major']) {
				return 1;
			} else if ($this->major < $version['major']) {
				return -1;
			}

			if ($this->isWildcard($this->minor) || $this->isWildcard($version['minor'])) {
				return 0;
			} else if ($this->minor > $version['minor']) {
				return 1;
			} else if ($this->minor < $version['minor']) {
				return -1;
			}

			if ($this->isWildcard($this->patch) || $this->isWildcard($version['patch'])) {
				return 0;
			} else if ($this->patch > $version['patch']) {
				return 1;
			} else if ($this->patch < $version['patch']) {
				return -1;
			}

			return 0;
		}

		/**
		 * Separate the version into major, minor, and patch.
		 *
		 * @example $version = array('major' => 1, 'minor' => 5, 'patch' => 0);
		 *
		 * @param int|float|string $version
		 *
		 * @return array
		 */
		protected function separate($version) {
			if (is_array($version) && in_array(array('major', 'minor', 'patch'), $version)) {
				return $version;
			}

			if (!is_string($version) && !is_float($version) && !is_int($version)) {
				$version = '0.0.0';
			}

			$version = (string) $version;
			$version = explode('.', $version);

			$ver = array();
			$ver['major'] = (int) isset($version[0]) ? $version[0] : 0;
			$ver['minor'] = (int) isset($version[1]) ? $version[1] : 0;
			$ver['patch'] = (int) isset($version[2]) ? $version[2] : 0;

			return $ver;
		}

		/**
		 * Check to see if the segment is a wild card.
		 *
		 * @param string $segment
		 *
		 * @return bool
		 */
		protected function isWildcard($segment) {
			return in_array($segment, $this->wildcards);
		}
	}

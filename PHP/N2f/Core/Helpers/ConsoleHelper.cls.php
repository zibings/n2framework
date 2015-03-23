<?php

	namespace N2f;

	/**
	 * ConsoleHelper class to aid with CLI interactions.
	 * 
	 * A class to simplify the work required to smoothly
	 * interact with the console.
	 * 
	 * @version 1.0 
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class ConsoleHelper {
		private $argInfo = array();
		private $isWindows = false;
		private $forceCli = false;

		public function __construct($argc = null, $argv = null, $columns = 80, $forceCli = false) {
			if ($argc === null || $argv === null) {
				$this->argInfo = null;
			} else {
				$this->argInfo['argc'] = $argc;
				$this->argInfo['argv'] = $argv;

				if ($argc > 0) {
					$this->argInfo['arga'] = \env_parse_params($argv, true);
				}
			}

			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$this->isWindows = true;
			}

			$this->forceCli = $forceCli;

			return;
		}

		public function CompareArgAt($index, $value, $caseInsensitive = false) {
			return ($this->argInfo['argc'] > $index && (($caseInsensitive) ? strtolower($this->argInfo['argv'][$index]) == strtolower($value) : $this->argInfo['argv'][$index] == $value));
		}

		public function Get($characters = 1) {
			if ($characters < 1) {
				return null;
			}

			return trim(fread(STDIN, $characters));
		}

		public function GetLine() {
			return trim(fgets(STDIN));
		}

		public function HasArg($key) {
			return $this->argInfo['argc'] > 0 && array_key_exists($key, $this->argInfo['arga']);
		}

		public function IsCLI() {
			return $this->forceCli || php_sapi_name() == 'cli';
		}

		public function NumArgs() {
			return $this->argInfo['argc'];
		}

		public function Parameters($AsAssociative = false) {
			return ($AsAssociative) ? $this->argInfo['arga'] : $this->argInfo['argv'];
		}

		public function Put($buf) {
			echo($buf);

			return;
		}

		public function PutLine($buf = null) {
			if ($buf !== null) {
				echo($buf);
			}

			echo("\n");

			return;
		}
	}

?>
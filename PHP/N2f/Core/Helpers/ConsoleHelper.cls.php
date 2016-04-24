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
	 * @copyright 2014-2016 Zibings.com
	 * @package N2F
	 */
	class ConsoleHelper {
		/**
		 * Collection of arguments.
		 * 
		 * @var array
		 */
		private $argInfo = array();
		/**
		 * Whether or not executing environment
		 * is Windows based.
		 * 
		 * @var bool
		 */
		private $isWindows = false;
		/**
		 * Allows overriding instances to think
		 * they were called from CLI PHP.
		 * 
		 * @var bool
		 */
		private $forceCli = false;

		/**
		 * Creates a new ConsoleHelper instance.
		 * 
		 * @param int $argc Number of arguments.
		 * @param array $argv Argument collection.
		 * @param bool $forceCli Force instance to emulate CLI mode.
		 * @return void
		 */
		public function __construct($argc = null, array $argv = null, $forceCli = false) {
			if ($argc === null || $argv === null) {
				$this->argInfo = null;
			} else {
				$this->argInfo['argc'] = $argc;
				$this->argInfo['argv'] = $argv;

				if ($argc > 0) {
					$this->argInfo['arga'] = \env_parse_params($argv);
				}
			}

			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$this->isWindows = true;
			}

			$this->forceCli = $forceCli;

			return;
		}

		/**
		 * Compares an argument by key optionally
		 * without case sensitivity. May return
		 * inaccurate results against toggle type
		 * arguments.
		 * 
		 * @param string $key String value of key in argument list.
		 * @param string $value Value to compare against.
		 * @param bool $caseInsensitive Enable case-insensitive comparison.
		 * @return bool True if the values are the same, false otherwise.
		 */
		public function CompareArg($key, $value, $caseInsensitive = false) {
			return ($caseInsensitive) ? strtolower($this->argInfo['arga'][$key]) == strtolower($value) : $this->argInfo['arga'] == $value;
		}

		/**
		 * Compares an argument at the given index
		 * optionally without case sensitivity.
		 * Returns false if index is out of bounds.
		 * 
		 * @param int $index Integer value for argument offset.
		 * @param string $value String value to compare against.
		 * @param bool $caseInsensitive Enable case-insensitive comparison.
		 * @return bool True if the values are the same, false otherwise.
		 */
		public function CompareArgAt($index, $value, $caseInsensitive = false) {
			return ($this->argInfo['argc'] > $index && (($caseInsensitive) ? strtolower($this->argInfo['argv'][$index]) == strtolower($value) : $this->argInfo['argv'][$index] == $value));
		}

		/**
		 * Retrieves $characters from STDIN.
		 * 
		 * @param int $characters Number of characters to read from STDIN.
		 * @return string|null Trimmed string up to $characters long, or null if $characters is less than 1.
		 */
		public function Get($characters = 1) {
			if ($characters < 1) {
				return null;
			}

			return trim(fread(STDIN, $characters));
		}

		/**
		 * Retrieves an entire line from STDIN.
		 * 
		 * @return string Trimmed string from STDIN.
		 */
		public function GetLine() {
			return trim(fgets(STDIN));
		}

		/**
		 * Queries a user repeatedly for input.
		 * 
		 * @param string $Query Base prompt, sans-colon.
		 * @param mixed $DefaultValue Default value for input, provide null if not present.
		 * @param string $ErrorMessage Message to display when input not provided correctly.
		 * @param int $MaxTries Maximum number of attempts a user can make before the process bails out.
		 * @param callable $Validation An optional method or function to provide boolean validation of input.
		 * @param callable $Sanitation An optional method or function to provide sanitation of the validated input.
		 * @return \N2f\ReturnHelper A ReturnHelper instance with extra state information.
		 */
		public function GetQueriedInput($Query, $DefaultValue, $ErrorMessage, $MaxTries = 5, $Validation = null, $Sanitation = null) {
			$Ret = new ReturnHelper();
			$Prompt = $Query;

			if ($DefaultValue !== null) {
				$Prompt .= " [{$DefaultValue}]";
			}

			$Prompt .= ": ";

			if ($Validation === null) {
				$Validation = function ($Value) { return !empty(trim($Value)); };
			}

			if ($Sanitation === null) {
				$Sanitation = function ($Value) { return trim($Value); };
			}

			$Attempts = 0;

			while (true) {
				$this->Put($Prompt);
				$Val = $this->GetLine();

				if (empty($Val) && $DefaultValue !== null) {
					$Ret->SetGud();
					$Ret->SetResult($DefaultValue);

					break;
				}

				if ($Validation($Val)) {
					$Sanitized = $Sanitation($Val);
					$Ret->SetGud();

					if ($Sanitized instanceof ReturnHelper) {
						$Ret = $Sanitized;
					} else {
						$Ret->SetResult($Sanitized);
					}

					break;
				} else {
					$this->PutLine($ErrorMessage);
					$Attempts++;

					if ($Attempts == $MaxTries) {
						$Ret->SetMessage("Exceeded maximum number of attempts.");

						break;
					}
				}
			}

			return $Ret;
		}

		/**
		 * Checks if the given key exists in the argument list,
		 * optionally without case sensitivity.
		 * 
		 * @param string $key Key name to check in argument list.
		 * @param bool $caseInsensitive Enable case-insensitive comparison.
		 * @return bool True if key is found in argument list, false if not.
		 */
		public function HasArg($key, $caseInsensitive = false) {
			if ($this->argInfo['argc'] < 1) {
				return false;
			}

			if ($caseInsensitive) {
				foreach (array_keys($this->argInfo['arga']) as $k) {
					if (strtolower($k) == strtolower($key)) {
						return true;
					}
				}

				return false;
			}

			return array_key_exists($key, $this->argInfo['arga']);
		}

		/**
		 * Returns whether or not PHP invocation is via CLI
		 * or invocation is emulating CLI.
		 * 
		 * @return bool True if called from CLI or emulating CLI, false otherwise.
		 */
		public function IsCLI() {
			return $this->forceCli || php_sapi_name() == 'cli';
		}

		/**
		 * Returns whether or not PHP invocation is via CLI
		 * and ignores forced CLI mode.
		 * 
		 * @return bool True if called from CLI, false otherwise.
		 */
		public function IsNaturalCLI() {
			return php_sapi_name() == 'cli';
		}

		/**
		 * Returns the number of arguments.
		 * 
		 * @return int Number of arguments supplied to the instance.
		 */
		public function NumArgs() {
			return $this->argInfo['argc'];
		}

		/**
		 * Returns the argument collection, either
		 * as-received by the instance or as an
		 * associative array.
		 * 
		 * @param bool $AsAssociative Enables returning list as an associative array.
		 * @return array Associative or regular array of argument list.
		 */
		public function Parameters($AsAssociative = false) {
			return ($AsAssociative) ? $this->argInfo['arga'] : $this->argInfo['argv'];
		}

		/**
		 * Outputs the buffer to STDIN.
		 * 
		 * @param string $buf Buffer to output.
		 * @return void
		 */
		public function Put($buf) {
			echo($buf);

			return;
		}

		/**
		 * Outputs the buffer followed by a newline
		 * to STDIN.
		 * 
		 * @param string $buf Buffer to output.
		 * @return void
		 */
		public function PutLine($buf = null) {
			if ($buf !== null) {
				echo($buf);
			}

			echo("\n");

			return;
		}
	}

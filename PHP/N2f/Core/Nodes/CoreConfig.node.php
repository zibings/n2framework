<?php

	namespace N2f;

	/**
	 * Node for managing CLI configuration process.
	 *
	 * Node that manages the CLI configuration process
	 * for the N2f class and other base systems.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class CoreConfig extends NodeBase {
		/**
		 * Creates a new CoreConfig instance.
		 * 
		 * @return void
		 */
		public function __construct() {
			$this->SetKey('N2f-CoreConfig')->SetVersion('1.0');

			return;
		}

		/**
		 * Processes a CliDispatch from a sender.
		 * 
		 * @param mixed $Sender Sender value.
		 * @param \N2f\DispatchBase $Dispatch The DispatchBase instance for the chain, must be a CliDispatch.
		 * @return void
		 */
		public function Process($Sender, DispatchBase &$Dispatch) {
			if (!($Dispatch instanceof CliDispatch)) {
				return;
			}

			/** @var CliDispatch $Dispatch */

			$Env = $Dispatch->GetEnvParameters();
			$Argv = $Dispatch->GetAssocParameters();
			$relDir = (array_key_exists('relDir', $Env)) ? $Env['relDir'] : N2F_REL_DIR;

			$Ch = new ConsoleHelper();

			if (!$Ch->IsNaturalCLI()) {
				return;
			}

			$Fh = new FileHelper($relDir);
			$Jh = new JsonHelper();
			$D = array(
				'version' => N2F_VERSION,
				'timezone' => N2fStrings::CfgTimezoneDefault,
				'locale' => N2fStrings::CfgLocaleDefault,
				'charset' => N2fStrings::CfgCharsetDefault,
				'hash' => $this->GenerateHash(),
				'extension_dir' => N2fStrings::CfgExtensionDirDefault,
				'logger' => array(
					'log_level' => N2fStrings::CfgLogLevelDefault,
					'dump_logs' => true
				),
				'extensions' => array(
					'SampleExtension'
				)
			);

			if ($Fh->FileExists(N2fStrings::DirIncludes . "N2f.cfg")) {
				$Df = $Jh->DecodeAssoc($Fh->GetContents(N2fStrings::DirIncludes . "N2f.cfg"));

				foreach ($Df as $key => $val) {
					$D[$key] = $val;
				}
			}

			if (count($Argv) > 2) {
				$Ch->PutLine();

				foreach ($Argv as $Key => $Val) {
					switch (strtolower($Key)) {
						case 'timezone':
							if (!empty($Val) && $this->ValidateTimezone($Val)) {
								$D['timezone'] = $Val;
								$Ch->PutLine("System timezone successfully set.");
							} else {
								$Ch->PutLine("Invalid timezone, please try again.");
							}

							break;
						case 'locale':
							if (!empty($Val) && $this->ValidateLocale($Val)) {
								$D['locale'] = $Val;
								$Ch->PutLine("System language successfully set.");
							} else {
								$Ch->PutLine("Invalid system language, please try again.");
							}

							break;
						case 'charset':
							if (!empty($Val)) {
								$D['charset'] = $Val;
								$Ch->PutLine("System charset successfully set.");
							} else {
								$Ch->PutLine("Invalid charset, please try again.");
							}

							break;
						case 'log_level':
							$ReportingLevel = strtoupper($Val);

							if (Logger::ValidLevel($ReportingLevel)) {
								$D['logger']['log_level'] = $ReportingLevel;
								$Ch->PutLine("System log level successfully set.");
							} else {
								$Ch->PutLine("Invalid log level, please try again.");
							}

							break;
						case 'dump_logs':
							$DumpDebug = strtolower($Val);

							if ($DumpDebug == 'y' || $DumpDebug == 'yes') {
								$D['logger']['dump_logs'] = true;
								$Ch->PutLine("Log dumps successfully set.");
							} else if ($DumpDebug == 'n' || $DumpDebug == 'no') {
								$D['logger']['dump_logs'] = false;
								$Ch->PutLine("Log dumps successfully set.");
							} else {
								$Ch->PutLine("Invalid value for 'dump_logs'!");
							}

							break;
						case 'extension_dir':
							if ($Fh->FolderExists($Val)) {
								$D['extension_dir'] = (substr($Val, -1) == '/') ? $Val : $Val . '/';
								$Ch->PutLine("Extension directory successfully set.");
							} else {
								$Ch->PutLine("Invalid extension directory provided, does not exist.");
							}

							break;
					}
				}
			} else {
				$Ch->PutLine();
				$Ch->PutLine("Answer the following prompts to create/update your instance configuration.");
				$Ch->PutLine();

				while (true) {
					$Ch->Put("System Timezone [" . $D['timezone'] . "]: ");
					$Timezone = $Ch->GetLine();

					if (!empty($Timezone)) {
						if ($this->ValidateTimezone($Timezone)) {
							$D['timezone'] = $Timezone;

							break;
						} else {
							$Ch->PutLine("Invalid timezone!");
						}
					} else {
						break;
					}
				}

				while (true) {
					$Ch->Put("System Locale [" . $D['locale'] . "]: ");
					$Syslang = $Ch->GetLine();

					if (!empty($Syslang)) {
						if ($this->ValidateLocale($Syslang)) {
							$D['locale'] = $Syslang;

							break;
						} else {
							$Ch->PutLine("Invalid locale!");
						}
					} else {
						break;
					}
				}

				$Ch->Put("System Charset [" . $D['charset'] . "]: ");
				$Charset = $Ch->GetLine();

				if (!empty($Charset)) {
					$D['charset'] = $Charset;
				}

				while (true) {
					$Ch->Put("Extension Directory [" . $D['extension_dir'] . "]: ");
					$ExtDir = $Ch->GetLine();

					if (!empty($ExtDir)) {
						if ($Fh->FolderExists($ExtDir)) {
							$D['extension_dir'] = (substr($ExtDir, -1) == '/') ? $ExtDir : $ExtDir . '/';

							break;
						} else {
							$Ch->PutLine("Invalid extension directory, does not exist!");
						}
					} else {
						break;
					}
				}

				while (true) {
					$Ch->Put("Logging Level [" . $D['logger']['log_level'] . "]: ");
					$ReportingLevel = $Ch->GetLine();

					if (!empty($ReportingLevel)) {
						$ReportingLevel = strtoupper($ReportingLevel);

						if (Logger::ValidLevel($ReportingLevel)) {
							$D['logger']['log_level'] = $ReportingLevel;

							break;
						} else {
							$Ch->PutLine("Invalid log level!");
						}
					} else {
						break;
					}
				}

				while (true) {
					$Ch->Put("Dump Logs [" . (($D['logger']['dump_logs']) ? 'Y/n' : 'y/N') . "]: ");
					$DumpDebug = $Ch->GetLine();

					if (!empty($DumpDebug)) {
						$DumpDebug = strtolower($DumpDebug);

						if ($DumpDebug == 'y') {
							$D['logger']['dump_logs'] = true;
						} else if ($DumpDebug == 'n') {
							$D['logger']['dump_logs'] = false;
						} else {
							$Ch->PutLine("Invalid value for 'dump_logs'!");

							continue;
						}

						break;
					} else {
						break;
					}
				}
			}

			$Fh->PutContents(N2fStrings::DirIncludes . "N2f.cfg", $Jh->EncodePretty($D));
			$Ch->PutLine();
			$Ch->PutLine("Configuration file has been successfully saved.");

			$Dispatch->Consume();

			return;
		}

		/**
		 * Method to generate a new hash using the
		 * built-in env_get_guid function and a sha1
		 * value.
		 * 
		 * @return string String value of generated hash.
		 */
		protected function GenerateHash() {
			return sha1(\env_get_guid());
		}

		/**
		 * Determines if the given timezone is valid.
		 * 
		 * @param string $Timezone Timezone to validate.
		 * @return bool True if included in system list, false otherwise.
		 */
		protected function ValidateTimezone($Timezone) {
			$zones = \DateTimeZone::listAbbreviations();

			foreach (array_values($zones) as $zone) {
				foreach (array_values($zone) as $z) {
					if ($z['timezone_id'] == $Timezone) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Determines if the given locale is valid.
		 * 
		 * @param string $Locale String value of locale abbreviation to validate.
		 * @return bool True if included in locale list, false otherwise.
		 */
		protected function ValidateLocale($Locale) {
			foreach (array_values(\env_get_locales()) as $loc) {
				if (strtolower($loc) == strtolower($Locale)) {					
					return true;
				}
			}

			return false;
		}
	}

?>
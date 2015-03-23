<?php

	namespace N2f;

	/**
	 * Class for managing system configuration.
	 * 
	 * Class used to hold all system configuration
	 * values for the N2f class.
	 * 
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class Config {
		public $Charset;
		public $Logger;
		public $Extensions;
		public $Hash;
		public $Locale;
		public $Timezone;

		public function __construct(array $config = null) {
			if ($config === null || count($config) < 1) {
				$this->Charset = 'utf8';
				$this->Logger = new LoggerConfig();
				$this->Extensions = array();
				$this->Hash = '';
				$this->Locale = 'en-US';
				$this->Timezone = 'America/New_York';
			} else {
				$this->Charset = (array_key_exists('charset', $config)) ? $config['charset'] : 'utf8';
				$this->Logger = (array_key_exists('logger', $config) && is_array($config['logger'])) ? new LoggerConfig($config['logger']) : new LoggerConfig();
				$this->Extensions = (array_key_exists('extensions', $config) && is_array($config['extensions'])) ? $config['extensions'] : array();
				$this->Hash = (array_key_exists('hash', $config)) ? $config['hash'] : '';
				$this->Locale = (array_key_exists('locale', $config)) ? $config['locale'] : 'en-US';
				$this->Timezone = (array_key_exists('timezone', $config)) ? $config['timezone'] : 'America/New_York';
			}

			return;
		}
	}

	/**
	 * Class for managing Logger configuration.
	 * 
	 * Class used to hold all logger configuration
	 * values.
	 * 
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class LoggerConfig {
		public $DumpLogs;
		public $LogLevel;

		public function __construct(array $config = null) {
			if ($config === null || count($config) < 1) {
				$this->DumpLogs = false;
				$this->LogLevel = N2F_LOG_ERROR;
			} else {
				$this->DumpLogs = (array_key_exists('dump_logs', $config) && $config['dump_logs']) ? true : false;
				$this->LogLevel = (array_key_exists('log_level', $config)) ? $config['log_level'] : N2F_LOG_ERROR;
			}

			return $this;
		}

		public function SetDumpLogs($Value) {
			if (is_bool($Value)) {
				$this->DumpLogs = $Value;
			}

			return $this;
		}

		public function SetLogLevel($Level) {
			if (!Logger::ValidLevel($Level)) {
				return $this;
			}

			$this->LogLevel = $Level;

			return $this;
		}
	}

?>
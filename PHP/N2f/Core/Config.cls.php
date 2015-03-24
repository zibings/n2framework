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
		/**
		 * The current charset for this configuration.
		 * 
		 * @var string
		 */
		public $Charset;
		/**
		 * The current logger config for this configuration.
		 * 
		 * @var \N2f\LoggerConfig
		 */
		public $Logger;
		/**
		 * The current collection of extensions for this configuration.
		 * 
		 * @var array
		 */
		public $Extensions;
		/**
		 * The current hash for this configuration.
		 * 
		 * @var string
		 */
		public $Hash;
		/**
		 * The current locale for this configuration.
		 * 
		 * @var string
		 */
		public $Locale;
		/**
		 * The current timezone for this configuration.
		 * 
		 * @var string
		 */
		public $Timezone;

		/**
		 * Creates a new Config instance.
		 * 
		 * @param array $config Optional array of configuration values.
		 * @return void
		 */
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
		/**
		 * Whether or not logs should be dumped.
		 * 
		 * @var bool
		 */
		public $DumpLogs;
		/**
		 * The current log level for this instance.
		 * 
		 * @var int
		 */
		public $LogLevel;

		/**
		 * Creates a new LoggerConfig instance.
		 * 
		 * @param array $config Optional array of configuration values.
		 * @return void
		 */
		public function __construct(array $config = null) {
			if ($config === null || count($config) < 1) {
				$this->DumpLogs = false;
				$this->LogLevel = N2F_LOG_ERROR;
			} else {
				$this->DumpLogs = (array_key_exists('dump_logs', $config) && $config['dump_logs']) ? true : false;
				$this->LogLevel = (array_key_exists('log_level', $config)) ? $config['log_level'] : N2F_LOG_ERROR;
			}

			return;
		}
	}

?>
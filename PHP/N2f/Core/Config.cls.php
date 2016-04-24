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
	 * @copyright 2014-2016 Zibings.com
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
		 * The location of the directory where extensions
		 * are stored.
		 * 
		 * @var string
		 */
		public $ExtensionDirectory;

		/**
		 * Creates a new Config instance.
		 * 
		 * @param array $config Optional array of configuration values.
		 * @return void
		 */
		public function __construct(array $config = null) {
			if ($config === null || count($config) < 1) {
				$this->Charset = N2fStrings::CfgCharsetDefault;
				$this->Logger = new LoggerConfig();
				$this->Extensions = array();
				$this->Hash = '';
				$this->Locale = N2fStrings::CfgLocaleDefault;
				$this->Timezone = N2fStrings::CfgTimezoneDefault;
				$this->ExtensionDirectory = N2fStrings::CfgExtensionDirDefault;
			} else {
				$this->Charset = (array_key_exists('charset', $config)) ? $config['charset'] : N2fStrings::CfgCharsetDefault;
				$this->Logger = (array_key_exists('logger', $config) && is_array($config['logger'])) ? new LoggerConfig($config['logger']) : new LoggerConfig();
				$this->Extensions = (array_key_exists('extensions', $config) && is_array($config['extensions'])) ? $config['extensions'] : array();
				$this->Hash = (array_key_exists('hash', $config)) ? $config['hash'] : '';
				$this->Locale = (array_key_exists('locale', $config)) ? $config['locale'] : N2fStrings::CfgLocaleDefault;
				$this->Timezone = (array_key_exists('timezone', $config)) ? $config['timezone'] : N2fStrings::CfgTimezoneDefault;
				$this->ExtensionDirectory = (array_key_exists('extension_dir', $config)) ? ((substr($config['extension_dir'], -1) == '/') ? $config['extension_dir'] : $config['extension_dir'] . '/') : N2fStrings::CfgExtensionDirDefault;
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
	 * @copyright 2014-2016 Zibings.com
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
		 * @var mixed
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

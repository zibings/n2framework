<?php

	namespace N2f;

	/**
	* Basic logger class with notification chains.
	*
	* A class to provide basic logging functionality
  * for use by the N2f class (and anything else).
  * Has two chains to enable notifications of log
  * events and final dumped output.
	*
	* @version 1.0
	* @author Andrew Male
	* @copyright 2014-2016 Zibings.com
	* @package N2F
	*/
	class Logger {
		/**
		 * Local instance of LoggerConfig.
		 * 
		 * @var \N2f\LoggerConfig
		 */
		private $_Config;
		/**
		 * ChainHelper instance for sending out log dispatches.
		 * 
		 * @var \N2f\ChainHelper
		 */
		private $_LogChain;
		/**
		 * ChainHelper instance for sending out log output dispatches.
		 * 
		 * @var \N2f\ChainHelper
		 */
		private $_OutputChain;
		/**
		 * LoggerProcesser instance for handling log dispatches.
		 * 
		 * @var \N2f\LoggerProcessor
		 */
		private $_LogProcNode;
		/**
		 * Collection of various log level buckets.
		 * 
		 * @var array
		 */
		private $_Logs = array(
			'Debug' => array(),
			'Info' => array(),
			'Notice' => array(),
			'Warning' => array(),
			'Error' => array(),
			'Critical' => array(),
			'Alert' => array(),
			'Emergency' => array()
		);

		/**
		 * Creates a new Logger instance.
		 * 
		 * @param mixed $Config Optional array or LoggerConfig instance with configuration settings.
		 * @param ChainHelper $LogChain Optional ChainHelper instance to handle log dispatches.
		 * @param ChainHelper $OutputChain Optional ChainHelper instance to handle log output dispatches.
		 * @return void
		 */
		public function __construct($Config = null, ChainHelper &$LogChain = null, ChainHelper &$OutputChain = null) {
			if ($Config !== null) {
				if (is_array($Config)) {
					$this->_Config = new LoggerConfig($Config);
				} else if ($Config instanceof LoggerConfig) {
					$this->_Config = clone $Config;
				} else {
					$this->_Config = new LoggerConfig();
				}
			} else {
				$this->_Config = new LoggerConfig();
			}

			if ($LogChain !== null) {
				$this->_LogChain = $LogChain;
			} else {
				$this->_LogChain = new ChainHelper();
			}

			if ($OutputChain !== null) {
				$this->_OutputChain = $OutputChain;
			} else {
				$this->_OutputChain = new ChainHelper();
			}

			$this->_LogProcNode = new LoggerProcessor();
			$this->LinkOutputNode($this->_LogProcNode);

			return;
		}

		/**
		 * Adds an ALERT level log entry with optional
		 * context for keyword replacements.
		 * 
		 * @param string $Message String value of log message.
		 * @param array $Context Optional array value for context variables.
		 * @return \N2f\Logger The current Logger instance.
		 */
		public function Alert($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_ALERT, $Message, $Context);
		}

		/**
		 * Adds a CRITICAL level log entry with optional
		 * context for keyword replacements.
		 * 
		 * @param string $Message String value of log message.
		 * @param array $Context Optional array value for context variables.
		 * @return \N2f\Logger The current Logger instance.
		 */
		public function Critical($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_CRITICAL, $Message, $Context);
		}

		/**
		 * Adds a DEBUG level log entry with optional
		 * context for keyword replacements.
		 * 
		 * @param string $Message String value of log message.
		 * @param array $Context Optional array value for context variables.
		 * @return \N2f\Logger The current Logger instance.
		 */
		public function Debug($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_DEBUG, $Message, $Context);
		}

		/**
		 * Adds an EMERGENCY level log entry with optional
		 * context for keyword replacements.
		 * 
		 * @param string $Message String value of log message.
		 * @param array $Context Optional array value for context variables.
		 * @return \N2f\Logger The current Logger instance.
		 */
		public function Emergency($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_EMERGENCY, $Message, $Context);
		}

		/**
		 * Adds an ERROR level log entry with optional
		 * context for keyword replacements.
		 * 
		 * @param string $Message String value of log message.
		 * @param array $Context Optional array value for context variables.
		 * @return \N2f\Logger The current Logger instance.
		 */
		public function Error($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_ERROR, $Message, $Context);
		}

		/**
		 * Returns the current log level for the instance.
		 * 
		 * @return int The current log level flag.
		 */
		public function GetLogLevel() {
			return $this->_Config->LogLevel;
		}

		/**
		 * Returns a collection of log entries based
		 * on the provided level.
		 * 
		 * @param int $Level Flag for log level(s) to return.
		 * @return array|null Array of log entries or null if invalid flag.
		 */
		public function GetLogs($Level = null) {
			if ($Level === null) {
				return $this->_Logs;
			}

			if ($this->ValidSingleLevel($Level)) {
				$Ret = array();

				if ($Level & N2F_LOG_DEBUG) {
					$Ret['Debug'] = $this->_Logs['Debug'];
				}

				if ($Level & N2F_LOG_INFO) {
					$Ret['Info'] = $this->_Logs['Info'];
				}

				if ($Level & N2F_LOG_NOTICE) {
					$Ret['Notice'] = $this->_Logs['Notice'];
				}

				if ($Level & N2F_LOG_WARNING) {
					$Ret['Warning'] = $this->_Logs['Warning'];
				}

				if ($Level & N2F_LOG_ERROR) {
					$Ret['Error'] = $this->_Logs['Error'];
				}

				if ($Level & N2F_LOG_CRITICAL) {
					$Ret['Critical'] = $this->_Logs['Critical'];
				}

				if ($Level & N2F_LOG_ALERT) {
					$Ret['Alert'] = $this->_Logs['Alert'];
				}

				if ($Level & N2F_LOG_EMERGENCY) {
					$Ret['Emergency'] = $this->_Logs['Emergency'];
				}

				return $Ret;
			}

			return null;
		}

		/**
		 * Adds an INFO level log entry with optional
		 * context for keyword replacements.
		 * 
		 * @param string $Message String value of log message.
		 * @param array $Context Optional array value for context variables.
		 * @return \N2f\Logger The current Logger instance.
		 */
		public function Info($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_INFO, $Message, $Context);
		}

		/**
		 * Check if a log level flag is included
		 * in the current setting.
		 * 
		 * @param int $Level Flag to check against current level(s).
		 * @return int Whether or not the flags are enabled.
		 */
		public function IsLevelLogged($Level) {
			if (!is_int($Level)) {
				$Level = $this->LevelFromString($Level);
			}

			return $this->_Config->LogLevel & $Level;
		}

		/**
		 * Converts a string representation of log levels
		 * (such as 'N2F_LOG_DEBUG | N2F_LOG_INFO') into
		 * the appropriate integer flag.
		 * 
		 * @param string $Level String representation of level flag.
		 * @return int $Level converted to the integer flag.
		 */
		protected function LevelFromString($Level) {
			if ($Level === null || empty($Level)) {
				return N2F_LOG_NONE;
			}

			$Ret = 0;

			$Parts = explode('|', $Level);

			foreach (array_values($Parts) as $Part) {
				$Part = trim($Part);

				if ($Part == 'N2F_LOG_ALL') {
					$Ret = N2F_LOG_ALL;

					break;
				}

				switch ($Part) {
					case 'N2F_LOG_DEBUG':
						$Ret = $Ret | N2F_LOG_DEBUG;

						break;
					case 'N2F_LOG_INFO':
						$Ret = $Ret | N2F_LOG_INFO;

						break;
					case 'N2F_LOG_NOTICE':
						$Ret = $Ret | N2F_LOG_NOTICE;

						break;
					case 'N2F_LOG_WARNING':
						$Ret = $Ret | N2F_LOG_WARNING;

						break;
					case 'N2F_LOG_ERROR':
						$Ret = $Ret | N2F_LOG_ERROR;

						break;
					case 'N2F_LOG_CRITICAL':
						$Ret = $Ret | N2F_LOG_CRITICAL;

						break;
					case 'N2F_LOG_ALERT':
						$Ret = $Ret | N2F_LOG_ALERT;

						break;
					case 'N2F_LOG_EMERGENCY':
						$Ret = $Ret | N2F_LOG_EMERGENCY;

						break;
				}
			}

			return $Ret;
		}

		/**
		 * Links a NodeBase into the chain for
		 * processing log dispatches.
		 * 
		 * @param \N2f\NodeBase $Node NodeBase to add to chain.
		 * @return \N2f\Logger The current Logger instance.
		 */
		public function LinkLogNode(NodeBase $Node) {
			$this->_LogChain->LinkNode($Node);

			return $this;
		}

		/**
		 * Links a NodeBase into the chain for
		 * processing log output dispatches.
		 * 
		 * @param \N2f\NodeBase $Node NodeBase to add to chain.
		 * @return \N2f\Logger The current Logger instance.
		 */
		public function LinkOutputNode(NodeBase $Node) {
			$this->_OutputChain->LinkNode($Node);

			return $this;
		}

		/**
		 * Adds a log entry with optional context
		 * for keyword replacements.
		 * 
		 * @param int $Level Integer level flag of log entry.
		 * @param string $Message String value of log message.
		 * @param array $Context Optional array for context variables.
		 * @return \N2f\Logger The current Logger instance.
		 */
		protected function Log($Level, $Message, array $Context = array()) {
			if (!is_int($Level)) {
				$Level = $this->LevelFromString($Level);
			}

			if (count($Context) > 0) {
				$Replace = array();

				foreach ($Context as $key => $val) {
					$Replace['{'.$key.'}'] = $val;
				}

				$Message = str_replace(array_keys($Replace), array_values($Replace), $Message);
			}

			if ($this->ValidSingleLevel($Level)) {
				$Log = array(
					'Time' => time(),
					'Message' => $Message
				);

				if ($Level & N2F_LOG_DEBUG) {
					$this->_Logs['Debug'][] = $Log;
				}

				if ($Level & N2F_LOG_INFO) {
					$this->_Logs['Info'][] = $Log;
				}

				if ($Level & N2F_LOG_NOTICE) {
					$this->_Logs['Notice'][] = $Log;
				}

				if ($Level & N2F_LOG_WARNING) {
					$this->_Logs['Warn'][] = $Log;
				}

				if ($Level & N2F_LOG_ERROR) {
					$this->_Logs['Error'][] = $Log;
				}

				if ($Level & N2F_LOG_CRITICAL) {
					$this->_Logs['Critical'][] = $Log;
				}

				if ($Level & N2F_LOG_ALERT) {
					$this->_Logs['Alert'][] = $Log;
				}

				if ($Level & N2F_LOG_EMERGENCY) {
					$this->_Logs['Emergency'][] = $Log;
				}

				$Log['Level'] = $Level;

				$Dispatch = new LogDispatch();
				$Dispatch->Initialize($Log);

				$this->_LogChain->Traverse($Dispatch, $this);
			}

			return $this;
		}

		/**
		 * Adds a NOTICE level log entry with optional
		 * context for keyword replacements.
		 * 
		 * @param string $Message String value of log message.
		 * @param array $Context Optional array for context variables.
		 * @return \N2f\Logger The current Logger instance.
		 */
		public function Notice($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_NOTICE, $Message, $Context);
		}

		/**
		 * Traverses the ChainHelper linked to log
		 * output dispatches. If no nodes have been
		 * linked to chain, it will attach the N2f
		 * default LoggerProcessor node.
		 * 
		 * @return void
		 */
		public function Output() {
			$Dispatch = new LogOutputDispatch();
			$Dispatch->Initialize(array(
				'Dump' => $this->_Config->DumpLogs,
				'Level' => $this->_Config->LogLevel,
				'Logs' => $this->_Logs
			));

			$Nodes = $this->_OutputChain->GetNodeList();

			if (count($Nodes) < 1) {
				$this->_OutputChain->LinkNode(new LoggerProcessor);
			}

			$this->_OutputChain->Traverse($Dispatch, $this);

			return;
		}

		/**
		 * Adds a WARNING level log entry with optional
		 * context for keyword replacements.
		 * 
		 * @param string $Message String value of log message.
		 * @param array $Context Optional array for context variables.
		 * @return \N2f\Logger The current Logger instance.
		 */
		public function Warning($Message, array $Context = null) {
			return $this->Log(N2F_LOG_WARNING, $Message, $Context);
		}

		/**
		 * Determines whether or not a level (string or integer)
		 * is valid.
		 * 
		 * @param mixed $Level String or integer value of log level.
		 * @return bool True if level is one or more valid levels.
		 */
		public static function ValidLevel($Level) {
			if (is_int($Level)) {
				if ($Level > N2F_LOG_ALL || $Level < 0) {
					return false;
				}

				return true;
			}

			if (!is_string($Level)) {
				return false;
			}

			$Parts = explode('|', $Level);

			if (count($Parts) < 1) {
				return false;
			}

			foreach (array_values($Parts) as $Part) {
				$Part = trim($Part);

				switch ($Part) {
					case 'N2F_LOG_NONE':
					case 'N2F_LOG_DEBUG':
					case 'N2F_LOG_INFO':
					case 'N2F_LOG_NOTICE':
					case 'N2F_LOG_WARNING':
					case 'N2F_LOG_ERROR':
					case 'N2F_LOG_CRITICAL':
					case 'N2F_LOG_ALERT':
					case 'N2F_LOG_EMERGENCY':
					case 'N2F_LOG_ALL':
						break;
					default:
						return false;
				}
			}

			return true;
		}

		/**
		 * Determines whether or not a single level
		 * (string or integer) is valid.
		 * 
		 * @param mixed $Level String or integer value of a single log level.
		 * @return bool True if level is valid.
		 */
		public static function ValidSingleLevel($Level) {
			if (is_int($Level)) {
				switch ($Level) {
					case N2F_LOG_NONE:
					case N2F_LOG_DEBUG:
					case N2F_LOG_INFO:
					case N2F_LOG_NOTICE:
					case N2F_LOG_WARNING:
					case N2F_LOG_ERROR:
					case N2F_LOG_CRITICAL:
					case N2F_LOG_ALERT:
					case N2F_LOG_EMERGENCY:
					case N2F_LOG_ALL:
						break;
					default:
						return false;
				}

				return true;
			}

			if (!is_string($Level)) {
				return false;
			}

			switch (trim($Level)) {
				case 'N2F_LOG_NONE':
				case 'N2F_LOG_DEBUG':
				case 'N2F_LOG_INFO':
				case 'N2F_LOG_NOTICE':
				case 'N2F_LOG_WARNING':
				case 'N2F_LOG_ERROR':
				case 'N2F_LOG_CRITICAL':
				case 'N2F_LOG_ALERT':
				case 'N2F_LOG_EMERGENCY':
				case 'N2F_LOG_ALL':
					break;
				default:
					return false;
			}

			return true;
		}
	}

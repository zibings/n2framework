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
	* @copyright 2014-2015 Zibings.com
	* @package N2F
	*/
	class Logger {
		private $_Config;
		private $_LogChain;
		private $_OutputChain;
		private $_LogProcNode;
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

			return $this;
		}

		public function Alert($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_ALERT, $Message, $Context);
		}

		public function Critical($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_CRITICAL, $Message, $Context);
		}

		public function Debug($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_DEBUG, $Message, $Context);
		}

		public function Emergency($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_EMERGENCY, $Message, $Context);
		}

		public function Error($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_ERROR, $Message, $Context);
		}

		public function GetLogLevel() {
			return $this->_Config->LogLevel;
		}

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

		public function Info($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_INFO, $Message, $Context);
		}

		public function IsLevelLogged($Level) {
			if (!is_int($Level)) {
				$Level = $this->LevelFromString($Level);
			}

			return $this->_Config->LogLevel & $Level;
		}

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

		public function LinkLogNode(NodeBase $Node) {
			$this->_LogChain->LinkNode($Node);

			return $this;
		}

		public function LinkOutputNode(NodeBase $Node) {
			$this->_OutputChain->LinkNode($Node);

			return $this;
		}

		protected function Log($Level, $Message, array $Context = array()) {
			if (!is_int($Level)) {
				$Level = $this->LevelFromString($Level);
			}

			if (count($Context) > 0) {
				$Replace = array();

				foreach ($Context as $key => $val) {
					$Replace['{'.$key.'}'] = $val;
				}

				$Message = strstr($Message, $Replace);
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

		public function Notice($Message, array $Context = array()) {
			return $this->Log(N2F_LOG_NOTICE, $Message, $Context);
		}

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

		public function Warning($Message, $Number = null) {
			return $this->Log(N2F_LOG_WARNING, $Message, $Number);
		}

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

?>
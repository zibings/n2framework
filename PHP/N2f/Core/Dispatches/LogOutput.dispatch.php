<?php

	namespace N2f;

	/**
	 * Output dispatch for log system.
	 *
	 * The output dispatch type for the base
	 * log system used by the N2f class.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class LogOutputDispatch extends DispatchBase {
		/**
		 * Whether or not to dump logs.
		 * 
		 * @var bool True or false based on settings.
		 */
		protected $_Dump;
		/**
		 * Current log level setting.
		 * 
		 * @var int
		 */
		protected $_Level;
		/**
		 * Array of log entries.
		 * 
		 * @var array
		 */
		protected $_Logs;

		/**
		 * Creates a new LogOutputDispatch instance.
		 * LogOutputDispatch is a stateful dispatch
		 * and cannot be consumed.
		 * 
		 * @return void
		 */
		public function __construct() {
			$this->MakeStateful();

			return;
		}

		/**
		 * Returns log entries based on the provided
		 * log level, all entries if no level provided,
		 * or no entries if an invalid level provided.
		 * 
		 * @param int $Level Log level to configure the returned array.
		 * @return array|null Array value if log entries present, null if invalid level.
		 */
		public function GetLogs($Level = null) {
			if ($Level === null) {
				return $this->_Logs;
			}

			if (!Logger::ValidLevel($Level)) {
				return null;
			}

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

		/**
		 * Returns the currently configured log level.
		 * 
		 * @return int Log level from settings.
		 */
		public function GetLogLevel() {
			return $this->_Level;
		}

		/**
		 * Returns any results for dispatch as a string.
		 * 
		 * @return string String value for all results (combined with newline if multiple present).
		 */
		public function GetResults() {
			$Results = parent::GetResults();

			if (count($Results) < 1) {
				return '';
			} else if (count($Results) == 1) {
				return $Results[0];
			}

			$RetStr = '';

			foreach (array_values($Results) as $Res) {
				$RetStr .= "\n" . $Res;
			}

			return $RetStr;
		}

		/**
		 * Initializes a LogOutputDispatch instance. $Input
		 * must have 'Dump', 'Level', and 'Logs' elements.
		 * 
		 * @param array $Input Array of input information from Logger.
		 * @return void
		 */
		public function Initialize($Input) {
			if ($Input === null || !is_array($Input) || count($Input) != 3) {
				return;
			}

			if (!array_key_exists('Dump', $Input) || !array_key_exists('Level', $Input) || !array_key_exists('Logs', $Input)) {
				return;
			}

			$this->_Dump = $Input['Dump'];
			$this->_Level = (Logger::ValidLevel($Input['Level'])) ? $Input['Level'] : N2F_LOG_OFF;
			$this->_Logs = $Input['Logs'];

			$this->MakeValid();

			return;
		}

		/**
		 * Returns setting for dumping logs.
		 * 
		 * @return bool True or false based on settings.
		 */
		public function ShouldDumpLogs() {
			return $this->_Dump;
		}
	}

?>
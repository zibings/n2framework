<?php

	namespace N2f;

	/**
	 * Node to manage default output behavior.
	 *
	 * Manages output for the default logger included
	 * with the N2f class.  Automatically accounts for
	 * Cli, Json, or Html based requests.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class LoggerProcessor extends NodeBase {
		public function __construct() {
			$this->SetKey('N2f-LoggerProcessor')->SetVersion('1.0');

			return $this;
		}

		public function Process($Sender, DispatchBase &$Dispatch) {
			if (!($Dispatch instanceof LogOutputDispatch) || $Dispatch->GetLogLevel() & N2F_LOG_NONE) {
				return;
			}

			$Level = $Dispatch->GetLogLevel();
			$Logs = $Dispatch->GetLogs();

			$Ch = new ConsoleHelper();

			if ($Ch->IsCLI()) {
				$Ch->PutLine();

				if ($Level & N2F_LOG_DEBUG) {
					$this->GenerateConsoleBlock('DEBUG', $Logs['Debug']);
				}

				if ($Level & N2F_LOG_INFO) {
					$this->GenerateConsoleBlock('INFO', $Logs['Info']);
				}

				if ($Level & N2F_LOG_NOTICE) {
					$this->GenerateConsoleBlock('NOTICE', $Logs['Notice']);
				}

				if ($Level & N2F_LOG_WARNING) {
					$this->GenerateConsoleBlock('WARNING', $Logs['Warning']);
				}

				if ($Level & N2F_LOG_ERROR) {
					$this->GenerateConsoleBlock('ERROR', $Logs['Error']);
				}

				if ($Level & N2F_LOG_CRITICAL) {
					$this->GenerateConsoleBlock('CRITICAL', $Logs['Critical']);
				}

				if ($Level & N2F_LOG_ALERT) {
					$this->GenerateConsoleBlock('ALERT', $Logs['Alert']);
				}

				if ($Level & N2F_LOG_EMERGENCY) {
					$this->GenerateConsoleBlock('EMERGENCY', $Logs['Emergency']);
				}

				$Ch->PutLine();
			} else {
				$Rh = new RequestHelper();

				if ($Rh->IsJson()) {
					$Jh = new JsonHelper();
					$VisibleLogs = array();

					if ($Level & N2F_LOG_DEBUG && count($Logs['Debug']) > 0) {
						$VisibleLogs['Debug'] = $Logs['Debug'];
					}

					if ($Level & N2F_LOG_INFO && count($Logs['Info']) > 0) {
						$VisibleLogs['Info'] = $Logs['Info'];
					}

					if ($Level & N2F_LOG_NOTICE && count($Logs['Notice']) > 0) {
						$VisibleLogs['Notice'] = $Logs['Notice'];
					}

					if ($Level & N2F_LOG_WARNING && count($Logs['Warning']) > 0) {
						$VisibleLogs['Warning'] = $Logs['Warning'];
					}

					if ($Level & N2F_LOG_ERROR && count($Logs['Error']) > 0) {
						$VisibleLogs['Error'] = $Logs['Error'];
					}

					if ($Level & N2F_LOG_CRITICAL && count($Logs['Critical']) > 0) {
						$VisibleLogs['Critical'] = $Logs['Critical'];
					}

					if ($Level & N2F_LOG_ALERT && count($Logs['Alert']) > 0) {
						$VisibleLogs['Alert'] = $Logs['Alert'];
					}

					if ($Level & N2F_LOG_EMERGENCY && count($Logs['Emergency']) > 0) {
						$VisibleLogs['Emergency'] = $Logs['Emergency'];
					}

					echo($Jh->EncodePretty(array(
						'Generated' => date('Y-m-d G:i:s'),
						'LogLevel' => $Level,
						'Logs' => $VisibleLogs
					)));
				} else {
					echo('<div id="n2f-logger-dump-output" style="padding: 10px">');

					if ($Level & N2F_LOG_DEBUG) {
						$this->GenerateHtmlBlock('DEBUG', $Logs['Debug']);
					}

					if ($Level & N2F_LOG_INFO) {
						$this->GenerateHtmlBlock('INFO', $Logs['Info']);
					}

					if ($Level & N2F_LOG_NOTICE) {
						$this->GenerateHtmlBlock('NOTICE', $Logs['Notice']);
					}

					if ($Level & N2F_LOG_WARNING) {
						$this->GenerateHtmlBlock('WARNING', $Logs['Warning']);
					}

					if ($Level & N2F_LOG_ERROR) {
						$this->GenerateHtmlBlock('ERROR', $Logs['Error']);
					}

					if ($Level & N2F_LOG_CRITICAL) {
						$this->GenerateHtmlBlock('CRITICAL', $Logs['Critical']);
					}

					if ($Level & N2F_LOG_ALERT) {
						$this->GenerateHtmlBlock('ALERT', $Logs['Alert']);
					}

					if ($Level & N2F_LOG_EMERGENCY) {
						$this->GenerateHtmlBlock('EMERGENCY', $Logs['Emergency']);
					}

					echo('</div>');
				}
			}

			return;
		}

		protected function GenerateConsoleBlock($Id, array $Logs) {
			$Ch = new ConsoleHelper();

			if (count($Logs) < 1) {
				$Ch->PutLine("[{$Id}] There were no {$Id} logs recorded.");
			} else {
				foreach (array_values($Logs) as $Log) {
					$Ch->Put("[{$Id} @ " . date('Y-m-d G:i:s', $Log['Time']) . "] ");

					if ($Log['Number'] !== null) {
						$Ch->Put($Log['Number'] . " ");
					}

					$Ch->PutLine($Log['Message']);
				}
			}

			return;
		}

		protected function GenerateHtmlBlock($Id, array $Logs) {
			if (count($Logs) < 1) {
				echo("<div>[{$Id}] There were no {$Id} logs recorded.</div>");
			} else {
				foreach (array_values($Logs) as $Log) {
					echo("<div>[{$Id} @ " . date('Y-m-d G:i:s', $Log['Time']) . "] ");

					if ($Log['Number'] !== null) {
						echo($Log['Number'] . " ");
					}

					echo($Log['Message'] . "</div>");
				}
			}

			return;
		}
	}

?>
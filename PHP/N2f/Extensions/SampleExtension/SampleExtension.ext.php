<?php

	class SampleExtension extends N2f\ExtensionBase {
		public function Initialize(N2f\N2f &$N2f) {
			$N2f->LinkExecuteNode(new SampleNode);
			$N2f->LinkConfigNode(new SampleConfigNode);
			$N2f->GetLogger()->LinkOutputNode(new LogDump);
		}
	}

	class SampleConfigNode extends N2f\NodeBase {
		public function __construct() {
			$this->SetKey('SampleConfigNode')->SetVersion('0.1');

			return;
		}

		public function Process($Sender, N2f\DispatchBase &$Dispatch) {
			if ($Dispatch === null || !($Sender instanceof N2f\N2f)) {
				return;
			}

			/** @var N2f\ConfigDispatch $Dispatch */
			/** @var N2f\N2f $Sender */

			$Ch = $Sender->GetConsoleHelper();

			if ($Dispatch->IsInteractive()) {
				$Ch->PutLine("It's interactive.");
			} else {
				$Ch->PutLine("It's not interactive.");
			}

			$Ch->PutLine("Extension being configured is: " . $Dispatch->GetExt());

			$Dispatch->Consume();

			return;
		}
	}

	class SampleNode extends N2f\NodeBase {
		public function __construct() {
			$this->SetKey('SampleNode')->SetVersion('0.1');

			return;
		}

		public function Process($Sender, N2f\DispatchBase &$Dispatch) {
			if ($Dispatch === null || !($Sender instanceof N2f\N2f)) {
				return;
			}

			/** @var N2f\N2f $Sender */

			if ($Dispatch instanceof N2f\WebDispatch) {
				/** @var N2f\WebDispatch $Dispatch */

				echo("<pre>");
				print_r($Dispatch->GetParams());
				$Sender->GetLogger()->Output();
				echo("</pre>");
			} else if ($Dispatch instanceof N2f\CliDispatch) {
				/** @var N2f\CliDispatch $Dispatch */
				$Ch = $Sender->GetConsoleHelper();

				$Ch->PutLine("Hi there!");
				$Ch->PutLine();
				$Ch->PutLine(print_r($Dispatch->GetAssocParameters(), true));

				$Params = $Dispatch->GetAssocParameters();

				if (count($Params) > 1) {
					$total = 0;

					foreach ($Params as $Key => $Val) {
						$total += intval($Key);
					}

					$Ch->PutLine("Total: " . $total);
				}
			}

			$Dispatch->Consume();

			return;
		}
	}

	class LogDump extends N2f\NodeBase {
		public function __construct() {
			$this->SetKey('LogDump')->SetVersion('0.1');

			return;
		}

		public function Process($Sender, N2f\DispatchBase &$Dispatch) {
			if ($Dispatch === null || !($Dispatch instanceof N2f\LogOutputDispatch)) {
				return;
			}

			/** @var N2f\LogOutputDispatch $Dispatch */

			$Logs = $Dispatch->GetLogs();

			if (count($Logs) > 0) {
				echo("Boomsauce: <br /><pre>");
				print_r($Logs);
				echo("</pre>");
			}

			return;
		}
	}

?>
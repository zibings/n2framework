<?php

	namespace N2f;

	/**
	 * Class to handle chains of nodes.
	 *
	 * Class that handles a collection of nodes to
	 * make a chain.  Allows for easy grouping and
	 * sending of dispatches to the chain.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class ChainHelper {
		protected $_Nodes = array();
		protected $_Dispatch;
		private $_IsEvent = false;

		public function __construct($IsEvent = false) {
			$this->_IsEvent = $IsEvent;

			return $this;
		}

		public function GetNodeList() {
			$ret = array();

			if (count($this->_Nodes) > 0) {
				foreach (array_values($this->_Nodes) as $Node) {
					$ret[] = array('key' => $Node->Getkey(), 'version' => $Node->GetVersion());
				}
			}

			return $ret;
		}

		public function LinkNode(NodeBase $Node) {
			if (!$Node->IsValid()) {
				return $this;
			}

			if ($this->_IsEvent) {
				$this->_Nodes = array($Node);
			} else {
				$this->_Nodes[] = $Node;
			}

			return $this;
		}

		public function Traverse(DispatchBase &$Dispatch, $Sender = null) {
			if (count($this->_Nodes) < 1 || !$Dispatch->IsValid() || $Dispatch->IsConsumed()) {
				return;
			}

			if ($Sender === null) {
				$Sender = $this;
			}

			$isConsumable = $Dispatch->IsConsumable();

			if ($this->_IsEvent) {
				$this->_Nodes[0]->Process($Sender, $Dispatch);
			} else {
				$len = count($this->_Nodes);

				for ($i = 0; $i < $len; ++$i) {
					$this->_Nodes[$i]->Process($Sender, $Dispatch);

					if ($isConsumable && $Dispatch->IsConsumed()) {
						break;
					}
				}
			}

			return;
		}
	}

?>
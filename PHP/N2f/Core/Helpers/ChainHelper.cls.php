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
		/**
		 * Collection of nodes linked into chain.
		 * 
		 * @var array
		 */
		protected $_Nodes = array();
		/**
		 * Dispatch to use during traversal.
		 * 
		 * @var \N2f\DispatchBase
		 */
		protected $_Dispatch;
		/**
		 * Whether or not to display debug information.
		 * 
		 * @var bool
		 */
		protected $_DoDebug;
		/**
		 * Local instance of Logger class for logging
		 * debug information if toggled.
		 * 
		 * @var \N2f\Logger
		 */
		protected $_Logger;
		/**
		 * Whether or not multiple nodes may be linked
		 * to the chain. An event only allows one node.
		 * 
		 * @var bool
		 */
		private $_IsEvent = false;

		/**
		 * Creates a new ChainHelper instance.
		 * 
		 * @param bool $IsEvent True if chain is an event, default is false.
		 * @param bool $DoDebug True if chain should provide debugging information, default is false.
		 * @param \N2f\Logger $Logger Optional Logger instance to use for debug information, new Logger created by default.
		 * @return void
		 */
		public function __construct($IsEvent = false, $DoDebug = false, Logger &$Logger = null) {
			$this->_IsEvent = $IsEvent;
			$this->_DoDebug = $DoDebug;
			$this->_Logger = ($Logger !== null) ? $Logger : new Logger();

			return;
		}

		/**
		 * Returns the collection of node information
		 * for all nodes linked into chain.
		 * 
		 * @return array Array of linked node keys and versions.
		 */
		public function GetNodeList() {
			$ret = array();

			if (count($this->_Nodes) > 0) {
				foreach (array_values($this->_Nodes) as $Node) {
					$ret[] = array('key' => $Node->Getkey(), 'version' => $Node->GetVersion());
				}
			}

			return $ret;
		}

		/**
		 * Retrieves the local Logger instance.
		 * 
		 * @return \N2f\Logger The local Logger instance.
		 */
		public function &GetLogger() {
			return $this->_Logger;
		}

		/**
		 * Links a new NodeBase node into chain.
		 * 
		 * @param \N2f\NodeBase $Node Node to link into chain.
		 * @return \N2f\ChainHelper The current ChainHelper instance.
		 */
		public function LinkNode(NodeBase $Node) {
			if (!$Node->IsValid()) {
				return $this;
			}

			if ($this->_IsEvent) {
				$this->_Nodes = array($Node);

				if ($this->_DoDebug) {
					$this->_Logger->Debug("Set {$Node->GetKey()} (v{$Node->GetVersion()}) node as chain handler.");
				}
			} else {
				$this->_Nodes[] = $Node;

				if ($this->_DoDebug) {
					$this->_Logger->Debug("Linked {$Node->GetKey()} (v{$Node->GetVersion()}) node to chain.");
				}
			}

			return $this;
		}

		/**
		 * Starts traversal of the chain if there are linked
		 * nodes, the dispatch is valid, and the dispatch is
		 * not consumed.
		 * 
		 * @param \N2f\DispatchBase $Dispatch The dispatch to send along the chain.
		 * @param mixed $Sender Optional sender object, ChainHelper instance used if not provided.
		 * @return void
		 */
		public function Traverse(DispatchBase &$Dispatch, $Sender = null) {
			if (count($this->_Nodes) < 1 || !$Dispatch->IsValid() || $Dispatch->IsConsumed()) {
				return;
			}

			$this->_Dispatch = $Dispatch;

			if ($Sender === null) {
				$Sender = $this;
			}

			$isConsumable = $Dispatch->IsConsumable();

			if ($this->_IsEvent) {
				$this->_Nodes[0]->Process($Sender, $Dispatch);
			} else {
				$len = count($this->_Nodes);

				for ($i = 0; $i < $len; ++$i) {
					if ($this->_DoDebug) {
						$this->_Logger->Debug("Sending dispatch to {$this->_Nodes[$i]->GetKey()} (v{$this->_Nodes[$i]->GetVersion()}) node in chain.");
					}

					$this->_Nodes[$i]->Process($Sender, $Dispatch);

					if ($isConsumable && $Dispatch->IsConsumed()) {
						if ($this->_DoDebug) {
							$this->_Logger->Debug("Chain traversal stopped by {$this->_Nodes[$i]->GetKey()} (v{$this->_Nodes[$i]->GetVersion()}) node.");
						}

						break;
					}
				}
			}

			return;
		}
	}

?>
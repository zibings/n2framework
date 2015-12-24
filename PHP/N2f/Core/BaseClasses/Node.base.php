<?php

	namespace N2f;

	/**
	 * Abstract for all nodes in system.
	 *
	 * Abstract that defines required functionality for all nodes.
	 * Nodes which implement can be much more complex based on needs.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	abstract class NodeBase {
		/**
		 * Key for node.
		 * 
		 * @var string
		 */
		protected $_Key = null;
		/**
		 * Version for node.
		 * 
		 * @var string
		 */
		protected $_Version = null;

		/**
		 * Returns the key (if set) for the node.
		 *
		 * @return string|null Node key or null if not set.
		 */
		public function GetKey() {
			return $this->_Key;
		}

		/**
		 * Returns the version (if set) for the node.
		 * 
		 * @return string|null Node version or null if not set.
		 */
		public function GetVersion() {
			return $this->_Version;
		}

		/**
		 * Returns whether or not node is valid for use in chain.
		 * 
		 * @return bool True if both key and version are set, false if not.
		 */
		public function IsValid() {
			return $this->_Key !== null && $this->_Version !== null;
		}

		/**
		 * Method to process a dispatch from a sender.
		 * 
		 * @param mixed $Sender Object (or other) that initiated chain traversal.
		 * @param \N2f\DispatchBase $Dispatch Dispatch to process from sender.
		 */
		abstract public function Process($Sender, DispatchBase &$Dispatch);

		/**
		 * Sets the node's key value.
		 * 
		 * @param string $Key String value to set for key.
		 * @return \N2f\NodeBase Current node.
		 */
		public function SetKey($Key) {
			$this->_Key = $Key;

			return $this;
		}

		/**
		 * Sets the node's version value.
		 * 
		 * @param string $Version String value to set for version.
		 * @return \N2f\NodeBase Current node.
		 */
		public function SetVersion($Version) {
			$this->_Version = $Version;

			return $this;
		}
	}

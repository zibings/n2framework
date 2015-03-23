<?php

	namespace N2f;

	/**
	 * Node for managing extensions.
	 * 
	 * Node that manages the extension list and provides
	 * an alias for the generation of new extensions.
	 * 
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class ExtensionConfig extends NodeBase {
		public function __construct() {
			$this->SetKey('n2f-extensioncofig')->SetVersion('1.0');

			return;
		}

		public function Process($Sender, DispatchBase &$Dispatch) {
			if (!($Dispatch instanceof ExtensionDispatch)) {
				return;
			}

			/** @var ExtensionDispatch $Dispatch */

			$Fh = $Dispatch->GetFileHelper();
			$Jh = new JsonHelper();

			if (!$Fh->FileExists("~N2f/Includes/N2f.cfg")) {
				return;
			}

			$Cfg = $Jh->DecodeAssoc($Fh->GetContents("~N2f/Includes/N2f.cfg"));
			$Extension = $Dispatch->GetExtension();

			switch ($Dispatch->GetAction()) {
				case 'add':
					if (!array_key_exists('extensions', $Cfg)) {
						$Cfg['extensions'] = array($Extension);
					} else {
						$found = false;

						foreach (array_values($Cfg['extensions']) as $Ext) {
							if ($Ext == $Extension) {
								$found = true;
							}
						}

						if ($found === false) {
							$Cfg['extensions'][] = $Extension;
						}
					}

					$Dispatch->SetResult("Successfully configured the {$Extension} extension for use with the system.");

					break;
				case 'remove':
					if (array_key_exists('extensions', $Cfg) && count($Cfg['extensions']) > 0 && $this->ArrayValueExists($Extension, $Cfg['extensions'])) {
						$copy = $Cfg['extensions'];
						$Cfg['extensions'] = array();

						foreach (array_values($copy) as $Ext) {
							if ($Ext != $Extension) {
								$Cfg['extensions'][] = $Ext;
							}
						}

						$Dispatch->SetResult("Successfully removed the {$Extension} extension from use with the system.");
					} else {
						$Dispatch->SetResult("The {$Extension} extension was not configured for use with the system.");
					}

					break;
				case 'create':
					$n2f = N2f::createInstance(array(
						'FileHelper' => $this->_Fh,
						'ConsoleHelper' => new ConsoleHelper(6, array(
							'n2f-cli',
							'generate',
							'-type',
							'ext',
							'-name',
							$Extension
						))
					));

					$n2f->Process();

					if (!array_key_exists('extensions', $Cfg)) {
						$Cfg['extensions'] = array($Extension);
					} else {
						$found = false;

						foreach (array_values($Cfg['extensions']) as $Ext) {
							if ($Ext == $Extension) {
								$found = true;
							}
						}

						if ($found === false) {
							$Cfg['extensions'][] = $Extension;
						}
					}

					$Dispatch->SetResult("Successfully created and configured the {$Extension} extension for use with the system.");

					break;
				default:
					return;
			}

			$Fh->PutContents("~N2f/Includes/N2f.cfg", $Jh->EncodePretty($Cfg));

			return;
		}

		protected function ArrayValueExists($Value, array $Array) {
			if (count($Array) < 1) {
				return false;
			}

			foreach (array_values($Array) as $Val) {
				if ($Val === $Value) {
					return true;
				}
			}

			return false;
		}
	}

?>
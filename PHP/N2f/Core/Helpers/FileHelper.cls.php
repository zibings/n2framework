<?php

	namespace N2f;

	/**
	 * FileHelper class to do file operations.
	 *
	 * Class to simplify file-based operations
	 * that require translation with things like
	 * N2F_REL_DIR.
	 *
	 * @version 1.0
	 * @author Andrew Male
	 * @copyright 2014-2015 Zibings.com
	 * @package N2F
	 */
	class FileHelper {
		private static $Included = array();
		private $RelDir;

		// Fake constants
		private $Glob_All = 0;
		private $Glob_FoldersOnly = 1;
		private $Glob_FilesOnly = 2;

		public function __construct($RelDir = null) {
			if ($RelDir !== null) {
				$this->RelDir = $RelDir;
			} else {
				$this->RelDir = './';
			}

			return;
		}

		public function FileExists($Path) {
			if (!empty($Path) && file_exists($this->ProcessRoot($Path))) {
				return true;
			}

			return false;
		}

		public function FolderExists($Path) {
			if (!empty($Path) && is_dir($this->ProcessRoot($Path))) {
				return true;
			}

			return false;
		}

		public function Load($Path) {
			if (empty($Path) && isset(FileHelper::$Included[$Path])) {
				return;
			}

			FileHelper::$Included[$Path] = true;
			$Path = $this->ProcessRoot($Path);

			if ($this->FileExists($Path)) {
				require_once($Path);
			}

			return;
		}

		public function GetContents($Path) {
			if (empty($Path)) {
				return null;
			}

			$Path = $this->ProcessRoot($Path);
			$Ret = '';

			if ($this->FileExists($Path)) {
				$Ret = file_get_contents($Path);
			}

			return $Ret;
		}

		public function GetFolderFiles($Path) {
			return $this->GlobFolder($Path, $this->Glob_FilesOnly);
		}

		public function GetFolderFolders($Path, $Recursive = false) {
			return $this->GlobFolder($Path, $this->Glob_FoldersOnly, $Recursive);
		}

		public function GetFolderItems($Path, $Recursive = false) {
			return $this->GlobFolder($Path, $this->Glob_All, $Recursive);
		}

		public function GetRelDir() {
			return $this->RelDir;
		}
		
		protected function GlobFolder($Path, $GlobType, $Recursive = false) {
			if (empty($Path)) {
				return null;
			}

			$Ret = array();
			$Path = $this->ProcessRoot($Path);

			if (!is_dir($Path)) {
				return null;
			}

			if (substr($Path, -1) != '/') {
				$Path .= '/';
			}

			if ($Dh = @opendir($Path)) {
				while (($Item = @readdir($Dh)) !== false) {
					if ($Item == '.' || $Item == '..') {
						continue;
					}

					if (is_dir($Path . $Item) && ($GlobType == $this->Glob_All || $GlobType == $this->Glob_FoldersOnly)) {
						$Ret[] = $Path . $Item . '/';

						if ($Recursive) {
							$Tmp = $this->GlobFolder($Path . $Item, $GlobType, $Recursive);

							if (count($Tmp) > 0) {
								foreach (array_values($Tmp) as $TItem) {
									$Ret[] = $TItem;
								}
							}
						}
					} else if ($GlobType == $this->Glob_All || $GlobType == $this->Glob_FilesOnly) {
						$Ret[] = $Path . $Item;
					}
				}
			}

			return $Ret;
		}

		public function MakeFolder($Path) {
			if (empty($Path) || $this->FolderExists($Path)) {
				return false;
			}

			return mkdir($this->ProcessRoot($Path));
		}

		protected function ProcessRoot($Path) {
			if ($Path[0] == '~') {
				$Path = $this->RelDir . substr($Path, ($Path[1] == '/' && $this->RelDir[strlen($this->RelDir) - 1] == '/') ? 2 : 1);
			}

			return $Path;
		}

		public function PutContents($Path, $Data) {
			if (empty($Path) || $Data === null) {
				return;
			}

			file_put_contents($this->ProcessRoot($Path), $Data);

			return;
		}
	}

?>
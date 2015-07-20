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
		/**
		 * Cache collection of files that have
		 * been included.
		 * 
		 * @var array
		 */
		private static $Included = array();
		/**
		 * The relative directory path.
		 * 
		 * @var string
		 */
		private $RelDir;

		// Fake constants
		private $Glob_All = 0;
		private $Glob_FoldersOnly = 1;
		private $Glob_FilesOnly = 2;

		/**
		 * Creates a new FileHelper instance.
		 * 
		 * @param string $RelDir Optional relative directory, './' used otherwise.
		 * @param array $PreLoads Optional array of files that have already been loaded.
		 * @return void
		 */
		public function __construct($RelDir = null, array $PreLoads = null) {
			if ($RelDir !== null) {
				$this->RelDir = $RelDir;
			} else {
				$this->RelDir = './';
			}

			if ($PreLoads !== null && count($PreLoads) > 0) {
				foreach (array_values($PreLoads) as $Load) {
					FileHelper::$Included[$Load] = true;
				}
			}

			return;
		}

		/**
		 * Returns whether or not a file exists.
		 * 
		 * @param string $Path String value of file path.
		 * @return bool True if file exists, false otherwise.
		 */
		public function FileExists($Path) {
			if (!empty($Path) && file_exists($this->ProcessRoot($Path))) {
				return true;
			}

			return false;
		}

		/**
		 * Returns whether or not a folder exists.
		 * 
		 * @param string $Path String value of folder path.
		 * @return bool True if folder exists, false otherwise.
		 */
		public function FolderExists($Path) {
			if (!empty($Path) && is_dir($this->ProcessRoot($Path))) {
				return true;
			}

			return false;
		}

		/**
		 * Loads a file from the filesystem.
		 * 
		 * @param string $Path String value of path to include.
		 * @param bool $AllowReload Boolean value to toggle allowing files to be re-loaded.
		 * @return \N2f\ReturnHelper A ReturnHelper instance with extra state information.
		 */
		public function Load($Path, $AllowReload = false) {
			$Ret = new ReturnHelper();

			if (empty($Path)) {
				$Ret->SetMessage("Invalid path provided.");
			} else if (isset(FileHelper::$Included[$Path]) && !$AllowReload) {
				$Ret->SetMessage("File has already been included.");
			} else {
				FileHelper::$Included[$Path] = true;
				$Path = $this->ProcessRoot($Path);

				if ($this->FileExists($Path)) {
					require_once($Path);

					$Ret->SetGud();
					$Ret->SetResult($Path);
				} else {
					$Ret->SetMessage("File did not exist.");
				}
			}

			return $Ret;
		}

		/**
		 * Return the contents of the specified file.
		 * 
		 * @param string $Path String value of file path.
		 * @return string|null String contents of file, null if not found.
		 */
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

		/**
		 * Retrieve file list from specified folder.
		 * 
		 * @param string $Path String value of the folder path.
		 * @return array|null List of files, null if not found or path invalid.
		 */
		public function GetFolderFiles($Path) {
			return $this->GlobFolder($Path, $this->Glob_FilesOnly);
		}

		/**
		 * Retrieve folder list from specified folder.
		 * 
		 * @param string $Path String value of the folder path.
		 * @param bool $Recursive Toggles recursive traversal of the folder.
		 * @return array|null List of folders, null if not found or path invalid.
		 */
		public function GetFolderFolders($Path, $Recursive = false) {
			return $this->GlobFolder($Path, $this->Glob_FoldersOnly, $Recursive);
		}

		/**
		 * Retrieves a list of folder items from the specified folder.
		 * 
		 * @param string $Path String value of the folder path.
		 * @param bool $Recursive Toggles recursive traversal of the folder.
		 * @return array|null List of folder items, null if not found or path invalid.
		 */
		public function GetFolderItems($Path, $Recursive = false) {
			return $this->GlobFolder($Path, $this->Glob_All, $Recursive);
		}

		/**
		 * Returns the relative directory path.
		 * 
		 * @return string Relative directory path for instance.
		 */
		public function GetRelDir() {
			return $this->RelDir;
		}
		
		/**
		 * Retrieves the results of a folder traversal.
		 * 
		 * @param string $Path String value of the folder path.
		 * @param int $GlobType Option for returned entries.
		 * @param bool $Recursive Toggles recursive traversal of the folder.
		 * @return array|null List of folder items, null if not found or path invalid.
		 */
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

		/**
		 * Creates a folder on the filesystem.
		 * 
		 * @param string $Path String value of the folder path.
		 * @return bool True if the folder is created, false otherwise.
		 */
		public function MakeFolder($Path) {
			if (empty($Path) || $this->FolderExists($Path)) {
				return false;
			}

			return mkdir($this->ProcessRoot($Path));
		}

		/**
		 * Processes a path string to replace the ~
		 * with the relative directory path.
		 * 
		 * @param string $Path String value of the path.
		 * @return string String value of processed path.
		 */
		protected function ProcessRoot($Path) {
			if ($Path[0] == '~') {
				$Path = $this->RelDir . substr($Path, ($Path[1] == '/' && $this->RelDir[strlen($this->RelDir) - 1] == '/') ? 2 : 1);
			}

			return $Path;
		}

		/**
		 * Inserts the contents into the given file.
		 * 
		 * @param string $Path String value of file path.
		 * @param mixed $Data Data to insert into file.
		 * @param int $Flags Optional flags sent to file_put_contents.
		 * @param resource $Context Optional context resource from stream_context_create().
		 * @return \N2f\ReturnHelper A ReturnHelper instance with extra state information.
		 */
		public function PutContents($Path, $Data, $Flags = 0, $Context = null) {
			$Ret = new ReturnHelper();

			if (empty($Path)) {
				$Ret->SetMessage("Invalid path provided.");
			} else if ($Data === null || empty($Data)) {
				$Ret->SetMessage("Invalid data provided.");
			} else {
				if (($bytesWritten = @file_put_contents($this->ProcessRoot($Path), $Data, $Flags, $Context)) !== false) {
					$Ret->SetGud();
					$Ret->SetResult($bytesWritten);
				} else {
					$Ret->SetMessage("Failed to write to file.");
				}
			}

			return $Ret;
		}
	}

?>